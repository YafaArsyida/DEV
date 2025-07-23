<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ExportOverviewTabungan implements FromView
{

    protected $totalKredit;
    protected $totalDebit;
    protected $totalSaldo;

    public function __construct($totalKredit, $totalDebit, $totalSaldo)
    {
        $this->totalKredit = $totalKredit;
        $this->totalDebit = $totalDebit;
        $this->totalSaldo = $totalSaldo;
    }

    public function view(): View
    {
        return view('EXPORTS.overview-tabungan-siswa', [
            'totalKredit' => $this->totalKredit,
            'totalDebit' => $this->totalDebit,
            'totalSaldo' => $this->totalSaldo,
        ]);
    }
}
