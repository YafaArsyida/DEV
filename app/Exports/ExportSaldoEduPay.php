<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportSaldoEduPay implements FromView
{
    protected $siswas;
    protected $totalSaldo;

    public function __construct($siswas,  $totalSaldo)
    {
        $this->siswas = $siswas;
        $this->totalSaldo = $totalSaldo;
    }

    public function view(): View
    {
        return view('EXPORTS.saldo-edupay-siswa', [
            'siswas' => $this->siswas,
            'totalSaldo' => $this->totalSaldo,
        ]);
    }
}
