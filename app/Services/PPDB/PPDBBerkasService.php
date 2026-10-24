<?php

namespace App\Services\PPDB;

use App\Models\PPDBBerkas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PPDBBerkasService
{
    /**
     * Menyimpan file fisik dan menyiapkan metadata.
     * Belum menyimpan metadata ke database.
     */
    public static function simpanFile(
        UploadedFile $file,
        int $pendaftaranId,
        string $jenis,
        string $property
    ): array {
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

        if ($jenis === 'PAS_FOTO' && $mime === 'application/pdf') {
            throw ValidationException::withMessages([
                $property => 'Pas foto harus berupa gambar.',
            ]);
        }

        $isPdf = $mime === 'application/pdf';

        $namaSimpan = Str::uuid()->toString()
            . ($isPdf ? '.pdf' : '.webp');

        $direktori = 'ppdb/berkas/' . $pendaftaranId;
        $path = $direktori . '/' . $namaSimpan;
        $disk = Storage::disk('local');

        $disk->makeDirectory($direktori);

        try {
            if ($isPdf) {
                $hasil = $disk->putFileAs(
                    $direktori,
                    $file,
                    $namaSimpan
                );

                if (! $hasil) {
                    throw new \RuntimeException(
                        'File PDF gagal disimpan.'
                    );
                }
            } else {
                self::simpanSebagaiWebP(
                    $file->getRealPath(),
                    $disk->path($path),
                    $property
                );
            }

            if (! $disk->exists($path)) {
                throw new \RuntimeException(
                    'File gagal disimpan ke penyimpanan.'
                );
            }

            return [
                'property' => $property,
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'mime_type' => $isPdf
                    ? 'application/pdf'
                    : 'image/webp',
                'ukuran_file' => $disk->size($path),
            ];
        } catch (\Throwable $e) {
            // Bersihkan file parsial jika proses penyimpanan gagal.
            if ($disk->exists($path)) {
                $disk->delete($path);
            }

            throw $e;
        }
    }

    /**
     * Membuat metadata berkas baru.
     */
    public static function create(
        int $pendaftaranId,
        string $jenis,
        array $data
    ): PPDBBerkas {
        $berkas = new PPDBBerkas();
        $berkas->ppdb_pendaftaran_id = $pendaftaranId;
        $berkas->jenis_berkas = $jenis;

        self::isiData($berkas, $data);

        $berkas->save();

        return $berkas;
    }

    /**
     * Memperbarui metadata berkas yang sudah ada.
     */
    public static function update(
        PPDBBerkas $berkas,
        array $data
    ): PPDBBerkas {
        self::isiData($berkas, $data);

        $berkas->save();

        return $berkas->refresh();
    }

    /**
     * Pemetaan metadata yang digunakan create dan update.
     */
    private static function isiData(
        PPDBBerkas $berkas,
        array $data
    ): void {
        $berkas->nama_file = $data['nama_file'];
        $berkas->path_file = $data['path_file'];
        $berkas->mime_type = $data['mime_type'];
        $berkas->ukuran_file = $data['ukuran_file'];

        // Berkas pengganti harus diverifikasi ulang.
        $berkas->status_verifikasi = 'menunggu';
        $berkas->catatan_verifikasi = null;
        $berkas->verified_by = null;
        $berkas->verified_at = null;
    }

    /**
     * Mengonversi gambar menjadi WebP maksimal 2000 px.
     */
    private static function simpanSebagaiWebP(
        string $source,
        string $destination,
        string $property
    ): void {
        if (
            ! function_exists('imagewebp')
            || ! function_exists('imagecreatefromstring')
        ) {
            throw ValidationException::withMessages([
                $property => 'Server belum mendukung WebP. Silakan hubungi pengelola aplikasi.',
            ]);
        }

        $isi = file_get_contents($source);
        $gambar = $isi !== false
            ? @imagecreatefromstring($isi)
            : false;

        if (! $gambar) {
            throw ValidationException::withMessages([
                $property => 'Gambar tidak valid atau tidak dapat dibaca.',
            ]);
        }

        $hasil = null;

        try {
            $lebar = imagesx($gambar);
            $tinggi = imagesy($gambar);

            $skala = min(1, 2000 / max($lebar, $tinggi));

            $lebarBaru = max(
                1,
                (int) round($lebar * $skala)
            );

            $tinggiBaru = max(
                1,
                (int) round($tinggi * $skala)
            );

            $hasil = imagecreatetruecolor(
                $lebarBaru,
                $tinggiBaru
            );

            // Latar putih untuk gambar yang memiliki transparansi.
            $putih = imagecolorallocate($hasil, 255, 255, 255);

            imagefilledrectangle(
                $hasil,
                0,
                0,
                $lebarBaru - 1,
                $tinggiBaru - 1,
                $putih
            );

            imagecopyresampled(
                $hasil,
                $gambar,
                0,
                0,
                0,
                0,
                $lebarBaru,
                $tinggiBaru,
                $lebar,
                $tinggi
            );

            if (! imagewebp($hasil, $destination, 82)) {
                throw ValidationException::withMessages([
                    $property => 'Gambar gagal dikonversi menjadi WebP.',
                ]);
            }
        } finally {
            imagedestroy($gambar);

            if ($hasil !== null) {
                imagedestroy($hasil);
            }
        }
    }

    /**
     * Menghapus file fisik berdasarkan path.
     */
    public static function hapusFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = Storage::disk('local');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    /**
     * Menghapus kumpulan file lama setelah transaksi berhasil.
     */
    public static function hapusFileLama(array $paths): void
    {
        foreach (array_unique($paths) as $path) {
            self::hapusFile($path);
        }
    }
}
