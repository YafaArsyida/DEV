<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportSaldoTabunganSiswa implements FromView
{
    protected $siswasOnPage;
    protected $totalSaldo;

    public function __construct($siswasOnPage,  $totalSaldo)
    {
        $this->siswasOnPage = $siswasOnPage;
        $this->totalSaldo = $totalSaldo;
    }

    public function view(): View
    {
        return view('EXPORTS.saldo-tabungan-siswa-export', [
            'siswas' => $this->siswasOnPage,
            'totalSaldo' => $this->totalSaldo,
        ]);
    }
}
