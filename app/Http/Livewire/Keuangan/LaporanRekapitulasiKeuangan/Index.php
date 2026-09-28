<?php

namespace App\Http\Livewire\Keuangan\LaporanRekapitulasiKeuangan;

use App\Models\JenisTagihanSiswa;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $perPage = 50;

    public $jenisTagihan;
    public $jenisRekapitulasi = 'tagihan';
    // public $total;
    // public $grandTotal;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKelas = [];
    public $selectedKategoriTagihanSiswa = [];
    public $selectedJenisTagihanSiswa = [];

    public $search = '';

    // Per halaman
    public $pageTotal = [];
    public $pageGrandTotal = 0;

    // Seluruh data
    public $overallTotal = [];
    public $overallGrandTotal = 0;

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

        $this->resetPage(); // Reset halaman ke 1 saat filter diterapkan
    }

    public function clearFilters()
    {
        $this->selectedKelas = [];
        $this->selectedKategoriTagihanSiswa = [];
        $this->selectedJenisTagihanSiswa = [];

        $this->resetPage(); // Reset halaman ke 1 saat filter diterapkan
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
        $baseQuery = PenempatanSiswa::with([
                'ms_siswa.ms_educard',
                'ms_kelas',
                'ms_tagihan_siswa' => function ($q) {
                    $q->with('ms_jenis_tagihan_siswa')
                        ->withSum([
                            'dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar' => function ($q) {
                                $q->where(function ($q) {
                                    $q->whereNull('status_transaksi')
                                    ->orWhere('status_transaksi', '!=', 'dibatalkan');
                                });
                            }
                        ], 'jumlah_bayar')
                        ->when(!empty($this->selectedKategoriTagihanSiswa), function ($q) {
                            $q->whereHas('ms_jenis_tagihan_siswa', function ($q) {
                                $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihanSiswa);
                            });
                        })
                        ->when(!empty($this->selectedJenisTagihanSiswa), function ($q) {
                            $q->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihanSiswa);
                        });
                }
            ])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

        if (!empty($this->selectedKelas)) {
            $baseQuery->whereIn('ms_kelas_id', $this->selectedKelas);
        }

        if (!empty($this->selectedKategoriTagihanSiswa)) {
            $baseQuery->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn(
                    'ms_kategori_tagihan_siswa_id',
                    $this->selectedKategoriTagihanSiswa
                );
            });
        }

        if (!empty($this->selectedJenisTagihanSiswa)) {
            $baseQuery->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn(
                    'ms_jenis_tagihan_siswa_id',
                    $this->selectedJenisTagihanSiswa
                );
            });
        }

        if ($this->search) {
            $baseQuery->whereHas('ms_siswa', function ($q) {
                $q->where('nama_siswa', 'like', "%{$this->search}%")
                    ->orWhereHas('ms_educard', function ($qr) {
                        $qr->where('kode_kartu', 'like', "%{$this->search}%");
                    });
            });
        }

        // Sorting cukup sekali
        $baseQuery
            ->orderBy('ms_kelas_id')
            ->orderBy(
                fn($q) => $q->select('nama_siswa')
                    ->from('ms_siswa')
                    ->whereColumn(
                        'ms_siswa.ms_siswa_id',
                        'ms_penempatan_siswa.ms_siswa_id'
                    )
            );

        $rekapQuery = clone $baseQuery;
        $listQuery  = clone $baseQuery;

        // ===============================
        // GRAND TOTAL (SEMUA DATA)
        // ===============================
        $rekapSiswas = $rekapQuery->get();

        $this->overallTotal = [];
        $this->overallGrandTotal = 0;

        foreach ($this->jenisTagihan as $jenis) {

            $sum = 0;

            foreach ($rekapSiswas as $siswa) {

                $tagihan = $siswa->ms_tagihan_siswa
                    ->firstWhere(
                        'ms_jenis_tagihan_siswa_id',
                        $jenis->ms_jenis_tagihan_siswa_id
                    );

                if (!$tagihan) {
                    continue;
                }

                $dibayar = $tagihan->jumlah_sudah_dibayar ?? 0;
                $nominal = $tagihan->jumlah_tagihan_siswa;
                $kekurangan = $nominal - $dibayar;

                switch ($this->jenisRekapitulasi) {
                    case 'tagihan':
                        $sum += $nominal;
                        break;

                    case 'pembayaran':
                        $sum += $dibayar;
                        break;

                    default:
                        $sum += $kekurangan;
                }
            }

            $this->overallTotal[$jenis->ms_jenis_tagihan_siswa_id] = $sum;
            $this->overallGrandTotal += $sum;
        }

        // ===============================
        // DATA TABEL (PER PAGE)
        // ===============================
        $siswas = $listQuery->paginate($this->perPage);

        $this->pageTotal = [];
        $this->pageGrandTotal = 0;

        foreach ($this->jenisTagihan as $jenis) {

            $sum = 0;

            foreach ($siswas as $siswa) {

                $tagihan = $siswa->ms_tagihan_siswa
                    ->firstWhere(
                        'ms_jenis_tagihan_siswa_id',
                        $jenis->ms_jenis_tagihan_siswa_id
                    );

                if (!$tagihan) {
                    continue;
                }

                $dibayar = $tagihan->jumlah_sudah_dibayar ?? 0;
                $nominal = $tagihan->jumlah_tagihan_siswa;
                $kekurangan = $nominal - $dibayar;

                switch ($this->jenisRekapitulasi) {
                    case 'tagihan':
                        $sum += $nominal;
                        break;

                    case 'pembayaran':
                        $sum += $dibayar;
                        break;

                    default:
                        $sum += $kekurangan;
                }
            }

            $this->pageTotal[$jenis->ms_jenis_tagihan_siswa_id] = $sum;
            $this->pageGrandTotal += $sum;
        }

        return view('livewire.keuangan.laporan-rekapitulasi-keuangan.index',
            compact('siswas')
        );
    }
}
