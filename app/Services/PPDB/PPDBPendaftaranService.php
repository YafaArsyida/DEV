<?php

namespace App\Services\PPDB;

use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PPDBPendaftaranService
{
    /**
     * Membuat pendaftaran baru.
     */
    public static function create(array $data): PPDBPendaftaran
    {
        return DB::transaction(function () use ($data) {
            return PPDBPendaftaran::create([
                'ms_pengguna_id' => $data['ms_pengguna_id'] ?? null,
                'ppdb_periode_id' => $data['ppdb_periode_id'],
                'ppdb_gelombang_id' => $data['ppdb_gelombang_id'],
                'ppdb_jalur_id' => $data['ppdb_jalur_id'],
                'nomor_pendaftaran' => self::buatNomorPendaftaran(),
                'current_step' => 1,
                'status' => $data['status'],
                'submitted_at' => null,
                'submitted_by' => null,
                'created_by' => $data['created_by'] ?? null,
                'catatan_perbaikan' => null,
            ]);
        });
    }

    /**
     * Menghapus pendaftaran beserta seluruh data formulirnya.
     *
     * Data database di-soft-delete dalam satu transaksi.
     * File fisik dihapus setelah transaksi berhasil.
     */
    public static function delete(PPDBPendaftaran $pendaftaran): void
    {
        $pendaftaranId = (int) $pendaftaran->ppdb_pendaftaran_id;

        // Kumpulkan path berkas sebelum menghapus data relasi.
        $paths = $pendaftaran->ppdb_berkas()
            ->withTrashed()
            ->pluck('path_file')
            ->filter()
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($pendaftaran) {
            // Data formulir calon siswa.
            $pendaftaran->ppdb_calon_siswa?->delete();

            // Data orang tua/wali.
            $pendaftaran->ppdb_orangtua?->delete();

            // Data alamat.
            $pendaftaran->ppdb_alamat?->delete();

            // Data pendidikan.
            $pendaftaran->ppdb_pendidikan?->delete();

            // Berkas dan jawaban formulir.
            $pendaftaran->ppdb_berkas()->delete();
            $pendaftaran->ppdb_jawaban()->delete();

            // Pendaftaran utama.
            $pendaftaran->delete();
        });

        // Hanya dijalankan jika transaksi database berhasil.
        PPDBBerkasService::hapusFileLama(
            array_filter(
                $paths,
                fn ($path) => is_string($path)
                    && str_starts_with(
                        $path,
                        'ppdb/berkas/' . $pendaftaranId . '/'
                    )
                    && ! preg_match('#(?:^|/)\.\.(?:/|$)#', $path)
            )
        );
    }

    /**
     * Memperbarui jalur pendaftaran yang sudah ada.
     */
    public static function updateJalur(PPDBPendaftaran $pendaftaran, int $jalurId): PPDBPendaftaran 
    {
        $pendaftaran->update([
            'ppdb_jalur_id' => $jalurId,
        ]);

        return $pendaftaran->refresh();
    }


    /**
     * Membuat nomor pendaftaran unik.
     */
    private static function buatNomorPendaftaran(): string
    {
        do {
            $nomor = 'PPDB-' . now()->format('Y') . '-'
                . Str::upper(Str::random(6));

            $sudahAda = PPDBPendaftaran::query()
                ->where('nomor_pendaftaran', $nomor)
                ->exists();
        } while ($sudahAda);

        return $nomor;
    }
}
