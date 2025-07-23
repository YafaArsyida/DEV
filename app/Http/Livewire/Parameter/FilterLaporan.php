<?php

namespace App\Http\Livewire\Parameter;

use App\Models\JenisTagihan;
use App\Models\Kelas;
use App\Models\User;
use Livewire\Component;

class FilterLaporan extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $startDate = null;
    public $endDate = null;

    public $selectedKelas = [];
    public $selectedPetugas = [];
    public $selectedJenisTagihan = [];
    public $selectedMetode = [];

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
    }

    public function applyFilters($filters)
    {
        $this->startDate = $filters['startDate'] ?? null;
        $this->endDate = $filters['endDate'] ?? null;

        $this->selectedKelas = $filters['selectedKelas'] ?? [];
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
        $this->selectedJenisTagihan = $filters['selectedJenisTagihan'] ?? [];
        $this->selectedMetode = $filters['selectedMetode'] ?? [];
    }

    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;

        $this->selectedKelas = [];
        $this->selectedPetugas = [];
        $this->selectedJenisTagihan = [];
        $this->selectedMetode = [];
    }


    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $select_jenis_tagihan = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_jenis_tagihan = JenisTagihan::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $select_petugas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_petugas = User::get();
        }

        return view('livewire.parameter.filter-laporan', [
            'select_petugas' => $select_petugas,
            'select_kelas' => $select_kelas,
            'select_jenis_tagihan' => $select_jenis_tagihan,
        ]);
    }
}
