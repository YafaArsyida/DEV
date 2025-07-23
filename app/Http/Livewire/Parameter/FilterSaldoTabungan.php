<?php

namespace App\Http\Livewire\Parameter;

use App\Models\Kelas;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FilterSaldoTabungan extends Component
{
    public $selectedJenjang = [];
    public $selectedTahunAjaran = [];
    public $selectedKelas = [];

    // Listener untuk Livewire
    protected $listeners = [
        'applyFilters' => 'applyFilters',
        'clearFilters' => 'clearFilters',
    ];

    public function applyFilters($filters)
    {
        $this->selectedJenjang = $filters['selectedJenjang'] ?? [];
        $this->selectedTahunAjaran = $filters['selectedTahunAjaran'] ?? [];
        $this->selectedKelas = $filters['selectedKelas'] ?? [];
    }

    public function clearFilters()
    {
        $this->selectedJenjang = [];
        $this->selectedTahunAjaran = [];
        $this->selectedKelas = [];
    }


    public function render()
    {
        // Ambil ID jenjang dari akses petugas yang login
        $select_jenjang = DB::table('ms_akses_jenjang')
            ->join('ms_jenjang', 'ms_akses_jenjang.ms_jenjang_id', '=', 'ms_jenjang.ms_jenjang_id')
            ->where('ms_akses_jenjang.ms_pengguna_id', Auth::id())
            ->select('ms_jenjang.ms_jenjang_id', 'ms_jenjang.nama_jenjang')
            ->get();

        $select_tahun_ajar = TahunAjar::get();

        // Memfilter kelas berdasarkan jenjang dan tahun ajar jika dipilih
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }
        return view('livewire.parameter.filter-saldo-tabungan', [
            'select_jenjang' => $select_jenjang,
            'select_tahun_ajar' => $select_tahun_ajar,
            'select_kelas' => $select_kelas,
        ]);
    }
}
