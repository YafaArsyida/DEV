<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\AkuntansiJurnalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class KartuJurnalKantin extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $periode = 'hari_ini'; // default

    public $selectedKantin = null;
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

    public function updateParameters($kantin, $tahunAjar)
    {
        // Update nilai selectedKantin dan selectedTahunAjar
        $this->selectedKantin = $kantin;
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

        $user = Auth::user();

        $jurnal = AkuntansiJurnalDetail::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)

            ->when($user->peran !== 'SUPERADMIN', function ($query) use ($user) {
                $query->where('ms_pengguna_id', $user->ms_pengguna_id);
            })
            
            ->where('kode_rekening', '21001.01')
            ->whereBetween('tanggal_transaksi', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ])
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10);

        // grouping SETELAH paginate
        $transaksiJurnal = $jurnal->getCollection()
            ->groupBy(['deskripsi', 'nominal']);

        return view('livewire.smart-canteen.widget.kartu-jurnal-kantin',[
            'transaksiJurnal' => $transaksiJurnal,
            'jurnalPagination' => $jurnal,
        ]);
    }
}
