<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\SaldoTabungan;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Saldo extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $search = '';
    public $totalSaldo = 0;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshSaldoTabunganSiswa'
    ];

    public function refreshSaldoTabunganSiswa()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->resetPage(); // Reset pagination ketika parameter berubah
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function updatingSelectedKelas()
    {
        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function render()
    {
        // Dropdown kelas
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // =========================
        // QUERY UTAMA
        // =========================
        $query = PenempatanSiswa::with([
            'ms_siswa.ms_educard',
            'ms_siswa.ms_saldo_tabungan',
            'ms_kelas'
        ])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

        // Filter kelas
        if ($this->selectedKelas) {
            $query->where('ms_kelas_id', $this->selectedKelas);
        }

        // Search
        if ($this->search) {
            $query->whereHas('ms_siswa', function ($q) {
                $q->where('nama_siswa', 'like', '%' . trim($this->search) . '%')
                    ->orWhereHas('ms_educard', function ($q2) {
                        $q2->where('kode_kartu', 'like', '%' . trim($this->search) . '%');
                    });
            });
        }

        // 🔥 HANYA siswa yang punya saldo ≠ 0
        $query->whereHas('ms_siswa.ms_saldo_tabungan', function ($q) {
            $q->where('saldo_tabungan', '!=', 0);
        });

        // 🔥 SORT by saldo (pakai subquery, bukan join manual)
        $query->orderByDesc(
            SaldoTabungan::select('saldo_tabungan')
                ->whereColumn('user_id', 'ms_penempatan_siswa.ms_siswa_id')
                ->where('user_type', 'siswa')
                ->limit(1)
        );

        $siswas = $query->get();

        // =========================
        // TOTAL SALDO
        // =========================
        $this->totalSaldo = $siswas->sum(function ($item) {
            return $item->ms_siswa->ms_saldo_tabungan->saldo_tabungan ?? 0;
        });

        // =========================
        // NOTIF
        // =========================
        if ($siswas->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui..']);
        }

        return view('livewire.laporan-tabungan-siswa.saldo', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
