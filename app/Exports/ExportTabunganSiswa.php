<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportTabunganSiswa implements FromView
{
    protected $tabungans;

    public function __construct($tabungans)
    {
        $this->tabungans = $tabungans;
    }

    public function view(): View
    {
        return view('EXPORTS.laporan-tabungan-siswa-export', [
            'tabungans' => $this->tabungans,
        ]);
    }
}
