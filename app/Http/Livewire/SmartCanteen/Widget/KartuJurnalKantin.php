<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\AkuntansiJurnalDetail;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class KartuJurnalKantin extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $periode = 'hari_ini'; // default

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshJurnalHariIni'
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function refreshJurnalHariIni()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    protected function getTanggalFilter()
    {
        return match ($this->periode) {
            'hari_ini'  => [Carbon::today(), Carbon::today()],
            'kemarin'   => [Carbon::yesterday(), Carbon::yesterday()],
            'bulan_ini' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            default     => [Carbon::today(), Carbon::today()],
        };
    }

    public function updatedPeriode()
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->resetPage();
    }

    public function render()
    {
        [$startDate, $endDate] = $this->getTanggalFilter();

        $jurnal = AkuntansiJurnalDetail::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('kode_rekening', '21001.01')
            ->whereBetween('tanggal_transaksi', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10); // ⬅️ pagination di sini

        // grouping SETELAH paginate
        $transaksiJurnal = $jurnal->getCollection()
            ->groupBy(['deskripsi', 'nominal']);

        return view('livewire.smart-canteen.widget.kartu-jurnal-kantin',[
            'transaksiJurnal' => $transaksiJurnal,
            'jurnalPagination' => $jurnal,
        ]);
    }
}
