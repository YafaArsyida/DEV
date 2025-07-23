<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Illuminate\Support\Collection;

class ImportPegawai implements ToCollection, WithHeadingRow
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
        // Simpan data siswa yang tidak kosong ke koleksi lokal
        foreach ($rows as $row) {
            if (!empty($row['pegawai']) && !empty($row['telepon'])) {
                // if (!empty($row['pegawai']) && !empty($row['telepon']) && !empty($row['nip']) && !empty($row['deskripsi'])) {
                $this->pegawaiCollection->push([
                    'nama_pegawai' => $row['pegawai'],
                    'telepon'    => $row['telepon'],
                    'nip'    => $row['nip'],
                    'deskripsi'    => $row['deskripsi'],
                ]);
            }
        }
    }

    public function getCollection()
    {
        return $this->pegawaiCollection;
    }
}
