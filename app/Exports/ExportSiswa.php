<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportSiswa implements FromView
{
    protected $siswas;

    public function __construct($siswas)
    {
        $this->siswas = $siswas;
    }

    public function view(): View
    {
        return view('EXPORTS.siswa-export', [
            'siswas' => $this->siswas,
        ]);
    }
}
