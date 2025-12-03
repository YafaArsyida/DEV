<?php

namespace App\Http\Livewire\SmartCanteen\SettlementTransaksi;

use App\Models\SettlementSmartCanteen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RiwayatSettlement extends Component
{
    public $startDate;
    public $endDate;
    public $selectedPetugas = null;       // filter petugas kantin
    public $listPetugas = [];             // data dropdown

    public function mount()
    {
        $user = Auth::user();

        // $this->startDate = now()->format('Y-m-d');
        // Default periode: awal bulan sampai hari ini
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');

        // Jika peran kantin → otomatis set
        if ($user->peran === 'kantin') {
            $this->selectedPetugas = $user->ms_pengguna_id;
        } else {
            // Admin TU → load semua petugas kantin
            $this->listPetugas = User::where('peran', 'kantin')->get();
        }
    }
    public function updatedSelectedPetugas()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Filter petugas diperbarui'
        ]);
    }

    // Query utama riwayat settlement
    public function getDataProperty()
    {
        $user = Auth::user();

        $query = SettlementSmartCanteen::query()
            ->orderBy('tanggal_settlement', 'desc');

        // Jika role kantin → otomatis filter
        if ($user->peran === 'kantin') {

            $query->where('ms_pengguna_kantin_id', $user->ms_pengguna_id);
        } else {
            // Untuk admin → filter berdasarkan dropdown petugas (opsional)
            if (!empty($this->selectedPetugas)) {
                $query->where('ms_pengguna_kantin_id', $this->selectedPetugas);
            }
        }

        // Filter tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_settlement', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.smart-canteen.settlement-transaksi.riwayat-settlement', [
            'riwayat' => $this->getDataProperty()
        ]);
    }
}
