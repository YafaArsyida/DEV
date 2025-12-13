<?php

namespace App\Http\Livewire\Parameter;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FilterLaporanEduPay extends Component
{
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
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
        $this->selectedJenisTransaksi = $filters['selectedJenisTransaksi'] ?? [];
    }
    public function clearFilters()
    {
        $this->selectedPetugas = [];
        $this->selectedJenisTransaksi = [];
    }

    public function render()
    {
        // Ambil daftar petugas
        $select_petugas = User::whereNotIn('peran', ['kantin', 'koperasi'])->get();

        return view('livewire.parameter.filter-laporan-edu-pay', [
            'select_petugas' => $select_petugas,
        ]);
    }
}
