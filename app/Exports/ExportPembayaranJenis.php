<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportPembayaranJenis implements FromView
{
    protected $laporans;
    protected $totals;

    public function __construct($laporans, $totals)
    {
        $this->laporans = $laporans;
        $this->totals = $totals;
    }

    public function view(): View
    {
        return view('EXPORTS.laporan-pembayaran-jenis-export', [
            'laporans' => $this->laporans,
            'totals' => $this->totals,
        ]);
    }
}
