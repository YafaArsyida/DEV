<?php

namespace App\Http\Livewire\LaporanEduPaySiswa;

use App\Exports\ExportSaldoEduPay;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;



class ExportSaldo extends Component
{
    public $siswas = [];
    public $totalSaldo = 0;

    protected $listeners = ['prepareExportSaldoEduPaySiswa'];

    public function prepareExportSaldoEduPaySiswa($siswas, $totalSaldo)
    {
        if (!isset($siswas) || !isset($totalSaldo)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }

        $this->siswas = $siswas;
        $this->totalSaldo = $totalSaldo;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function export()
    {
        if (empty($this->siswas)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tabungan tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "saldo-edupay-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportSaldoEduPay(
            $this->siswas,
            $this->totalSaldo
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.laporan-edu-pay-siswa.export-saldo');
    }
}
