<?php

namespace App\Http\Livewire\Parameter;

use App\Models\Kelas;
use App\Models\TahunAjar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FilterTabungan extends Component
{
    public $startDate = null;
    public $endDate = null;
    public $selectedPetugas = [];
    public $selectedJenjang = [];
    public $selectedJenisTransaksi = [];

    // Listener untuk Livewire
    protected $listeners = [
        'applyFilters' => 'applyFilters',
        'clearFilters' => 'clearFilters',
    ];

    public function applyFilters($filters)
    {
        $this->startDate = $filters['startDate'] ?? null;
        $this->endDate = $filters['endDate'] ?? null;

        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
        $this->selectedJenisTransaksi = $filters['selectedJenisTransaksi'] ?? [];
    }

    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;

        $this->selectedJenisTransaksi = [];
        $this->selectedPetugas = [];
    }

    public function render()
    {
        // Ambil daftar petugas
        $select_petugas = User::get();

        return view('livewire.parameter.filter-tabungan', [
            'select_petugas' => $select_petugas,
        ]);
    }
}
