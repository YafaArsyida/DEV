<?php

namespace App\Http\Livewire\Keuangan\LaporanEduPayPegawai;

use App\Models\Jabatan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TransaksiEduPay;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $perPage = 50;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedJabatan = null;

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
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function resetTanggal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->resetPage(); // Reset pagination ketika pencarian berubah
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function applyFilters($filters)
    {
        $this->selectedPetugas = $filters['selectedPetugas'] ?? [];
        $this->selectedJenisTransaksi = $filters['selectedJenisTransaksi'] ?? [];
    }

    public function clearFilters()
    {
        $this->selectedPetugas = [];
        $this->selectedJenisTransaksi = [];
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

        $url = route('laporan.edupay-pegawai.pdf', [
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

        $query = TransaksiEduPay::query()
            ->with(['ms_pegawai', 'ms_pengguna', 'ms_pegawai.ms_jabatan'])
            ->join('ms_pegawai', 'ms_pegawai.ms_pegawai_id', '=', 'ms_transaksi_edupay.user_id')
            ->leftJoin('ms_jabatan', 'ms_jabatan.ms_jabatan_id', '=', 'ms_pegawai.ms_jabatan_id')
            ->select(
                'ms_transaksi_edupay.*',
                'ms_pegawai.nama_pegawai',
                'ms_pegawai.ms_jenjang_id',
                'ms_pegawai.ms_jabatan_id',
                'ms_jabatan.nama_jabatan'
            )
            ->where('ms_transaksi_edupay.user_type', 'pegawai')
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan jenjang (kalau ada)
        if ($this->selectedJenjang) {
            $query->where('ms_pegawai.ms_jenjang_id', $this->selectedJenjang);
        }

        // Filter berdasarkan jabatan (bukan kelas)
        if ($this->selectedJabatan) {
            $query->where('ms_pegawai.ms_jabatan_id', $this->selectedJabatan);
        }

        // Filter berdasarkan petugas (penginput transaksi)
        if ($this->selectedPetugas) {
            $query->where('ms_transaksi_edupay.ms_pengguna_id', $this->selectedPetugas);
        }

        // Filter berdasarkan nama pegawai
        if ($this->search) {
            $query->where('ms_pegawai.nama_pegawai', 'like', '%' . trim($this->search) . '%');
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

        // Filter berdasarkan jenis transaksi (jika relevan)
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
        $laporan = $query->paginate($this->perPage);

        return view('livewire.keuangan.laporan-edu-pay-pegawai.index', [
            'select_jabatan' => $select_jabatan,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalSaldo' => $totalSaldo,
            'laporan' => $laporan
        ]);
    }
}
