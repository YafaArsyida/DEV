<?php

namespace App\Http\Livewire\AkuntansiLaporanPengeluaran;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedBulan = null;
    public $startDate = null;
    public $endDate = null;

    public $search = '';

    public $namaJenjang = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function updateBulan($bulan)
    {
        $this->selectedBulan = $bulan;
    }

    public function resetTanggal()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-pengeluaran.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $bebanPerBulan = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('posisi', 'debit') // beban = debit
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
            })
            ->whereHas('akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '5%'); // kode beban
            })
            ->get()
            ->groupBy([
                fn($item) => $item->akuntansi_rekening->nama_rekening,
                fn($item) => \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m') // per bulan
            ]);

        // Ambil semua header bulan unik
        $bulanHeaders = collect($bebanPerBulan)->flatMap(function ($item) {
            return collect($item)->keys()->all();
        })->unique()->sort()->values();

        // Mapping header ke dalam bahasa Indonesia
        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {
            return [$bulan => HelperController::formatTanggalIndonesia($bulan . '-01', 'F Y')];
        });
        return view('livewire.akuntansi-laporan-pengeluaran.index', [
            'bebanPerBulan' => $bebanPerBulan,
            'bulanIndo' => $bulanIndo,
        ]);
    }
}
