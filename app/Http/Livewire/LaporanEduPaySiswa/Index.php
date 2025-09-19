<?php

namespace App\Http\Livewire\LaporanEduPaySiswa;

use App\Models\EduPay;
use App\Models\EduPaySiswa;
use App\Models\Kelas;
use App\Models\TransaksiEduPay;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    public $selectedPetugas = [];
    public $selectedJenisTransaksi = [];

    public $startDate = null;
    public $endDate = null;

    public $search = '';

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'applyFilters' => 'applyFilters',
        'clearFilters' => 'clearFilters',

        'refreshSaldoEduPay'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function refreshSaldoEduPay()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function applyFilters($filters)
    {
        // Simpan filter yang diterima
        $this->startDate = $filters['startDate'] ?? null;
        $this->endDate = $filters['endDate'] ?? null;
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
        $this->selectedJenisTransaksi = $filters['selectedJenisTransaksi'] ?? [];
    }

    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->selectedPetugas = [];
        $this->selectedJenisTransaksi = [];
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function showExportEduPay()
    {
        $query = TransaksiEduPay::query()
            ->with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_transaksi_edupay.user_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_transaksi_edupay.ms_penempatan_siswa_id')
            ->select('ms_transaksi_edupay.*', 'ms_siswa.nama_siswa', 'ms_penempatan_siswa.ms_jenjang_id', 'ms_penempatan_siswa.ms_tahun_ajar_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->selectedPetugas) {
            $query->where('ms_transaksi_edupay.ms_pengguna_id', $this->selectedPetugas);
        }

        // Filter berdasarkan rentang tanggal
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Mapping kategori
        $pemasukanJenis = ['topup tunai', 'topup online', 'pengembalian dana'];
        $pengeluaranJenis = ['penarikan', 'pembayaran', 'kantin'];

        // Filter berdasarkan pilihan
        if ($this->selectedJenisTransaksi) {
            $query->whereIn('jenis_transaksi', $this->selectedJenisTransaksi);
        }

        // Hitung total pemasukan
        $totalPemasukan = $query->clone()
            ->whereIn('jenis_transaksi', $pemasukanJenis)
            ->sum('nominal');

        // Hitung total pengeluaran
        $totalPengeluaran = $query->clone()
            ->whereIn('jenis_transaksi', $pengeluaranJenis)
            ->sum('nominal');

        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        $laporans = $query->get();

        // Emit data ke komponen lain untuk diexport
        $this->emit('prepareExportEduPay', $laporans->toArray(), $totalPemasukan, $totalPengeluaran, $totalSaldo);
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('laporan.edupay-siswa.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'kelas' => $this->selectedKelas,
            'jenis_transaksi' => $this->selectedJenisTransaksi,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $query = TransaksiEduPay::query()
            ->with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_transaksi_edupay.user_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_transaksi_edupay.ms_penempatan_siswa_id')
            ->select('ms_transaksi_edupay.*', 'ms_siswa.nama_siswa', 'ms_penempatan_siswa.ms_jenjang_id', 'ms_penempatan_siswa.ms_tahun_ajar_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->selectedPetugas) {
            $query->where('ms_transaksi_edupay.ms_pengguna_id', $this->selectedPetugas);
        }

        // Filter berdasarkan nama siswa jika ada
        if ($this->search) {
            $query->whereHas('ms_siswa', function ($q) {
                $q->where('nama_siswa', 'like', '%' . trim($this->search) . '%');
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Filter berdasarkan jenis transaksi (jika relevan)
        // Mapping kategori
        $pemasukanJenis = ['topup tunai', 'topup online', 'pengembalian dana'];
        $pengeluaranJenis = ['penarikan', 'pembayaran', 'kantin'];

        // Filter berdasarkan pilihan
        if ($this->selectedJenisTransaksi) {
            $query->whereIn('jenis_transaksi', $this->selectedJenisTransaksi);
        }

        // Hitung total pemasukan
        $totalPemasukan = $query->clone()
            ->whereIn('jenis_transaksi', $pemasukanJenis)
            ->sum('nominal');

        // Hitung total pengeluaran
        $totalPengeluaran = $query->clone()
            ->whereIn('jenis_transaksi', $pengeluaranJenis)
            ->sum('nominal');

        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        // Ambil data transaksi yang telah difilter
        $laporan = $query->paginate(100);

        return view('livewire.laporan-edu-pay-siswa.index', [
            'select_kelas' => $select_kelas,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalSaldo' => $totalSaldo,
            'laporan' => $laporan
        ]);
    }
}
