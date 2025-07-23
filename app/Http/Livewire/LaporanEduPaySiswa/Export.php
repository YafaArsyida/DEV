<?php

namespace App\Http\Livewire\LaporanEduPaySiswa;

use App\Exports\ExportTransaksiEduPay;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;


class Export extends Component
{
    public $laporansOnPage = [];
    public $totalPemasukan = 0;
    public $totalPengeluaran = 0;
    public $totalSaldo = 0;

    protected $listeners = ['prepareExportEduPay'];

    public function prepareExportEduPay($laporans, $totalPemasukan, $totalPengeluaran, $totalSaldo)
    {
        if (!isset($laporans)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }
        $this->laporansOnPage = $laporans;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->totalSaldo = $totalSaldo;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function export()
    {
        if (empty($this->laporansOnPage)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data EduPay tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "laporan-transaksi-edupay-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportTransaksiEduPay(
            $this->laporansOnPage,
            $this->totalPemasukan,
            $this->totalPengeluaran,
            $this->totalSaldo
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.laporan-edu-pay-siswa.export');
    }
}
