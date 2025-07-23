<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportRekapTabunganSiswa implements FromView
{
    protected $laporans;
    protected $totalKredit;
    protected $totalDebit;
    protected $totalSaldo;

    public function __construct($laporans, $totalKredit, $totalDebit, $totalSaldo)
    {
        $this->laporans = $laporans;
        $this->totalKredit = $totalKredit;
        $this->totalDebit = $totalDebit;
        $this->totalSaldo = $totalSaldo;
    }

    public function view(): View
    {
        return view('EXPORTS.rekap-tabungan-siswa-export', [
            'laporans' => $this->laporans,
            'totalKredit' => $this->totalKredit,
            'totalDebit' => $this->totalDebit,
            'totalSaldo' => $this->totalSaldo,
        ]);
    }
}
