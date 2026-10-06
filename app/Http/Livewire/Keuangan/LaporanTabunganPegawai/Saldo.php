<?php

namespace App\Http\Livewire\Keuangan\LaporanTabunganPegawai;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\SaldoTabungan;
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
        'refreshSaldoTabungan'
    ];

    public function refreshSaldoTabungan()
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

        $url = route('keuangan.laporan.pegawai.tabungan.saldo.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'jabatan' => $this->selectedJabatan,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function updatingSelectedJabatan()
    {
        $this->namaJabatan = Jabatan::where('ms_jabatan_id', $this->selectedJabatan)
            ->value('nama_jabatan') ?? '';

        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function render()
    {
        $select_jabatan = Jabatan::get();

        // =========================
        // QUERY UTAMA
        // =========================
        $query = Pegawai::with([
            'ms_jabatan',
            'ms_jenjang',
            'ms_saldo_tabungan'
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
        $query->whereHas('ms_saldo_tabungan', function ($q) {
            $q->where('saldo_tabungan', '!=', 0);
        });

        // 🔥 Sort by saldo (subquery, TANPA join manual)
        $query->orderByDesc(
            SaldoTabungan::select('saldo_tabungan')
                ->whereColumn('user_id', 'ms_pegawai.ms_pegawai_id')
                ->where('user_type', 'pegawai')
                ->limit(1)
        );

         /** @var \Illuminate\Pagination\LengthAwarePaginator $pegawai */
        $pegawai = $query->paginate($this->perPage);
        $this->totalSaldo = $pegawai->getCollection()
            ->sum(fn($item) => $item->ms_saldo_tabungan->saldo_tabungan ?? 0);

        return view('livewire.keuangan.laporan-tabungan-pegawai.saldo', [
            'select_jabatan' => $select_jabatan,
            'pegawai' => $pegawai,
        ]);
    }
}
