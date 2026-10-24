<?php

namespace App\Services\PPDB;

use App\Models\PPDBPendidikan;

class PPDBPendidikanService
{
    /**
     * Membuat data pendidikan calon siswa.
     */
    public static function create(
        int $pendaftaranId,
        array $data
    ): PPDBPendidikan {
        $pendidikan = new PPDBPendidikan();

        $pendidikan->ppdb_pendaftaran_id = $pendaftaranId;

        self::isiData($pendidikan, $data);

        $pendidikan->save();

        return $pendidikan;
    }

    /**
     * Memperbarui data pendidikan calon siswa.
     */
    public static function update(
        PPDBPendidikan $pendidikan,
        array $data
    ): PPDBPendidikan {
        self::isiData($pendidikan, $data);

        $pendidikan->save();

        return $pendidikan->refresh();
    }

    /**
     * Memetakan data sekolah asal calon siswa.
     */
    private static function isiData(
        PPDBPendidikan $pendidikan,
        array $data
    ): void {
        $tidakAdaSekolah =
            ($data['jenjang_asal'] ?? null) === 'TIDAK_ADA';

        $pendidikan->jenjang_asal = $data['jenjang_asal'];

        $pendidikan->asal_sekolah = $tidakAdaSekolah
            ? null
            : self::nilaiOpsional($data['asal_sekolah'] ?? null);

        $pendidikan->alamat_asal_sekolah = $tidakAdaSekolah
            ? null
            : self::nilaiOpsional($data['alamat_asal_sekolah'] ?? null);

        $pendidikan->npsn_asal_sekolah = $tidakAdaSekolah
            ? null
            : self::nilaiOpsional($data['npsn_asal_sekolah'] ?? null);

        $pendidikan->tahun_lulus = $tidakAdaSekolah
            ? null
            : self::nilaiOpsional($data['tahun_lulus'] ?? null);

        $pendidikan->nomor_ijazah = $tidakAdaSekolah
            ? null
            : self::nilaiOpsional($data['nomor_ijazah'] ?? null);
    }

    /**
     * Mengubah nilai kosong menjadi null.
     */
    private static function nilaiOpsional($nilai): ?string
    {
        if ($nilai === null || trim((string) $nilai) === '') {
            return null;
        }

        return trim((string) $nilai);
    }
}
