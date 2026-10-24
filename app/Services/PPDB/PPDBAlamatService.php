<?php

namespace App\Services\PPDB;

use App\Models\PPDBAlamat;

class PPDBAlamatService
{
    /**
     * Menyimpan alamat pendaftaran.
     */
    public static function create(int $pendaftaranId, array $data): PPDBAlamat 
    {
        $alamat = new PPDBAlamat();

        $alamat->ppdb_pendaftaran_id = $pendaftaranId;

        self::isiData($alamat, $data);

        $alamat->save();

        return $alamat;
    }

    /**
     * Memperbarui alamat pendaftaran.
     */
    public static function update(PPDBAlamat $alamat, array $data): PPDBAlamat 
    {
        self::isiData($alamat, $data);

        $alamat->save();

        return $alamat->refresh();
    }

    /**
     * Memetakan dan menormalisasi data alamat.
     */
    private static function isiData(
        PPDBAlamat $alamat,
        array $data
    ): void {
        $alamat->alamat_lengkap = self::buatAlamatLengkap($data);
        $alamat->provinsi = trim($data['provinsi']);
        $alamat->kabupaten = trim($data['kabupaten']);
        $alamat->kecamatan = trim($data['kecamatan']);
        $alamat->kelurahan_desa = trim($data['kelurahan_desa']);

        $alamat->kode_pos = self::nilaiOpsional($data['kode_pos'] ?? null);

        $alamat->jenis_tempat_tinggal = $data['jenis_tempat_tinggal'];

        // Informasi perjalanan ke sekolah
        $alamat->jarak_ke_sekolah = $data['jarak_ke_sekolah'] ?? null;

        $alamat->waktu_tempuh_ke_sekolah = $data['waktu_tempuh_ke_sekolah'] ?? null;

        $alamat->moda_transportasi = $data['moda_transportasi'] ?? null;
    }

    /**
     * Menggabungkan RT/RW jika field-nya tersedia.
     */
    private static function buatAlamatLengkap(array $data): string
    {
        $alamatLengkap = trim($data['alamat_lengkap']);

        // Jika RT/RW tidak dikirim, gunakan alamat apa adanya.
        // Ini mencegah RT/RW ditambahkan ulang saat edit.
        if (
            !array_key_exists('rt', $data)
            && !array_key_exists('rw', $data)
        ) {
            return $alamatLengkap;
        }

        $rtRw = [];

        $rt = trim((string) ($data['rt'] ?? ''));

        if ($rt !== '') {
            $rtRw[] = 'RT ' . str_pad($rt, 3, '0', STR_PAD_LEFT);
        }

        $rw = trim((string) ($data['rw'] ?? ''));

        if ($rw !== '') {
            $rtRw[] = 'RW ' . str_pad($rw, 3, '0', STR_PAD_LEFT);
        }

        if (!empty($rtRw)) {
            $alamatLengkap .= ', ' . implode('/', $rtRw);
        }

        return $alamatLengkap;
    }

    /**
     * Mengubah string kosong menjadi null.
     */
    private static function nilaiOpsional($nilai): ?string
    {
        if ($nilai === null || trim((string) $nilai) === '') {
            return null;
        }

        return trim((string) $nilai);
    }
}
