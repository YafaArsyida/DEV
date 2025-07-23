<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Exports\ExportSaldoTabunganSiswa;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportSaldo extends Component
{
    public $siswasOnPage = [];
    public $totalSaldo = 0;

    protected $listeners = ['prepareExportSaldo'];

    public function prepareExportSaldo($siswas, $totalSaldo)
    {
        if (!isset($siswas) || !isset($totalSaldo)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }

        $this->siswasOnPage = $siswas;
        $this->totalSaldo = $totalSaldo;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function exportSaldoTabunganSiswa()
    {
        if (empty($this->siswasOnPage)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tabungan tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "saldo-tabungan-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportSaldoTabunganSiswa(
            $this->siswasOnPage,
            $this->totalSaldo
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.laporan-tabungan-siswa.export-saldo');
    }
}
