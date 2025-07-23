<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportJenisTagihan implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $jenisTagihan;

    public function __construct()
    {
        $this->jenisTagihan = collect(); // Koleksi kosong untuk menyimpan data
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!empty($row['tagihan'])) {
                $this->jenisTagihan->push([
                    'nama_jenis_tagihan_siswa' => trim($row['tagihan']),
                ]);
            }
        }
    }

    public function getCollection()
    {
        return $this->jenisTagihan;
    }
}
