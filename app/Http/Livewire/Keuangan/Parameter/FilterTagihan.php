<?php

namespace App\Http\Livewire\Keuangan\Parameter;

use App\Models\JenisTagihan;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihan;
use App\Models\KategoriTagihanSiswa;
use App\Models\Kelas;
use Carbon\Carbon;
use Livewire\Component;

class FilterTagihan extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $endDate = null;

    public $selectedKelas = [];
    public $selectedKategoriTagihan = [];
    public $showJenisTagihan = false;
    public $selectedJenisTagihan = [];

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
        // $this->endDate = $filters['endDate'] ?? null;

        // $this->selectedKelas = $filters['selectedKelas'] ?? [];
        $this->selectedKategoriTagihan = $filters['selectedKategoriTagihan'] ?? [];

        // Tampilkan filter jenis tagihan jika kategori tidak kosong
        $this->showJenisTagihan = !empty($this->selectedKategoriTagihan);
        $this->selectedJenisTagihan = $filters['selectedJenisTagihan'] ?? [];
    }

    // public function mount()
    // {
    //     $this->endDate = now()->endOfMonth()->format('Y-m-d');
    // }

    public function clearFilters()
    {
        // $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->selectedKategoriTagihan = [];
        $this->selectedJenisTagihan = [];
        $this->showJenisTagihan = false;
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

        $select_kategori_tagihan = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kategori_tagihan = KategoriTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $select_jenis_tagihan = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar && $this->selectedKategoriTagihan) {
            $select_jenis_tagihan = JenisTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan)
                ->get();
        }

        return view('livewire.keuangan.parameter.filter-tagihan', [
            'select_kelas' => $select_kelas,
            'select_kategori_tagihan' => $select_kategori_tagihan,
            'select_jenis_tagihan' => $select_jenis_tagihan,
        ]);
    }
}
