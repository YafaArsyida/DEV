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
    public $selectedJenjang = null;
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

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        // Reset petugas setiap parameter berubah
        $this->selectedPetugas = null;

        // Reload petugas sesuai jenjang
        $this->loadPetugasByJenjang();

        $this->resetPage();
    }

    public function mount()
    {
        // Default tanggal
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    protected function loadPetugasByJenjang()
    {
        $user = auth()->user();

        // Jika jenjang belum dipilih → kosongkan
        if (!$this->selectedJenjang) {
            $this->select_petugas = collect();
            return;
        }

        // Jika login sebagai kantin → auto set, tidak perlu list
        if ($user->peran === 'kantin') {
            $this->selectedPetugas = $user->ms_pengguna_id;
            $this->select_petugas = collect();
            return;
        }

        // Admin / TU → load petugas kantin sesuai akses jenjang
        $this->select_petugas = User::where('peran', 'kantin')
            ->whereHas('ms_akses_jenjang', function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang);
            })
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
        $user = Auth::user();

        $query = TransaksiSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang);

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
