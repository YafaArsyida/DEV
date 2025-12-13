<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Tabungan;
use App\Models\TabunganSiswa;
use App\Models\TransaksiTabungan;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    public $selectedJenisTransaksi = [];
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

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function applyFilters($filters)
    {
        $this->selectedJenisTransaksi = $filters['selectedJenisTransaksi'] ?? [];
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
    }

    public function clearFilters()
    {
        $this->selectedJenisTransaksi = [];
        $this->selectedPetugas = [];
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('laporan.tabungan-siswa.pdf', [
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

        $query = TransaksiTabungan::query()
            ->with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa.ms_kelas']) // ambil relasi kelas juga
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_transaksi_tabungan.user_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_transaksi_tabungan.ms_penempatan_siswa_id')
            ->select(
                'ms_transaksi_tabungan.*',
                'ms_siswa.nama_siswa',
                'ms_penempatan_siswa.ms_jenjang_id',
                'ms_penempatan_siswa.ms_tahun_ajar_id',
                'ms_penempatan_siswa.ms_kelas_id'
            )
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_transaksi_tabungan.user_type', 'siswa')
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->selectedPetugas) {
            $query->where('ms_transaksi_tabungan.ms_pengguna_id', $this->selectedPetugas);
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

        // Filter berdasarkan pilihan
        if ($this->selectedJenisTransaksi) {
            $query->whereIn('jenis_transaksi', $this->selectedJenisTransaksi);
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
