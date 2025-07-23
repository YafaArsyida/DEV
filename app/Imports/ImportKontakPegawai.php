<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Http\Controllers\HelperController;

class ImportKontakPegawai implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $pegawaiCollection;

    public function __construct()
    {
        $this->pegawaiCollection = collect(); // Koleksi kosong untuk menyimpan data
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!empty($row['nama'])) {
                $this->pegawaiCollection->push([
                    'ms_pegawai_id' => $row['ms_pegawai_id'],
                    'nama_pegawai' => trim($row['nama']),
                    'telepon'    => HelperController::normalizePhoneNumber($row['telepon'] ?? null), // Normalisasi telepon
                    'email' => $row['email'],
                ]);
            }
        }
    }

    public function getCollection()
    {
        return $this->pegawaiCollection;
    }
}
