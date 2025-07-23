<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use App\Exports\ExportPembayaranTagihanSiswa;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    protected $listeners = ['prepareExport'];

    public $laporansOnPage = [];
    public $totalsOnPage = [];

    public function prepareExport($laporans, $totals)
    {
        if (!isset($laporans) || !isset($totals)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }

        // Simpan data laporan dan total untuk diproses
        $this->laporansOnPage = $laporans;
        $this->totalsOnPage = $totals;

        // Menampilkan notifikasi bahwa dokumen siap diexport
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function export()
    {
        if (empty($this->laporansOnPage)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tabungan tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "laporan-pembayaran-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportPembayaranTagihanSiswa($this->laporansOnPage, $this->totalsOnPage), $fileName);
    }

    public function render()
    {
        return view('livewire.laporan-pembayaran-tagihan-siswa.export');
    }
}
