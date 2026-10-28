<?php

namespace App\Services\PPDB;

use App\Models\PPDBPendaftaran;
use Carbon\Carbon;

class PPDBKartuAljabarService
{
    /**
     * Data sementara untuk pengujian layout PDF.
     */
    public function getData(PPDBPendaftaran $pendaftaran): array
    {
        $siswa = $pendaftaran->ppdb_calon_siswa;
        $orangTua = $pendaftaran->ppdb_orangtua;
        $jalur = $pendaftaran->ppdb_jalur;

        $tanggalLahir = $siswa?->tanggal_lahir;

        return [
            'judul' => 'KARTU ALJABAR (AL HIDAYAH JEJAK BARU)',
            'subjudul' => 'Syarat Pendataan SPMB Tahun Pelajaran 2027/2028',

            'nomor_pendaftaran' => $pendaftaran->nomor_pendaftaran,
            'nama_siswa' => $siswa?->nama_siswa ?? '-',
            'tempat_lahir' => $siswa?->tempat_lahir ?? '-',

            'tanggal_lahir' => $tanggalLahir
                ? Carbon::parse($tanggalLahir)
                    ->locale('id')
                    ->translatedFormat('d F Y')
                : '-',

            'jenis_kelamin' => $siswa?->jenis_kelamin ?? '-',
            'nama_jalur' => $jalur?->nama_jalur ?? '-',

            // Prioritas nomor telepon ayah, kemudian ibu, lalu wali.
            'telepon' => $orangTua?->telepon_ayah
                ?: ($orangTua?->telepon_ibu
                ?: ($orangTua?->telepon_wali ?? '-')),

            'persyaratan' => [
                'Fotokopi akta kelahiran 1 lembar',
                'Fotokopi Kartu Keluarga 1 lembar',
                'Fotokopi KTP ayah dan ibu',
                'Pas foto 3 × 4 berlatar belakang merah',
                'Membayar biaya pendaftaran, skrining, dan seragam',
                'Menyiapkan soft file KK dan akta kelahiran',
            ],
        ];
    }
}
