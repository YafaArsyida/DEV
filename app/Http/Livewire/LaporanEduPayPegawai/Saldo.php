<?php

namespace App\Http\Livewire\LaporanEduPayPegawai;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\SaldoEduPay;
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
    public $selectedJabatan = null;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshSaldoEduPay'
    ];

    public function refreshSaldoEduPay()
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

    public function updatingSelectedJabatan()
    {
        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_jabatan = Jabatan::get();

        // =========================
        // QUERY UTAMA
        // =========================
        $query = Pegawai::with([
            'ms_jabatan',
            'ms_jenjang',
            'ms_saldo_edupay'
        ])
            ->where('ms_jenjang_id', $this->selectedJenjang);

        // Filter jabatan
        if ($this->selectedJabatan) {
            $query->where('ms_jabatan_id', $this->selectedJabatan);
        }

        // Search
        if ($this->search) {
            $query->where('nama_pegawai', 'like', '%' . trim($this->search) . '%');
        }

        // 🔥 Hanya yang punya saldo ≠ 0
        $query->whereHas('ms_saldo_edupay', function ($q) {
            $q->where('saldo_edupay', '!=', 0);
        });

        // 🔥 Sort by saldo (subquery, TANPA join manual)
        $query->orderByDesc(
            SaldoEduPay::select('saldo_edupay')
                ->whereColumn('user_id', 'ms_pegawai.ms_pegawai_id')
                ->where('user_type', 'pegawai')
                ->limit(1)
        );

        $pegawai = $query->get();

        // =========================
        // TOTAL SALDO
        // =========================
        // $this->totalSaldo = SaldoTabungan::pegawai()
        //     ->whereIn('user_id', $pegawai->pluck('ms_pegawai_id'))
        //     ->sum('saldo_edupay');

        $this->totalSaldo = $pegawai->sum(function ($item) {
            return $item->ms_saldo_edupay->saldo_edupay ?? 0;
        });

        // =========================
        // NOTIF
        // =========================
        if ($pegawai->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak ditemukan.']);
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui..']);
        }

        return view('livewire.laporan-edu-pay-pegawai.saldo', [
            'select_jabatan' => $select_jabatan,
            'pegawai' => $pegawai,
        ]);
    }
}
