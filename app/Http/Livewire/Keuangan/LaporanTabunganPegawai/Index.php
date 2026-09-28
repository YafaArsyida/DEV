<?php

namespace App\Http\Livewire\Keuangan\LaporanTabunganPegawai;

use App\Models\Jabatan;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedJabatan = null;

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
            'jabatan' => $this->selectedJabatan,
            'jenis_transaksi' => $this->selectedJenisTransaksi,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $select_jabatan = Jabatan::get();

        $query = TransaksiTabungan::query()
            ->with(['ms_pengguna', 'ms_pegawai.ms_jabatan']) // ambil relasi pegawai dan jabatannya
            ->join('ms_pengguna', 'ms_pengguna.ms_pengguna_id', '=', 'ms_transaksi_tabungan.ms_pengguna_id')
            ->leftJoin('ms_pegawai', 'ms_pegawai.ms_pegawai_id', '=', 'ms_transaksi_tabungan.user_id')
            ->leftJoin('ms_jabatan', 'ms_jabatan.ms_jabatan_id', '=', 'ms_pegawai.ms_jabatan_id')
            ->select(
                'ms_transaksi_tabungan.*',
                'ms_pengguna.nama',
                'ms_pegawai.nama_pegawai',
                'ms_jabatan.nama_jabatan'
            )
            ->where('ms_transaksi_tabungan.user_type', 'pegawai')
            ->orderBy('ms_transaksi_tabungan.tanggal', 'ASC');
        // Filter berdasarkan jabatan (misal: guru, TU, dll)
        if ($this->selectedJabatan) {
            $query->where('ms_pegawai.ms_jabatan_id', $this->selectedJabatan);
        }

        // Filter berdasarkan petugas (yang input transaksi)
        if ($this->selectedPetugas) {
            $query->whereIn('ms_transaksi_tabungan.ms_pengguna_id', $this->selectedPetugas);
        }

        // Filter berdasarkan nama pegawai
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ms_pegawai.nama_pegawai', 'like', '%' . trim($this->search) . '%');
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereBetween('ms_transaksi_tabungan.tanggal', [$startDate, $endDate]);
        }

        // Filter berdasarkan jenis transaksi
        if ($this->selectedJenisTransaksi) {
            $query->whereIn('ms_transaksi_tabungan.jenis_transaksi', $this->selectedJenisTransaksi);
        }

        $totalKredit = (clone $query)->where('ms_transaksi_tabungan.jenis_transaksi', 'setoran')->sum('ms_transaksi_tabungan.nominal');
        $totalDebit = (clone $query)->where('ms_transaksi_tabungan.jenis_transaksi', 'penarikan')->sum('ms_transaksi_tabungan.nominal');
        $totalSaldo = $totalKredit - $totalDebit;

        // Ambil data transaksi yang telah difilter
        $laporan = $query->paginate(100);

        return view('livewire.keuangan.laporan-tabungan-pegawai.index', [
            'select_jabatan' => $select_jabatan,
            'totalKredit' => $totalKredit,
            'totalDebit' => $totalDebit,
            'totalSaldo' => $totalSaldo,
            'laporan' => $laporan
        ]);
    }
}
