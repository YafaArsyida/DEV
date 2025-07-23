<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportEduCardSiswa implements ToCollection, WithHeadingRow
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
                    'ms_siswa_id' => $row['ms_siswa_id'],
                    'nama_siswa' => trim($row['nama']),
                    'educard' => trim($row['educard']),
                    'kelas' => $row['kelas'],
                ]);
            }
        }
    }

    public function getCollection()
    {
        return $this->siswaCollection;
    }
}
