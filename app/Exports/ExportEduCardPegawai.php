<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportEduCardPegawai implements FromView
{
    protected $pegawais;

    public function __construct($pegawais)
    {
        $this->pegawais = $pegawais;
    }

    public function view(): View
    {
        return view('EXPORTS.educard-pegawai-export', [
            'pegawais' => $this->pegawais,
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
