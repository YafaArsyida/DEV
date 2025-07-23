<?php

namespace App\Http\Livewire\LaporanTagihanSiswa;

use App\Exports\ExportTagihanSiswa;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    protected $listeners = ['prepareExportTagihan'];

    public $laporansOnPage = [];
    public $totalsOnPage = [];

    public function prepareExportTagihan($laporans, $totals)
    {
        if (!isset($laporans) || !isset($totals)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }

        // Simpan data laporan dan total untuk diproses
        $this->laporansOnPage = $laporans;
        $this->totalsOnPage = $totals;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function export()
    {
        if (empty($this->laporansOnPage)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "laporan-tagihan-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);

        // Kirim data ke class Export untuk pengolahan Excel
        return Excel::download(new ExportTagihanSiswa($this->laporansOnPage, $this->totalsOnPage), $fileName);
    }

    
    public function render()
    {
        return view('livewire.laporan-tagihan-siswa.export');
    }
}
