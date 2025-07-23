<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Tabungan;
use App\Models\TabunganSiswa;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    public $selectedPetugas = [];

    public $startDate = null;
    public $endDate = null;

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
        $this->startDate = $filters['startDate'] ?? null;
        $this->endDate = $filters['endDate'] ?? null;
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
    }

    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->selectedPetugas = [];
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function showExportTabunganSiswa()
    {
        $query = TabunganSiswa::query()
            ->with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa.ms_kelas'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_tabungan_siswa.ms_siswa_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tabungan_siswa.ms_penempatan_siswa_id')
            ->select('ms_tabungan_siswa.*', 'ms_siswa.nama_siswa', 'ms_penempatan_siswa.ms_jenjang_id', 'ms_penempatan_siswa.ms_tahun_ajar_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->selectedPetugas) {
            $query->where('ms_tabungan_siswa.ms_pengguna_id', $this->selectedPetugas);
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

        // Hitung total kredit, debit, dan saldo
        $totalKredit = (clone $query)->where('jenis_transaksi', 'setoran')->sum('nominal');
        $totalDebit = (clone $query)->where('jenis_transaksi', 'penarikan')->sum('nominal');
        $totalSaldo = $totalKredit - $totalDebit;

        // Ambil data transaksi yang telah difilter
        $laporan = $query->get();

        // Emit data ke komponen lain untuk diexport
        $this->emit('prepareExport', $laporan->toArray(), $totalKredit, $totalDebit, $totalSaldo);
    }

    public function render()
    {
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $query = TabunganSiswa::query()
            ->with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_tabungan_siswa.ms_siswa_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tabungan_siswa.ms_penempatan_siswa_id')
            ->select('ms_tabungan_siswa.*', 'ms_siswa.nama_siswa', 'ms_penempatan_siswa.ms_jenjang_id', 'ms_penempatan_siswa.ms_tahun_ajar_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->selectedPetugas) {
            $query->where('ms_tabungan_siswa.ms_pengguna_id', $this->selectedPetugas);
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

        // Hitung total kredit, debit, dan saldo
        $totalKredit = (clone $query)->where('jenis_transaksi', 'setoran')->sum('nominal');
        $totalDebit = (clone $query)->where('jenis_transaksi', 'penarikan')->sum('nominal');
        $totalSaldo = $totalKredit - $totalDebit;

        // Ambil data transaksi yang telah difilter
        $laporan = $query->paginate(50);

        return view('livewire.laporan-tabungan-siswa.index', [
            'select_kelas' => $select_kelas,
            'totalKredit' => $totalKredit,
            'totalDebit' => $totalDebit,
            'totalSaldo' => $totalSaldo,
            'laporan' => $laporan
        ]);
    }
}
