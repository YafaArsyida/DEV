<?php

namespace App\Http\Livewire\Parameter;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FilterLaporanEduPay extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $select_petugas = [];
    public $selectedPetugas = [];

    public $selectedJenisTransaksi = [];

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'applyFilters' => 'applyFilters',
        'clearFilters' => 'clearFilters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->loadPetugasByJenjang();
    }

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

    protected function loadPetugasByJenjang()
    {
        $user = auth()->user();

        // Kantin tidak perlu select petugas
        if ($user->peran === 'kantin') {
            return;
        }

        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->select_petugas = collect();
            return;
        }

        $this->select_petugas = User::whereHas('ms_akses_jenjang', function ($q) {
            $q->where('ms_jenjang_id', $this->selectedJenjang);
        })
            ->whereNotIn('peran', ['kantin', 'koperasi'])
            ->orderBy('nama')
            ->get();
    }

    public function render()
    {
        return view('livewire.parameter.filter-laporan-edu-pay', [
            'select_petugas' => $this->select_petugas,
        ]);
    }
}
