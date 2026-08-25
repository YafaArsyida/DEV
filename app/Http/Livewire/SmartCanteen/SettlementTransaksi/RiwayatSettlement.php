<?php

namespace App\Http\Livewire\SmartCanteen\SettlementTransaksi;

use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\SettlementSmartCanteen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RiwayatSettlement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $startDate;
    public $endDate;

    // Parameter dari listener
    public $selectedKantin;
    public $selectedJenjang;

    public $namaKantin;

    public $namaJenjang = '-';

    public $selectedPetugas = null;       // filter petugas kantin
    public $select_petugas = [];

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshSettlement'
    ];

    public function updateParameters($kantin)
    {
        $this->selectedKantin = $kantin;

        $this->namaKantin = Kantin::find($kantin)?->nama_kantin ?? '-';

        $this->resetPage();
    }
    
    public function refreshSettlement()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }
    public function mount()
    {
        // Default tanggal
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function resetTanggal()
    {
        // $this->startDate = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->resetPage();
    }

    // Query utama riwayat settlement
    public function getDataProperty()
    {
        $query = SettlementSmartCanteen::query()
            ->where('ms_kantin_id', $this->selectedKantin)
            // ->where('ms_jenjang_id', $this->selectedJenjang)
            ->orderBy('tanggal_settlement', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_settlement', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        return $query->paginate(50);
    }

    public function render()
    {
        return view('livewire.smart-canteen.settlement-transaksi.riwayat-settlement', [
            'riwayat' => $this->getDataProperty()
        ]);
    }
}
