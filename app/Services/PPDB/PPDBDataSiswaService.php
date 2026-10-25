<?php

namespace App\Services\PPDB;

use App\Models\PPDBCalonSiswa;

class PPDBDataSiswaService
{
    /**
     * Membuat data calon siswa.
     */
    public static function create(array $data): PPDBCalonSiswa
    {
        $calonSiswa = new PPDBCalonSiswa();

        $calonSiswa->ppdb_pendaftaran_id = $data['ppdb_pendaftaran_id'];

        self::isiData($calonSiswa, $data);

        $calonSiswa->save();

        return $calonSiswa;
    }

    /**
     * Memperbarui data calon siswa.
     */
    public static function update(PPDBCalonSiswa $calonSiswa, array $data): PPDBCalonSiswa 
    {
        self::isiData($calonSiswa, $data);

        $calonSiswa->save();

        return $calonSiswa->refresh();
    }

    /**
     * Memetakan data identitas, keluarga, kesehatan,
     * kondisi fisik, dan bantuan sosial calon siswa.
     */
    private static function isiData(
        PPDBCalonSiswa $calonSiswa,
        array $data
    ): void {
        // Identitas siswa
        $calonSiswa->nama_siswa = $data['nama_siswa'];
        $calonSiswa->nama_panggilan = self::nilaiOpsional($data['nama_panggilan'] ?? null);
        $calonSiswa->cita_cita = self::nilaiOpsional($data['cita_cita'] ?? null);
        $calonSiswa->hobi = self::nilaiOpsional($data['hobi'] ?? null);
        $calonSiswa->kewarganegaraan = $data['kewarganegaraan'];

        $calonSiswa->nisn = self::nilaiOpsional($data['nisn'] ?? null);
        $calonSiswa->nik = $data['nik'];
        $calonSiswa->tempat_lahir = $data['tempat_lahir'];
        $calonSiswa->tanggal_lahir = $data['tanggal_lahir'];
        $calonSiswa->jenis_kelamin = $data['jenis_kelamin'];
        $calonSiswa->agama = $data['agama'];
        $calonSiswa->anak_ke = $data['anak_ke'];

        // Data keluarga
        $calonSiswa->jumlah_saudara_kandung = $data['jumlah_saudara_kandung'];
        $calonSiswa->jumlah_saudara_tiri = $data['jumlah_saudara_tiri'];
        $calonSiswa->status_yatim_piatu = $data['status_yatim_piatu'];

        // Kondisi khusus dan kesehatan
        $calonSiswa->berkebutuhan_khusus = $data['berkebutuhan_khusus'];
        $calonSiswa->penyakit = self::nilaiOpsional($data['penyakit'] ?? null);
        $calonSiswa->alergi = self::nilaiOpsional($data['alergi'] ?? null);

        // Kondisi fisik
        $calonSiswa->tinggi_badan = self::nilaiOpsional($data['tinggi_badan'] ?? null);
        $calonSiswa->berat_badan = self::nilaiOpsional($data['berat_badan'] ?? null);
        $calonSiswa->ukuran_baju = $data['ukuran_baju'];

        // Bantuan sosial disimpan sebagai teks.
        $bantuanSosial = $data['penerima_bantuan_sosial'] ?? [];

        if (is_array($bantuanSosial)) {
            $bantuanSosial = implode(', ', $bantuanSosial);
        }

        $calonSiswa->penerima_bantuan_sosial =
            self::nilaiOpsional($bantuanSosial);

        $calonSiswa->nomor_bantuan_sosial = self::nilaiOpsional($data['nomor_bantuan_sosial'] ?? null);
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
