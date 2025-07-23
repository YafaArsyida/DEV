<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Exports\ExportOverviewTabungan;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportOverview extends Component
{
    protected $listeners = ['ExportOverviewTabungan'];

    public $laporans = [];

    public function ExportOverviewTabungan($laporans)
    {
        if (empty($laporans)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }

        // Assign the received data to the component's properties
        $this->laporans = [
            'totalKredit' => $laporans['total_kredit'] ?? 0,
            'totalDebit' => $laporans['total_debit'] ?? 0,
            'totalSaldo' => $laporans['total_saldo'] ?? 0,
        ];

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function export()
    {
        if (empty($this->laporans)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tabungan tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "overview-tabungan-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportOverviewTabungan(
            $this->laporans['totalKredit'],
            $this->laporans['totalDebit'],
            $this->laporans['totalSaldo']
        ), $fileName);
    }

    public function render()
    {
        return view('livewire.laporan-tabungan-siswa.export-overview');
    }
}
