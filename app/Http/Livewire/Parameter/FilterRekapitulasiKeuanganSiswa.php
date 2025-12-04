<?php

namespace App\Http\Livewire\Parameter;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\Kelas;
use Livewire\Component;

class FilterRekapitulasiKeuanganSiswa extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKelas = [];
    public $selectedKategoriTagihanSiswa = [];
    public $showJenisTagihan = false;
    public $selectedJenisTagihanSiswa = [];

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
        $this->selectedKelas = $filters['selectedKelas'] ?? [];
        $this->selectedKategoriTagihanSiswa = $filters['selectedKategoriTagihanSiswa'] ?? [];

        // Tampilkan filter jenis tagihan jika kategori tidak kosong
        $this->showJenisTagihan = !empty($this->selectedKategoriTagihanSiswa);

        $this->selectedJenisTagihanSiswa = $filters['selectedJenisTagihanSiswa'] ?? [];

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function clearFilters()
    {
        $this->selectedKelas = [];
        $this->selectedKategoriTagihanSiswa = [];
        $this->selectedJenisTagihanSiswa = [];

        $this->showJenisTagihan = false;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
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
        if ($this->selectedKategoriTagihanSiswa && $this->selectedJenjang && $this->selectedTahunAjar) {
            $query = JenisTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            if (!empty($this->selectedKategoriTagihanSiswa)) {
                $query->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihanSiswa);
            }

            // Tambahkan orderBy untuk mengurutkan hasil
            $select_jenis_tagihan = $query->orderBy('ms_kategori_tagihan_siswa_id') // Urut berdasarkan kategori
                // ->orderBy('nama_jenis_tagihan_siswa')   // Urut berdasarkan nama jenis tagihan
                ->get();
        }
        return view('livewire.parameter.filter-rekapitulasi-keuangan-siswa', [
            'select_kelas' => $select_kelas,
            'select_kategori_tagihan' => $select_kategori_tagihan,
            'select_jenis_tagihan' => $select_jenis_tagihan,
        ]);
    }
}
