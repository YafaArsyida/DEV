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
                'status' => 'draft',
                'submitted_at' => null,
                'submitted_by' => null,
                'created_by' => $data['created_by'] ?? null,
                'catatan_perbaikan' => null,
            ]);
        });
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
