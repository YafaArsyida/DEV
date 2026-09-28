<?php

namespace App\Http\Livewire\Keuangan\Parameter;

use Livewire\Component;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\Kelas;
use App\Models\User;

class FilterLaporanPembayaran extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKelas = [];
    public $selectedKategoriTagihanSiswa = [];
    public $showJenisTagihan = false;
    public $selectedJenisTagihanSiswa = [];
    public $selectedMetode = [];

    public $select_petugas = [];
    public $selectedPetugas = [];

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

    public function applyFilters($filters)
    {
        $this->selectedKelas = $filters['selectedKelas'] ?? [];
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
        $this->selectedKategoriTagihanSiswa = $filters['selectedKategoriTagihanSiswa'] ?? [];

        // Tampilkan filter jenis tagihan jika kategori tidak kosong
        $this->showJenisTagihan = !empty($this->selectedKategoriTagihanSiswa);

        $this->selectedJenisTagihanSiswa = $filters['selectedJenisTagihanSiswa'] ?? [];
        $this->selectedMetode = $filters['selectedMetode'] ?? [];

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function clearFilters()
    {
        $this->selectedKelas = [];
        $this->selectedPetugas = [];
        $this->selectedKategoriTagihanSiswa = [];
        $this->selectedJenisTagihanSiswa = [];
        $this->selectedMetode = [];

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


        return view('livewire.keuangan.parameter.filter-laporan-pembayaran', [
            'select_petugas' => $this->select_petugas,
            'select_kelas' => $select_kelas,
            'select_kategori_tagihan' => $select_kategori_tagihan,
            'select_jenis_tagihan' => $select_jenis_tagihan,
        ]);
    }
}
