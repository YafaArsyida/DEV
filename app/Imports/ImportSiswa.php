<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Http\Controllers\HelperController;

use Illuminate\Support\Collection;

class ImportSiswa implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $siswaCollection;

    public function __construct()
    {
        $this->siswaCollection = collect(); // Koleksi kosong untuk menyimpan data
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!empty($row['nama'])) {
                $this->siswaCollection->push([
                    'nama_siswa' => trim($row['nama']),
                    'telepon'    => HelperController::normalizePhoneNumber($row['telepon'] ?? null), // Normalisasi telepon
                ]);
            }
        }
    }

    public function getCollection()
    {
        return $this->siswaCollection;
    }
}
