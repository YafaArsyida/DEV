<?php

namespace App\Services\PPDB;

use App\Models\PPDBJawaban;
use App\Models\PPDBPendaftaran;

class PPDBFormulirPendaftaranService
{
    public function getData(PPDBPendaftaran $pendaftaran): array
    {
        $pendaftaran->loadMissing([
            'ppdb_calon_siswa',
            'ppdb_orangtua',
            'ppdb_alamat',
            'ppdb_pendidikan',
        ]);

        $jawaban = PPDBJawaban::query()
            ->with('ppdb_pertanyaan')
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->getKey()
            )
            ->where(
                'ppdb_periode_id',
                $pendaftaran->ppdb_periode_id
            )
            ->get();

        return [
            'pendaftaran' => $pendaftaran,
            'calonSiswa' => $pendaftaran->ppdb_calon_siswa,
            'orangTua' => $pendaftaran->ppdb_orangtua,
            'alamat' => $pendaftaran->ppdb_alamat,
            'pendidikan' => $pendaftaran->ppdb_pendidikan,
            'jawaban' => $jawaban,
        ];
    }
}