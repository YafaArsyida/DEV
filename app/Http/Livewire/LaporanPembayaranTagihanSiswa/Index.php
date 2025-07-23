<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use App\Models\DetailTransaksi;
use App\Models\DetailTransaksiTagihanSiswa;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $startDate = null;
    public $endDate = null;

    public $selectedKelas = [];
    public $selectedPetugas = [];
    public $selectedKategoriTagihanSiswa = [];
    public $selectedJenisTagihanSiswa = [];
    public $selectedMetode = [];

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'applyFilters' => 'applyFilters',
        'clearFilters' => 'clearFilters',
    ];

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->resetPage(); // Reset paginasi saat parameter berubah
    }

    public function applyFilters($filters)
    {
        // Simpan filter yang diterima
        $this->startDate = $filters['startDate'] ?? null;
        $this->endDate = $filters['endDate'] ?? null;
        $this->selectedKelas = $filters['selectedKelas'] ?? [];
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
        $this->selectedKategoriTagihanSiswa = $filters['selectedKategoriTagihanSiswa'] ?? [];
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
    }

    public function showExportPembayaranSiswa()
    {
        $laporans = collect([]);
        $totals = [
            'totalPembayaran' => 0,
        ];
        $query = DetailTransaksiTagihanSiswa::with([
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa', // Relasi siswa
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas', // Relasi kelas
            'ms_transaksi_tagihan_siswa.ms_pengguna', // Relasi pengguna
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa', // Relasi kategori tagihan
        ])
            ->join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
            ->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa', function ($q) {
                $q->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                    ->where('ms_jenjang_id', $this->selectedJenjang);
            });
        // Filter kelas
        if (!empty($this->selectedKelas)) {
            $query->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas', function ($q) {
                $q->whereIn('ms_kelas_id', $this->selectedKelas);
            });
        }

        // Filter tanggal transaksi
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            });
        }

        // Filter petugas
        if (!empty($this->selectedPetugas)) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) {
                $q->whereIn('ms_pengguna_id', $this->selectedPetugas);
            });
        }

        // Filter kategori tagihan
        if (!empty($this->selectedKategoriTagihanSiswa)) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihanSiswa);
            });
        }

        // Filter jenis tagihan
        if (!empty($this->selectedJenisTagihanSiswa)) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihanSiswa);
            });
        }

        // Filter metode pembayaran
        if (!empty($this->selectedMetode)) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) {
                $q->whereIn('metode_pembayaran', $this->selectedMetode);
            });
        }

        // Ambil data dan map ke array sederhana
        $laporans = $query->orderBy('ms_transaksi_tagihan_siswa.tanggal_transaksi', 'ASC')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal_transaksi' => $item->ms_transaksi_tagihan_siswa->tanggal_transaksi,
                    'nama_siswa' => $item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_siswa->nama_siswa,
                    'kelas' => $item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_kelas->nama_kelas,
                    'jenis_tagihan' => $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan,
                    'kategori_tagihan' => $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan,
                    'jumlah_bayar' => $item->jumlah_bayar,
                    'petugas' => $item->ms_transaksi_tagihan_siswa->ms_pengguna->nama,
                    'metode_pembayaran' => $item->ms_transaksi_tagihan_siswa->metode_pembayaran,
                ];
            })
            ->toArray();

        // Hitung total pembayaran
        $totals['totalPembayaran'] = array_sum(array_column($laporans, 'jumlah_bayar'));

        // Emit event dengan data laporan dan total pembayaran
        $this->emit('prepareExport', $laporans, $totals);
    }

    public function cetakLaporanPembayaran()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'laporan diproses.']);

        $url = route('laporan.pembayaran-tagihan-siswa.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'kelas' => $this->selectedKelas,
            'kategori' => $this->selectedKategoriTagihanSiswa,
            'jenis' => $this->selectedJenisTagihanSiswa,
            'metode' => $this->selectedMetode,
            'petugas' => $this->selectedPetugas,
            'search' => $this->search,
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $query = DetailTransaksiTagihanSiswa::with([
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa', // Relasi siswa
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas', // Relasi kelas
            'ms_transaksi_tagihan_siswa.ms_pengguna', // Relasi pengguna
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa', // Relasi kategori tagihan
        ])
            ->join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
            ->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa', function ($q) {
                $q->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                    ->where('ms_jenjang_id', $this->selectedJenjang);
            });

        // Filter nama siswa
        if ($this->search) {
            $query->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa', function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%');
            });
        }

        // Filter kelas
        if (!empty($this->selectedKelas)) {
            $query->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas', function ($q) {
                $q->whereIn('ms_kelas_id', $this->selectedKelas);
            });
        }

        // Filter tanggal transaksi
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            });
        }

        // Filter petugas
        if (!empty($this->selectedPetugas)) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) {
                $q->whereIn('ms_pengguna_id', $this->selectedPetugas);
            });
        }

        // Filter kategori tagihan
        if (!empty($this->selectedKategoriTagihanSiswa)) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihanSiswa);
            });
        }

        // Filter jenis tagihan
        if (!empty($this->selectedJenisTagihanSiswa)) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihanSiswa);
            });
        }

        // Filter metode pembayaran
        if (!empty($this->selectedMetode)) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) {
                $q->whereIn('metode_pembayaran', $this->selectedMetode);
            });
        }

        // Sorting dan pagination
        $laporans = $query->orderBy('ms_transaksi_tagihan_siswa.tanggal_transaksi', 'ASC')
            ->paginate(1000);

        $totalPembayaran = $laporans->sum('jumlah_bayar');

        return view('livewire.laporan-pembayaran-tagihan-siswa.index', [
            'laporans' => $laporans,
            'totalPembayaran' => $totalPembayaran,
        ]);
    }
}
