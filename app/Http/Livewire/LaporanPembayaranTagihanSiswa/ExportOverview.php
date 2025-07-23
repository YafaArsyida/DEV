<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use App\Exports\ExportOverviewPembayaran;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportOverview extends Component
{
    protected $listeners = ['prepareExportOverview'];

    public $methodsOnPage = [];
    public $classesOnPage = [];
    public $monthsOnPage = [];
    public $totalClassesOnPage = [];

    public function prepareExportOverview($methods, $classes, $months, $totalClasses)
    {
        if (!isset($methods) || !isset($classes) || !isset($months) || !isset($totalClasses)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }

        // Simpan data laporan untuk diproses
        $this->methodsOnPage = $methods;
        $this->classesOnPage = $classes;
        $this->monthsOnPage = $months;
        $this->totalClassesOnPage = $totalClasses;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function export()
    {
        if (empty($this->methodsOnPage) && empty($this->classesOnPage) && empty($this->monthsOnPage)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "laporan-overview-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);

        // Kirim data ke class Export untuk pengolahan Excel
        return Excel::download(new ExportOverviewPembayaran(
            $this->methodsOnPage,
            $this->classesOnPage,
            $this->monthsOnPage,
            $this->totalClassesOnPage
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.laporan-pembayaran-tagihan-siswa.export-overview');
    }
}
