<?php

namespace App\Http\Livewire\Keuangan\LaporanEduPayPegawai;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\SaldoEduPay;
use Livewire\Component;
use Livewire\WithPagination;

class Saldo extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $perPage = 50;

    public $search = '';
    public $totalSaldo = 0;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedJabatan = null;
    public $namaJabatan = '';

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

    public function updatedSelectedJabatan()
    {
        $this->namaJabatan = Jabatan::where('ms_jabatan_id', $this->selectedJabatan)
            ->value('nama_jabatan') ?? '';

        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function cetakSaldo()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jenjang dan Tahun Ajar wajib dipilih'
            ]);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Laporan sedang diproses.'
        ]);

        $url = route('laporan.edupay-pegawai.saldo.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun'   => $this->selectedTahunAjar,
            'jabatan' => $this->selectedJabatan, // null jika tidak dipilih
        ]);

        $this->emit('openNewTab', $url);
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

         /** @var \Illuminate\Pagination\LengthAwarePaginator $pegawai */
        $pegawai = $query->paginate($this->perPage);
        $this->totalSaldo = $pegawai->getCollection()
            ->sum(fn($item) => $item->ms_saldo_edupay->saldo_edupay ?? 0);

        return view('livewire.keuangan.laporan-edu-pay-pegawai.saldo', [
            'select_jabatan' => $select_jabatan,
            'pegawai' => $pegawai,
        ]);
    }
}
