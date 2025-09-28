<?php

namespace App\Http\Livewire\SmartCanteen\LaporanTransaksi;

use App\Models\TransaksiSmartCanteen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $selectedPetugas = [];
    public $select_petugas = [];

    public $selectedJenis = ''; // default = semua

    public $startDate = null;
    public $endDate = null;

    public $search = '';

    public function mount()
    {
        $user = auth()->user();

        // kalau peran kantin → langsung set otomatis
        if ($user->peran === 'kantin') {
            $this->selectedPetugas = $user->ms_pengguna_id;
        } else {
            // kalau bukan kantin → bisa pilih dari semua petugas
            $this->select_petugas = User::all();
        }

        // Default ke hari ini
        $this->startDate = now()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
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
    public function resetTanggal()
    {
        $this->startDate = now()->format('Y-m-d');
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
        $user = Auth::user();

        $query = TransaksiSmartCanteen::query();

        if ($user->peran === 'kantin') {
            $query->where('ms_pengguna_id', $user->ms_pengguna_id);
        } else {
            // Kalau bukan kantin, cek apakah ada filter petugas
            if (!empty($this->selectedPetugas)) {
                $query->where('ms_pengguna_id', $this->selectedPetugas);
            }
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_transaksi', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        if (!empty($this->selectedJenis)) {
            $query->where('user_type', $this->selectedJenis);
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
