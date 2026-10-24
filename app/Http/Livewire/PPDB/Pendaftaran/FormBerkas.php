<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBPendaftaran;
use App\Models\PPDBOrangTua;
use App\Models\PPDBAlamat;
use App\Models\PPDBPendidikan;
use App\Models\PPDBBerkas;
use App\Models\PPDBPeriode;
use App\Services\PPDB\PPDBBerkasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormBerkas extends Component
{
    use WithFileUploads;

    public $periodeId;
    public $pendaftaranId;

    public $fileKartuKeluarga;
    public $fileAktaKelahiran;
    public $filePasFoto;
    public $fileDokumenPendukung;

    protected $listeners = [
        'parameterUpdated' => '$refresh',
    ];

    protected function rules()
    {
        return [
            'fileKartuKeluarga' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:2048',
            ],
            'fileAktaKelahiran' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:2048',
            ],
            'filePasFoto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'fileDokumenPendukung' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:2048',
            ],
        ];
    }

    protected $messages = [
        'fileKartuKeluarga.required' => 'Silakan pilih file Kartu Keluarga.',
        'fileAktaKelahiran.required' => 'Silakan pilih file Akta Kelahiran.',
        'filePasFoto.required' => 'Silakan pilih pas foto calon siswa.',
        '*.mimes' => 'Format file harus JPG, JPEG, PNG, WebP, atau PDF.',
        '*.max' => 'Ukuran file maksimal 2 MB.',
    ];

    public function mount($periodeId = null, $pendaftaranId = null)
    {
        $this->periodeId = $periodeId;
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        // Form awal hanya digunakan jika data belum tersedia.
        $berkas = PPDBBerkas::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if ($berkas) {
            $slugJenjang = PPDBPeriode::query()
                ->with('ms_jenjang')
                ->whereKey($pendaftaran->ppdb_periode_id)
                ->firstOrFail()
                ->ms_jenjang
                ->slug;

            return redirect()->route('ppdb.pendaftaran.berkas',  [
                'jenjang' => $slugJenjang,
            ]);
        }
    }

    protected function ambilPendaftaranMilikPengguna()
    {
        $periode = PPDBPeriode::query()
            ->with('ms_jenjang')
            ->whereKey($this->periodeId)
            ->where('status', 'aktif')
            ->first();

        if (! $periode) {
            abort(403, 'Periode PPDB tidak aktif.');
        }

        $pendaftaran = PPDBPendaftaran::query()
            ->with('ppdb_periode.ms_jenjang')
            ->whereKey($this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->where('ppdb_periode_id', $periode->ppdb_periode_id)
            ->first();

        if (! $pendaftaran) {
            abort(403, 'Pendaftaran tidak ditemukan atau bukan milik Anda.');
        }

        return $pendaftaran;
    }

    protected function pastikanBolehUnggah($pendaftaran)
    {
        if (in_array(
            strtolower(trim((string) $pendaftaran->status)),
            ['diterima', 'pengecekan'],
            true
        )) {
            abort(403, 'Berkas tidak dapat diubah pada tahap pendaftaran ini.');
        }
    }

    protected function jenisBerkas()
    {
        return [
            'KARTU_KELUARGA' => [
                'property' => 'fileKartuKeluarga',
                'wajib' => true,
            ],
            'AKTA_KELAHIRAN' => [
                'property' => 'fileAktaKelahiran',
                'wajib' => true,
            ],
            'PAS_FOTO' => [
                'property' => 'filePasFoto',
                'wajib' => true,
            ],
            'DOKUMEN_PENDUKUNG' => [
                'property' => 'fileDokumenPendukung',
                'wajib' => false,
            ],
        ];
    }

    protected function aturanValidasi($jenis)
    {
        $property = $this->jenisBerkas()[$jenis]['property'];

        return [
            $property => [
                'required',
                'file',
                'max:2048',
                'mimes:jpg,jpeg,png,webp,pdf',
            ],
        ];
    }

    public function updatedFileKartuKeluarga()
    {
        $this->validateOnly('fileKartuKeluarga', $this->aturanValidasi('KARTU_KELUARGA'));
    }

    public function updatedFileAktaKelahiran()
    {
        $this->validateOnly('fileAktaKelahiran', $this->aturanValidasi('AKTA_KELAHIRAN'));
    }

    public function updatedFilePasFoto()
    {
        $this->validateOnly('filePasFoto', $this->aturanValidasi('PAS_FOTO'));
    }

    public function updatedFileDokumenPendukung()
    {
        $this->validateOnly('fileDokumenPendukung', $this->aturanValidasi('DOKUMEN_PENDUKUNG'));
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

    protected function aturanBerkas()
    {
        return [
            'fileKartuKeluarga' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
            'fileAktaKelahiran' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
            'filePasFoto' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp',
            'fileDokumenPendukung' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
        ];
    }

    
    public function simpan()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $this->pastikanBolehUnggah($pendaftaran);

        $this->validate($this->aturanBerkas());

        $daftarInput = $this->daftarInputBerkas();

        $berkasTersimpan = PPDBBerkas::query()
            ->where(
                'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
            )
            ->get()
            ->keyBy('jenis_berkas');

        // Tiga berkas wajib harus tersedia dari unggahan lama atau baru.
        $wajib = [
            'KARTU_KELUARGA',
            'AKTA_KELAHIRAN',
            'PAS_FOTO',
        ];

        foreach ($wajib as $jenis) {
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
            // Simpan file baru terlebih dahulu. File lama belum dihapus.
            foreach ($daftarInput as $jenis => $property) {
                $file = $this->{$property};

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

                $this->pastikanBolehUnggah($pendaftaranTerkunci);

                // Periksa kembali berkas wajib berdasarkan data terbaru.
                $berkasTerkini = PPDBBerkas::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaranTerkunci->ppdb_pendaftaran_id
                    )
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('jenis_berkas');

                foreach ([
                    'KARTU_KELUARGA',
                    'AKTA_KELAHIRAN',
                    'PAS_FOTO',
                ] as $jenis) {
                    $property = $this->daftarInputBerkas()[$jenis];

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

                $pendaftaranTerkunci->update([
                    'current_step' => 6,
                ]);
            });

            // Transaksi selesai dan metadata berkas berhasil disimpan.
            $transaksiBerhasil = true;

            // File lama baru dihapus setelah transaksi database berhasil.
            PPDBBerkasService::hapusFileLama($pathLama);

            foreach ($fileBaru as $data) {
                $this->{$data['property']} = null;
            }

            $this->resetValidation();

            $slugJenjang = $pendaftaran
                ->ppdb_periode
                ->ms_jenjang
                ->slug;

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berkas berhasil disimpan.',
            ]);

            return redirect()->route('ppdb.pendaftaran.review', [
                'jenjang' => $slugJenjang,
            ]);
        } catch (\Throwable $e) {
            // Jangan hapus file baru jika database sudah berhasil menyimpan
            // metadata yang merujuk pada file tersebut.
            if (! $transaksiBerhasil) {
                foreach ($fileBaru as $data) {
                    PPDBBerkasService::hapusFile($data['path_file']);
                }
            }

            throw $e;
        }
    }


    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $berkas = PPDBBerkas::query()
            ->where('ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id)
            ->get()
            ->keyBy('jenis_berkas');

        return view('livewire.p-p-d-b.pendaftaran.form-berkas',[
            'pendaftaran' => $pendaftaran,
            'berkas' => $berkas,
        ]);
    }
}
