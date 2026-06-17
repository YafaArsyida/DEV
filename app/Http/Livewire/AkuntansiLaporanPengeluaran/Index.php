<?php

namespace App\Http\Livewire\AkuntansiLaporanPengeluaran;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    // public $selectedTahunAjar = null;
    public $selectedBulan = null;
    public $startDate = null;
    public $endDate = null;

    public $search = '';

    public $namaJenjang = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        // $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function updateBulan($bulan)
    {
        $this->selectedBulan = $bulan;
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-pengeluaran.pdf', [
            'jenjang' => $this->selectedJenjang,
            // 'tahun' => $this->selectedTahunAjar,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $bebanPerBulan = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('posisi', 'debit') // beban = debit

            ->when($this->startDate && $this->endDate, fn($q) => $q->whereBetween('tanggal_transaksi', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]))

            ->whereHas('akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '5%'); // kode beban
            })
            ->get()
            ->groupBy([
                fn($item) => $item->akuntansi_rekening->nama_rekening,
                fn($item) => Carbon::parse($item->tanggal_transaksi)->format('Y-m') // per bulan
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
