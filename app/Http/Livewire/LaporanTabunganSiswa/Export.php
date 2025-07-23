<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Exports\ExportRekapTabunganSiswa;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    public $laporansOnPage = [];
    public $totalKredit = 0;
    public $totalDebit = 0;
    public $totalSaldo = 0;

    protected $listeners = ['prepareExport'];

    public function prepareExport($laporan, $totalKredit, $totalDebit, $totalSaldo)
    {
        if (!isset($laporan)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }
        $this->laporansOnPage = $laporan;
        $this->totalKredit = $totalKredit;
        $this->totalDebit = $totalDebit;
        $this->totalSaldo = $totalSaldo;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function export()
    {
        if (empty($this->laporansOnPage)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tabungan tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "laporan-transaksi-tabungan-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportRekapTabunganSiswa(
            $this->laporansOnPage,
            $this->totalKredit,
            $this->totalDebit,
            $this->totalSaldo
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.laporan-tabungan-siswa.export');
    }
}
