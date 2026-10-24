<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBBerkas;
use App\Models\PPDBPendaftaran;
use App\Services\PPDB\PPDBBerkasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class EditBerkas extends Component
{
    use WithFileUploads;

    public $pendaftaranId;

    public $pendaftaran;
    public $berkas;

    public $fileKartuKeluarga;
    public $fileAktaKelahiran;
    public $filePasFoto;
    public $fileDokumenPendukung;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $this->pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $this->pastikanBolehUbah($this->pendaftaran);

        $this->muatBerkas();
    }

    protected $messages = [
        'fileKartuKeluarga.max' => 'Ukuran Kartu Keluarga maksimal 2 MB.',
        'fileAktaKelahiran.max' => 'Ukuran Akta Kelahiran maksimal 2 MB.',
        'filePasFoto.max' => 'Ukuran pas foto maksimal 2 MB.',
        'fileDokumenPendukung.max' => 'Ukuran dokumen pendukung maksimal 2 MB.',
        '*.mimes' => 'Format file harus JPG, JPEG, PNG, WebP, atau PDF.',
    ];

    protected function ambilPendaftaranMilikPengguna()
    {
        return PPDBPendaftaran::query()
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->firstOrFail();
    }

    protected function pastikanBolehUbah($pendaftaran)
    {
        if (in_array(
            strtolower(trim((string) $pendaftaran->status)),
            ['diterima', 'pengecekan'],
            true
        )) {
            abort(403, 'Berkas tidak dapat diubah pada tahap pendaftaran ini.');
        }
    }


    protected function muatBerkas()
    {
        $this->berkas = PPDBBerkas::query()
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->get()
            ->keyBy('jenis_berkas');
    }

    public function updatedFileKartuKeluarga()
    {
        $this->validateOnly(
            'fileKartuKeluarga',  $this->aturanValidasi()
        );
    }

    public function updatedFileAktaKelahiran()
    {
        $this->validateOnly(
            'fileAktaKelahiran',  $this->aturanValidasi()
        );
    }

    public function updatedFilePasFoto()
    {
        $this->validateOnly(
            'filePasFoto', $this->aturanValidasi()
        );
    }

    public function updatedFileDokumenPendukung()
    {
        $this->validateOnly(
            'fileDokumenPendukung', $this->aturanValidasi()
        );
    }

    protected function daftarInputBerkas()
    {
        return [
            'KARTU_KELUARGA' => 'fileKartuKeluarga',
            'AKTA_KELAHIRAN' => 'fileAktaKelahiran',
            'PAS_FOTO' => 'filePasFoto',
            'DOKUMEN_PENDUKUNG' => 'fileDokumenPendukung',
        ];
    }

    protected function aturanValidasi()
    {
        return [
            'fileKartuKeluarga' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
            'fileAktaKelahiran' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
            'filePasFoto' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp',
            'fileDokumenPendukung' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
        ];
    }

    
    public function update()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $this->pastikanBolehUbah($pendaftaran);

        $this->validate($this->aturanValidasi());

        $daftarInput = $this->daftarInputBerkas();

        $berkasTersimpan = PPDBBerkas::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->get()
            ->keyBy('jenis_berkas');

        // Berkas wajib harus tersedia dari unggahan lama atau baru.
        foreach ([
            'KARTU_KELUARGA',
            'AKTA_KELAHIRAN',
            'PAS_FOTO',
        ] as $jenis) {
            $property = $daftarInput[$jenis];

            if (
                ! $this->{$property}
                && ! $berkasTersimpan->has($jenis)
            ) {
                throw ValidationException::withMessages([
                    $property => 'Berkas ini wajib diunggah.',
                ]);
            }
        }

        $fileBaru = [];
        $pathLama = [];
        $transaksiBerhasil = false;

        try {
            // Simpan file pengganti. File lama belum dihapus.
            foreach ($daftarInput as $jenis => $property) {
                $file = $this->{$property};

                // Tidak ada penggantian, pertahankan berkas lama.
                if (! $file) {
                    continue;
                }

                $fileBaru[$jenis] = PPDBBerkasService::simpanFile(
                    $file,
                    (int) $pendaftaran->ppdb_pendaftaran_id,
                    $jenis,
                    $property
                );
            }

            DB::transaction(function () use (
                $pendaftaran,
                $fileBaru,
                $daftarInput,
                &$pathLama
            ) {
                $pendaftaranTerkunci = PPDBPendaftaran::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->where('ms_pengguna_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->pastikanBolehUbah($pendaftaranTerkunci);

                // Ambil kondisi berkas terbaru di dalam transaksi.
                $berkasTerkini = PPDBBerkas::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaranTerkunci->ppdb_pendaftaran_id
                    )
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('jenis_berkas');

                // Pastikan berkas wajib tetap tersedia.
                foreach ([
                    'KARTU_KELUARGA',
                    'AKTA_KELAHIRAN',
                    'PAS_FOTO',
                ] as $jenis) {
                    $property = $daftarInput[$jenis];

                    if (
                        ! isset($fileBaru[$jenis])
                        && ! $berkasTerkini->has($jenis)
                    ) {
                        throw ValidationException::withMessages([
                            $property => 'Berkas ini wajib diunggah.',
                        ]);
                    }
                }

                foreach ($fileBaru as $jenis => $data) {
                    $berkas = PPDBBerkas::query()
                        ->where(
                            'ppdb_pendaftaran_id',
                            $pendaftaranTerkunci->ppdb_pendaftaran_id
                        )
                        ->where('jenis_berkas', $jenis)
                        ->lockForUpdate()
                        ->first();

                    if ($berkas && $berkas->path_file) {
                        $pathLama[] = $berkas->path_file;
                    }

                    if ($berkas) {
                        PPDBBerkasService::update($berkas, $data);
                    } else {
                        PPDBBerkasService::create(
                            (int) $pendaftaranTerkunci->ppdb_pendaftaran_id,
                            $jenis,
                            $data
                        );
                    }
                }
            });

            // Database sudah berhasil diperbarui.
            $transaksiBerhasil = true;

            // Hapus file lama hanya setelah transaksi berhasil.
            PPDBBerkasService::hapusFileLama($pathLama);

            // Reset input file pengganti.
            foreach ($fileBaru as $data) {
                $this->{$data['property']} = null;
            }

            $this->resetValidation();

            $slugJenjang = $pendaftaran
                ->ppdb_periode
                ->ms_jenjang
                ->slug;

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Perubahan berhasil disimpan.',
            ]);

            return redirect()->route('ppdb.pendaftaran.berkas', [
                'jenjang' => $slugJenjang,
            ]);
        } catch (\Throwable $e) {
            // Jika transaksi gagal, bersihkan file yang baru dibuat.
            // Jika transaksi berhasil, jangan hapus file yang direferensikan
            // oleh database meskipun penghapusan file lama mengalami error.
            if (! $transaksiBerhasil) {
                foreach ($fileBaru as $data) {
                    PPDBBerkasService::hapusFile(
                        $data['path_file']
                    );
                }
            }

            throw $e;
        }
    }

   
    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $berkas = PPDBBerkas::query()
            ->where(
                'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
            );


        return view('livewire.p-p-d-b.pendaftaran.edit-berkas',[
            'pendaftaran' => $pendaftaran,

            'kartuKeluarga' => (clone $berkas)
                ->where('jenis_berkas', 'KARTU_KELUARGA')
                ->first(),

            'aktaKelahiran' => (clone $berkas)
                ->where('jenis_berkas', 'AKTA_KELAHIRAN')
                ->first(),

            'pasFoto' => (clone $berkas)
                ->where('jenis_berkas', 'PAS_FOTO')
                ->first(),

            'dokumenPendukung' => (clone $berkas)
                ->where('jenis_berkas', 'DOKUMEN_PENDUKUNG')
                ->first(),
        ]);
    }
}
