<?php

namespace App\Services\PPDB;

use App\Models\PPDBOrangTua;

class PPDBOrangTuaService
{
    /**
     * Menyimpan data orang tua calon siswa.
     */
    public static function create(int $pendaftaranId, array $data): PPDBOrangTua 
    {
        $orangTua = new PPDBOrangTua();

        $orangTua->ppdb_pendaftaran_id = $pendaftaranId;

        self::isiData($orangTua, $data);

        $orangTua->save();

        return $orangTua;
    }

    /**
     * Memperbarui data orang tua calon siswa.
     */
    public static function update(PPDBOrangTua $orangTua, array $data): PPDBOrangTua 
    {
        self::isiData($orangTua, $data);

        $orangTua->save();

        return $orangTua->refresh();
    }

    /**
     * Memetakan data ayah, ibu, dan wali.
     */
    private static function isiData(PPDBOrangTua $orangTua, array $data): void 
    {
        // Data ayah
        $orangTua->nama_ayah = $data['ayah_nama'];
        $orangTua->nik_ayah = $data['ayah_nik'] ?: null;
        $orangTua->tempat_lahir_ayah =
            $data['ayah_tempat_lahir'] ?: null;
        $orangTua->tanggal_lahir_ayah =
            $data['ayah_tanggal_lahir'] ?: null;
        $orangTua->berkebutuhan_khusus_ayah =
            $data['ayah_berkebutuhan_khusus'];
        $orangTua->pendidikan_ayah =
            $data['ayah_pendidikan'] ?: null;
        $orangTua->pekerjaan_ayah =
            $data['ayah_pekerjaan'] ?: null;
        $orangTua->penghasilan_ayah =
            $data['ayah_penghasilan'] ?: null;
        $orangTua->telepon_ayah =
            $data['ayah_whatsapp'] ?: null;

        // Data ibu
        $orangTua->nama_ibu = $data['ibu_nama'];
        $orangTua->nik_ibu = $data['ibu_nik'] ?: null;
        $orangTua->tempat_lahir_ibu =
            $data['ibu_tempat_lahir'] ?: null;
        $orangTua->tanggal_lahir_ibu =
            $data['ibu_tanggal_lahir'] ?: null;
        $orangTua->berkebutuhan_khusus_ibu =
            $data['ibu_berkebutuhan_khusus'];
        $orangTua->pendidikan_ibu =
            $data['ibu_pendidikan'] ?: null;
        $orangTua->pekerjaan_ibu =
            $data['ibu_pekerjaan'] ?: null;
        $orangTua->penghasilan_ibu =
            $data['ibu_penghasilan'] ?: null;
        $orangTua->telepon_ibu =
            $data['ibu_whatsapp'] ?: null;

        // Data wali
        if (!empty($data['gunakan_wali'])) {
            $orangTua->nama_wali = $data['wali_nama'];
            $orangTua->nik_wali = $data['wali_nik'] ?: null;
            $orangTua->tempat_lahir_wali =
                $data['wali_tempat_lahir'] ?: null;
            $orangTua->tanggal_lahir_wali =
                $data['wali_tanggal_lahir'] ?: null;
            $orangTua->berkebutuhan_khusus_wali =
                $data['wali_berkebutuhan_khusus'];
            $orangTua->hubungan_wali =
                $data['wali_hubungan'];
            $orangTua->pekerjaan_wali =
                $data['wali_pekerjaan'] ?: null;
            $orangTua->telepon_wali =
                $data['wali_whatsapp'] ?: null;
        } else {
            $orangTua->nama_wali = null;
            $orangTua->nik_wali = null;
            $orangTua->tempat_lahir_wali = null;
            $orangTua->tanggal_lahir_wali = null;
            $orangTua->berkebutuhan_khusus_wali = null;
            $orangTua->hubungan_wali = null;
            $orangTua->pekerjaan_wali = null;
            $orangTua->telepon_wali = null;
        }
    }
}
