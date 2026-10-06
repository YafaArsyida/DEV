<?php

namespace App\Http\Livewire\Keuangan\LaporanTabunganSiswa;

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

    public $perPage = 50;

    public $search = '';
    public $totalSaldo = 0;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;
    public $namaKelas = '';

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

    public function updatedSelectedKelas($value)
    {
        $this->namaKelas = Kelas::where('ms_kelas_id', $value)
            ->value('nama_kelas') ?? '';

        $this->resetPage();
    }

    public function confirmWithdraw()
    {
        $this->emit('showWithdrawModal', [
            'kelas'      => $this->selectedKelas,
            'namaKelas'  => $this->namaKelas,
            'jenjang'    => $this->selectedJenjang,
            'tahunAjar'  => $this->selectedTahunAjar,
        ]);
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

        $url = route('keuangan.laporan.siswa.tabungan.saldo.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun'   => $this->selectedTahunAjar,
            'kelas'   => $this->selectedKelas, // null jika tidak dipilih
        ]);

        $this->emit('openNewTab', $url);
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
            'ms_siswa.ms_saldo_tabungan',
            'ms_kelas'
        ])
        ->where('ms_jenjang_id', $this->selectedJenjang)
        ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

        if ($this->selectedKelas) {
            $query->where('ms_kelas_id', $this->selectedKelas);
        }

        if ($this->search) {
            $query->whereHas('ms_siswa', function ($q) {
                $q->where('nama_siswa', 'like', '%' . trim($this->search) . '%');
            });
        }

        $query->whereHas('ms_siswa.ms_saldo_tabungan', function ($q) {
            $q->where('saldo_tabungan', '!=', 0);
        });

        $query->orderByDesc(
            SaldoTabungan::select('saldo_tabungan')
                ->whereColumn('user_id', 'ms_penempatan_siswa.ms_siswa_id')
                ->where('user_type', 'siswa')
                ->limit(1)
        );
        
        /** @var \Illuminate\Pagination\LengthAwarePaginator $siswas */
        $siswas = $query->paginate($this->perPage);
        $this->totalSaldo = $siswas->getCollection()
            ->sum(fn($item) => $item->ms_siswa->ms_saldo_tabungan->saldo_tabungan ?? 0);

        return view('livewire.keuangan.laporan-tabungan-siswa.saldo', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
