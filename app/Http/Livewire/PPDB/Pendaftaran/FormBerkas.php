<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBPendaftaran;
use App\Models\PPDBOrangTua;
use App\Models\PPDBAlamat;
use App\Models\PPDBPendidikan;
use App\Models\PPDBBerkas;
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

    protected $messages = [
        'fileKartuKeluarga.required' => 'Silakan pilih file Kartu Keluarga.',
        'fileAktaKelahiran.required' => 'Silakan pilih file Akta Kelahiran.',
        'filePasFoto.required' => 'Silakan pilih pas foto calon siswa.',
        '*.mimes' => 'Format file harus JPG, JPEG, PNG, WebP, atau PDF.',
        '*.max' => 'Ukuran file maksimal 2 MB.',
    ];

    protected function ambilPendaftaran()
    {
        return PPDBPendaftaran::query()
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->where('ms_penguna_id', Auth::id())
            ->firstOrFail();
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

    public function mount($periodeId, $pendaftaranId)
    {
        $this->periodeId = $periodeId;
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaran();

        if (! PPDBPendidikan::where(
            'ppdb_pendaftaran_id',
            $pendaftaran->ppdb_pendaftaran_id
        )->exists()) {
            return redirect()->route('ppdb.pendaftaran.pendidikan');
        }

        $this->pastikanBolehUnggah($pendaftaran);
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
        $pendaftaran = $this->ambilPendaftaran();
        $this->pastikanBolehUnggah($pendaftaran);

        $this->validate($this->aturanBerkas());

        $daftarInput = $this->daftarInputBerkas();

        $berkasTersimpan = PPDBBerkas::query()
            ->where('ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id)
            ->get()
            ->keyBy('jenis_berkas');

        // Tiga berkas ini wajib tersedia, baik dari unggahan lama maupun baru.
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

        $disk = Storage::disk('local');
        $direktori = 'ppdb/berkas/' . $pendaftaran->ppdb_pendaftaran_id;

        $fileBaru = [];
        $pathLama = [];

        try {
            foreach ($daftarInput as $jenis => $property) {
                $file = $this->{$property};

                // File lama tetap dipakai jika pengguna tidak memilih pengganti.
                if (! $file) {
                    continue;
                }

                $mime = $file->getMimeType();

                if (! in_array($mime, [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'application/pdf',
                ], true)) {
                    throw ValidationException::withMessages([
                        $property => 'Isi file tidak sesuai dengan format yang diperbolehkan.',
                    ]);
                }

                if (
                    $jenis === 'PAS_FOTO'
                    && $mime === 'application/pdf'
                ) {
                    throw ValidationException::withMessages([
                        $property => 'Pas foto harus berupa gambar.',
                    ]);
                }

                $isPdf = $mime === 'application/pdf';
                $namaSimpan = Str::uuid()->toString()
                    . ($isPdf ? '.pdf' : '.webp');

                $path = $direktori . '/' . $namaSimpan;

                $disk->makeDirectory($direktori);

                if ($isPdf) {
                    $disk->putFileAs($direktori, $file, $namaSimpan);
                } else {
                    $this->simpanSebagaiWebP(
                        $file->getRealPath(),
                        $disk->path($path),
                        $property
                    );
                }

                $fileBaru[$jenis] = [
                    'property' => $property,
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'mime_type' => $isPdf ? 'application/pdf' : 'image/webp',
                    'ukuran_file' => $disk->size($path),
                ];
            }

            DB::transaction(function () use (
                $pendaftaran,
                $fileBaru,
                &$pathLama
            ) {
                $pendaftaranTerkunci = PPDBPendaftaran::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id)
                    ->where('ms_penguna_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->pastikanBolehUnggah($pendaftaranTerkunci);

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

                    $atribut = [
                        'nama_file' => $data['nama_file'],
                        'path_file' => $data['path_file'],
                        'mime_type' => $data['mime_type'],
                        'ukuran_file' => $data['ukuran_file'],
                        'status_verifikasi' => 'menunggu',
                        'catatan_verifikasi' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                    ];

                    if ($berkas) {
                        $berkas->update($atribut);
                    } else {
                        PPDBBerkas::create(array_merge($atribut, [
                            'ppdb_pendaftaran_id' =>
                                $pendaftaranTerkunci->ppdb_pendaftaran_id,
                            'jenis_berkas' => $jenis,
                        ]));
                    }
                }
            });

            // Hapus file lama hanya setelah seluruh perubahan database berhasil.
            foreach (array_unique($pathLama) as $path) {
                if ($disk->exists($path)) {
                    $disk->delete($path);
                }
            }

            foreach ($fileBaru as $data) {
                $this->{$data['property']} = null;
            }

            $this->resetValidation();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berkas berhasil disimpan.',
            ]);

            return redirect()->route('ppdb.pendaftaran.review');

        } catch (\Throwable $e) {
            // Bersihkan file baru jika proses gagal.
            foreach ($fileBaru as $data) {
                if ($disk->exists($data['path_file'])) {
                    $disk->delete($data['path_file']);
                }
            }

            throw $e;
        }
    }

    protected function simpanSebagaiWebP($source, $destination, $property)
    {
        if (! function_exists('imagewebp') ||
            ! function_exists('imagecreatefromstring')) {
            throw ValidationException::withMessages([
                $property => 'Server belum mendukung WebP. Silakan hubungi pengelola aplikasi.',
            ]);
        }

        $isi = file_get_contents($source);
        $gambar = $isi !== false ? @imagecreatefromstring($isi) : false;

        if (! $gambar) {
            throw ValidationException::withMessages([
                $property => 'Gambar tidak valid atau tidak dapat dibaca.',
            ]);
        }

        $lebar = imagesx($gambar);
        $tinggi = imagesy($gambar);
        $skala = min(1, 2000 / max($lebar, $tinggi));

        $lebarBaru = max(1, (int) round($lebar * $skala));
        $tinggiBaru = max(1, (int) round($tinggi * $skala));

        $hasil = imagecreatetruecolor($lebarBaru, $tinggiBaru);

        imagealphablending($hasil, false);
        imagesavealpha($hasil, true);

        // Latar putih untuk transparansi agar dokumen tetap mudah dibaca.
        $putih = imagecolorallocate($hasil, 255, 255, 255);
        imagefilledrectangle($hasil, 0, 0, $lebarBaru, $tinggiBaru, $putih);

        imagecopyresampled(
            $hasil,
            $gambar,
            0, 0, 0, 0,
            $lebarBaru,
            $tinggiBaru,
            $lebar,
            $tinggi
        );

        $berhasil = imagewebp($hasil, $destination, 82);

        imagedestroy($gambar);
        imagedestroy($hasil);

        if (! $berhasil) {
            throw ValidationException::withMessages([
                $property => 'Gambar gagal dikonversi. Silakan coba file lain.',
            ]);
        }
    }

    public function render()
    {
        $pendaftaran = $this->ambilPendaftaran();

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
