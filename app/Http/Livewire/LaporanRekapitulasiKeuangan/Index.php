<?php

namespace App\Http\Livewire\LaporanRekapitulasiKeuangan;

use App\Models\JenisTagihanSiswa;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $jenisTagihan;
    public $jenisRekapitulasi = 'tagihan';
    public $total;
    public $grandTotal;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKelas = [];
    public $selectedKategoriTagihanSiswa = [];
    public $selectedJenisTagihanSiswa = [];

    public $search = '';

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'applyFilters' => 'applyFilters',
        'clearFilters' => 'clearFilters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function applyFilters($filters)
    {
        // Simpan filter yang diterima
        $this->selectedKelas = $filters['selectedKelas'] ?? [];
        $this->selectedKategoriTagihanSiswa = $filters['selectedKategoriTagihanSiswa'] ?? [];
        $this->selectedJenisTagihanSiswa = $filters['selectedJenisTagihanSiswa'] ?? [];
    }

    public function clearFilters()
    {
        $this->selectedKelas = [];
        $this->selectedKategoriTagihanSiswa = [];
        $this->selectedJenisTagihanSiswa = [];
    }

    public function updatedJenisRekapitulasi()
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function render()
    {
        $this->jenisTagihan = JenisTagihanSiswa::with('ms_kategori_tagihan_siswa')
            ->whereHas('ms_kategori_tagihan_siswa', function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang)
                    ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);
            })
            ->when(!empty($this->selectedKategoriTagihanSiswa), function ($q) {
                $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihanSiswa);
            })
            ->when(!empty($this->selectedJenisTagihanSiswa), function ($q) {
                $q->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihanSiswa);
            })
            ->orderBy('ms_kategori_tagihan_siswa_id')
            ->get();


        // QUERY SISWA
        $query = PenempatanSiswa::with([
            'ms_siswa.ms_educard',
            'ms_kelas',
            'ms_tagihan_siswa' => function ($q) {
                $q->with([
                    'ms_jenis_tagihan_siswa'
                ])
                    ->withSum('dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar', 'jumlah_bayar');
            }
        ])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

        if (!empty($this->selectedKelas)) {
            $query->whereIn('ms_kelas_id', $this->selectedKelas);
        }

        if (!empty($this->selectedKategoriTagihanSiswa)) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihanSiswa);
            });
        }

        if (!empty($this->selectedJenisTagihanSiswa)) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihanSiswa);
            });
        }

        if ($this->search) {
            $query->whereHas('ms_siswa', function ($q) {
                $q->where('nama_siswa', 'like', "%{$this->search}%")
                    ->orWhereHas('ms_educard', function ($qr) {
                        $qr->where('kode_kartu', 'like', "%{$this->search}%");
                    });
            });
        }

        $siswas = $query->orderBy('ms_kelas_id')->orderBy(
            fn($q) => $q->select('nama_siswa')
                ->from('ms_siswa')
                ->whereColumn('ms_siswa.ms_siswa_id', 'ms_penempatan_siswa.ms_siswa_id')
        )->get();

        // ===============================
        //   REKAP PER JENIS TAGIHAN
        // ===============================
        $this->total = [];
        $this->grandTotal = 0;

        foreach ($this->jenisTagihan as $jenis) {
            $jenisId = $jenis->ms_jenis_tagihan_siswa_id;
            $sum = 0;

            foreach ($siswas as $siswa) {

                $tagihan = $siswa->ms_tagihan_siswa
                    ->firstWhere('ms_jenis_tagihan_siswa_id', $jenisId);

                if (!$tagihan) continue;

                $dibayar = $tagihan->jumlah_sudah_dibayar ?? 0;
                $tagihanNominal = $tagihan->jumlah_tagihan_siswa;
                $kekurangan = $tagihanNominal - $dibayar;

                if ($this->jenisRekapitulasi === 'tagihan') {
                    $sum += $tagihanNominal;
                } elseif ($this->jenisRekapitulasi === 'pembayaran') {
                    $sum += $dibayar;
                } else {
                    $sum += $kekurangan;
                }
            }

            $this->total[$jenisId] = $sum;
            $this->grandTotal += $sum;
        }
        
        return view('livewire.laporan-rekapitulasi-keuangan.index', [
            // 'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
