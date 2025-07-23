<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportOverviewPembayaran implements FromView
{
    protected $methods;
    protected $classes;
    protected $months;
    protected $totalClasses;

    public function __construct($methods, $classes, $months, $totalClasses)
    {
        $this->methods = $methods;
        $this->classes = $classes;
        $this->months = $months;
        $this->totalClasses = $totalClasses;
    }

    public function view(): View
    {
        return view('EXPORTS.laporan-pembayaran-overview-export', [
            'methods' => $this->methods,
            'classes' => $this->classes,
            'months' => $this->months,
            'totalClasses' => $this->totalClasses,
        ]);
    }
}
