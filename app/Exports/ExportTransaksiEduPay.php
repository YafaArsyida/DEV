<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportTransaksiEduPay implements FromView
{
    protected $laporans;
    protected $totalPemasukan;
    protected $totalPengeluaran;
    protected $totalSaldo;

    public function __construct($laporans, $totalPemasukan, $totalPengeluaran, $totalSaldo)
    {
        $this->laporans = $laporans;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->totalSaldo = $totalSaldo;
    }

    public function view(): View
    {
        return view('EXPORTS.laporan-rekap-edupay', [
            'laporans' => $this->laporans,
            'totalPemasukan' => $this->totalPemasukan,
            'totalPengeluaran' => $this->totalPengeluaran,
            'totalSaldo' => $this->totalSaldo,
        ]);
    }
}
