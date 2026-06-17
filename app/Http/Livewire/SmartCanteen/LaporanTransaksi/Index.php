<?php

namespace App\Http\Livewire\SmartCanteen\LaporanTransaksi;

use App\Models\Jenjang;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TahunAjar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    // Parameter dari listener
    public $selectedKantin = null;
    public $selectedTahunAjar = null;

    public $selectedPetugas = [];
    public $select_petugas = [];

    public $selectedJenis = ''; // default = semua

    public $startDate = null;
    public $endDate = null;

    public $search = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($kantin, $tahunAjar)
    {
        $this->selectedKantin = $kantin;
        $this->selectedTahunAjar = $tahunAjar;

        // Reset petugas setiap parameter berubah
        $this->selectedPetugas = null;

        // Reload petugas sesuai Kantin
        $this->loadPetugasByKantin();

        $this->resetPage();
    }

    public function mount()
    {
        // Default tanggal
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    protected function loadPetugasByKantin()
    {
        // Jika jenjang belum dipilih → kosongkan
        if (!$this->selectedKantin) {
            $this->selectedPetugas = null;
            $this->select_petugas = collect();
            return;
        }

        $this->select_petugas = User::whereHas('ms_kantin', function ($q) {
            $q->where('ms_kantin.ms_kantin_id', $this->selectedKantin);
        })
            ->where('peran', 'KANTIN')
            ->orderBy('nama')
            ->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedPetugas()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Memperbarui...'
        ]);
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

    public function cetakLaporan()
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('smartCanteen.laporan.transaksi.pdf', [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'petugas' => $this->selectedPetugas,
            'pembeli' => $this->selectedJenis,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $query = TransaksiSmartCanteen::where('ms_kantin_id', $this->selectedKantin);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_transaksi', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        if (!empty($this->selectedJenis)) {
            $query->where('user_type', $this->selectedJenis);
        }

        if (!empty($this->selectedPetugas)) {
            $query->where('ms_pengguna_id', $this->selectedPetugas);
        }

        // // // Search nama siswa
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('ms_siswa', function ($siswa) {
                    $siswa->where('nama_siswa', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('ms_pegawai', function ($pegawai) {
                        $pegawai->where('nama_pegawai', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $laporan = $query->orderBy('tanggal_transaksi', 'asc')->paginate(100);

        // Hitung total nominal transaksi (sesuai filter)
        $totalTransaksi = $query->sum('total_transaksi');

        return view('livewire.smart-canteen.laporan-transaksi.index', [
            'laporan' => $laporan,
            'totalTransaksi' => $totalTransaksi,
        ]);
    }
}
