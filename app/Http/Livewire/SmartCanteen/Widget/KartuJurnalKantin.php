<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class KartuJurnalKantin extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $perPage = 10;

    public $periode = 'hari_ini'; // default

    public $selectedKantin = null;

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

    public function updateParameters($kantin)
    {
        // Update nilai selectedKantin
        $this->selectedKantin = $kantin;
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

        $query = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
            'ms_pengguna',
        ])
            ->where('ms_departemen_id', 'KANTIN')
            ->whereBetween('tanggal_transaksi', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ]);

        if ($user->peran !== 'SUPERADMIN') {
            $query->where(
                'ms_pengguna_id',
                $user->ms_pengguna_id
            );
        }

        $transaksiJurnal = $query
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('akuntansi_jurnal_id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.smart-canteen.widget.kartu-jurnal-kantin',
            [
                'transaksiJurnal' => $transaksiJurnal,
            ]
        );
    }
}
