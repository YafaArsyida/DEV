<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTeleponSiswa implements FromView
{
    protected $siswas;

    public function __construct($siswas)
    {
        $this->siswas = $siswas;
    }

    public function view(): View
    {
        return view('EXPORTS.telepon-siswa-export', [
            'siswas' => $this->siswas,
        ]);
    }
    public function styles(Worksheet $sheet)
    {
        // Memberikan warna kuning pada kolom ID SISWA (kolom ke-2)
        $sheet->getStyle('A')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'], // Warna kuning
            ],
        ]);

        return [];
    }
}
