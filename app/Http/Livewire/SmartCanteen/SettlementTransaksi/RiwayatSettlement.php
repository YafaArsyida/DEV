<?php

namespace App\Http\Livewire\SmartCanteen\SettlementTransaksi;

use App\Models\Jenjang;
use App\Models\SmartCanteen\SettlementSmartCanteen;
use App\Models\TahunAjar;
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
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '-';
    public $namaTahunAjar = '-';

    public $selectedPetugas = null;       // filter petugas kantin
    public $select_petugas = [];

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshSettlement'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->loadPetugasByJenjang();

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

    protected function loadPetugasByJenjang()
    {
        $user = auth()->user();

        // Jika jenjang belum dipilih → kosongkan
        if (!$this->selectedJenjang) {
            $this->select_petugas = collect();
            return;
        }

        // Jika login sebagai kantin → auto set, tidak perlu list
        if ($user->peran === 'kantin') {
            $this->selectedPetugas = $user->ms_pengguna_id;
            $this->select_petugas = collect();
            return;
        }

        // Admin / TU → load petugas kantin sesuai akses jenjang
        $this->select_petugas = User::where('peran', 'kantin')
            ->whereHas('ms_akses_jenjang', function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang);
            })
            ->orderBy('nama')
            ->get();
    }

    public function resetTanggal()
    {
        // $this->startDate = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->resetPage();
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
            ->where('ms_jenjang_id', $this->selectedJenjang)
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

        // return $query->get();
    return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.smart-canteen.settlement-transaksi.riwayat-settlement', [
            'riwayat' => $this->getDataProperty()
        ]);
    }
}
