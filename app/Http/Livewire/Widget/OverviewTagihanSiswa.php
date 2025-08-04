<?php

namespace App\Http\Livewire\Widget;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Livewire\Component;
use Livewire\WithPagination;

class OverviewTagihanSiswa extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $select_kategori = [];
    public $selectedKategoriTagihan = null; // Filter kategori tagihan

    protected $listeners = ['parameterUpdated' => 'updateParameters'];

    public function updatedSelectedKategoriTagihan()
    {
        $this->resetPage(); // reset halaman jika filter berubah
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->select_kategori = KategoriTagihanSiswa::where('ms_jenjang_id', $jenjang)
            ->where('ms_tahun_ajar_id', $tahunAjar)
            ->get();

        $this->resetPage();
    }

    public function render()
    {
        $query = JenisTagihanSiswa::with('ms_kategori_tagihan_siswa')
            ->where('ms_jenis_tagihan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_jenis_tagihan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar);

        if ($this->selectedKategoriTagihan) {
            $query->where('ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
        }

        $laporans = $query->orderBy('ms_kategori_tagihan_siswa_id')
            ->orderBy('ms_kategori_tagihan_siswa_id')
            ->paginate(10);
        return view('livewire.widget.overview-tagihan-siswa', [
            'laporans' => $laporans,
            'select_kategori' => $this->select_kategori,
        ]);
    }
}
