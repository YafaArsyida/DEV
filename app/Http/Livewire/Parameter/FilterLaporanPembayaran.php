<?php

namespace App\Http\Livewire\Parameter;

use Livewire\Component;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\Kelas;
use App\Models\User;

class FilterLaporanPembayaran extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $startDate = null;
    public $endDate = null;

    public $selectedKelas = [];
    public $selectedPetugas = [];
    public $selectedKategoriTagihanSiswa = [];
    public $showJenisTagihan = false;
    public $selectedJenisTagihanSiswa = [];
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
        $this->selectedKategoriTagihanSiswa = $filters['selectedKategoriTagihanSiswa'] ?? [];

        // Tampilkan filter jenis tagihan jika kategori tidak kosong
        $this->showJenisTagihan = !empty($this->selectedKategoriTagihanSiswa);

        $this->selectedJenisTagihanSiswa = $filters['selectedJenisTagihanSiswa'] ?? [];
        $this->selectedMetode = $filters['selectedMetode'] ?? [];
    }

    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;

        $this->selectedKelas = [];
        $this->selectedPetugas = [];
        $this->selectedKategoriTagihanSiswa = [];
        $this->selectedJenisTagihanSiswa = [];
        $this->selectedMetode = [];

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

        $select_petugas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_petugas = User::get();
        }

        return view('livewire.parameter.filter-laporan-pembayaran', [
            'select_petugas' => $select_petugas,
            'select_kelas' => $select_kelas,
            'select_kategori_tagihan' => $select_kategori_tagihan,
            'select_jenis_tagihan' => $select_jenis_tagihan,
        ]);
    }
}
