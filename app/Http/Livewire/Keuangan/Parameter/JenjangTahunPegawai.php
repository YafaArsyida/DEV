<?php

namespace App\Http\Livewire\Keuangan\Parameter;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

use App\Models\Jenjang;
use App\Models\Pegawai;
use App\Models\TahunAjar;

class JenjangTahunPegawai extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $search = '';

    protected $listeners = [
        'refreshPegawai',
        'refreshParameters',
    ];

    public function handleRefreshPegawai()
    {
        $this->emitSelf('$refresh');
    }

    public function updatedSelectedJenjang()
    {
        $this->checkAndEmitParameters();
    }

    public function updatedSelectedTahunAjar()
    {
        $this->checkAndEmitParameters();
    }

    public function mount()
    {
        // Tetapkan nilai pertama dari data yang tersedia jika ada
        $firstJenjang = Jenjang::whereIn('ms_jenjang_id', function ($query) {
            $query->select('ms_jenjang_id')
                ->from('ms_akses_jenjang')
                ->where('ms_pengguna_id', Auth::id());
        })->where('status', 'Aktif')->first();

        $firstTahunAjar = TahunAjar::where('status', 'Aktif')
            ->orderBy('urutan', 'asc')->first();

        $this->selectedJenjang = $firstJenjang->ms_jenjang_id ?? null;
        $this->selectedTahunAjar = $firstTahunAjar->ms_tahun_ajar_id ?? null;
    }

    private function checkAndEmitParameters()
    {
        // Emit hanya jika kedua parameter tidak null
        if ($this->selectedJenjang !== null && $this->selectedTahunAjar !== null) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function refreshParameters()
    {
        $this->selectedJenjang = null;
        $this->selectedTahunAjar = null;
        $this->emit('parameterUpdated', null, null);
    }

    public function render()
    {
        // Emit parameterUpdated saat komponen dirender pertama kali
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
        }

        $pegawais = null; // Awalnya null untuk menghindari error
        $query = Pegawai::with(['ms_educard', 'ms_jabatan', 'ms_jenjang'])
            ->when($this->selectedJenjang, function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang);
            });

        // Filter pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_pegawai', 'like', '%' . $this->search . '%')
                    ->orWhereHas('ms_educard', function ($q) {
                        $q->where('kode_kartu', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $pegawais = $query->orderBy('nama_pegawai')->paginate(10);

        return view('livewire.keuangan.parameter.jenjang-tahun-pegawai',[
            'select_jenjang' => Jenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id()); // Filter berdasarkan pengguna yang login
            })->where('status', 'Aktif')->get(),
            'select_tahun_ajar' => TahunAjar::where('status', 'Aktif')->orderBy('urutan', 'asc')->get(),
            'pegawais' => $pegawais,
        ]);
    }
}
