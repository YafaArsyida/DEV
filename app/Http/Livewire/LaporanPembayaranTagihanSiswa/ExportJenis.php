<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use App\Exports\ExportPembayaranJenis;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportJenis extends Component
{
    protected $listeners = ['prepareExportJenis'];

    public $laporansOnPage = [];
    public $totalsOnPage = [];

    public function prepareExportJenis($laporans, $totals)
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
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak ditemukan.']);
            return;
        }

        $currentDate = date('Ymd');
        $fileName = "laporan-jenis-{$currentDate}.xlsx";

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);

        // Kirim data ke class Export untuk pengolahan Excel
        return Excel::download(new ExportPembayaranJenis($this->laporansOnPage, $this->totalsOnPage), $fileName);
    }
    public function render()
    {
        return view('livewire.laporan-pembayaran-tagihan-siswa.export-jenis');
    }
}
