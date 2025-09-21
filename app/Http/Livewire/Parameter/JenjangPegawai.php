<?php

namespace App\Http\Livewire\Parameter;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

use App\Models\Jenjang;
use App\Models\Pegawai;

class JenjangPegawai extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $selectedJenjang = null;
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

    public function mount()
    {
        // Ambil jenjang pertama yang diakses user
        $firstJenjang = Jenjang::whereIn('ms_jenjang_id', function ($query) {
            $query->select('ms_jenjang_id')
                ->from('ms_akses_jenjang')
                ->where('ms_pengguna_id', Auth::id());
        })->where('status', 'Aktif')->first();

        $this->selectedJenjang = $firstJenjang->ms_jenjang_id ?? null;
    }

    private function checkAndEmitParameters()
    {
        if ($this->selectedJenjang !== null) {
            $this->emit('parameterUpdated', $this->selectedJenjang);
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
        $this->emit('parameterUpdated', null);
    }

    public function render()
    {
        // Emit parameterUpdated saat komponen dirender pertama kali
        if ($this->selectedJenjang) {
            $this->emit('parameterUpdated', $this->selectedJenjang);
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

        return view('livewire.parameter.jenjang-pegawai',[
            'select_jenjang' => Jenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id()); // Filter berdasarkan pengguna yang login
            })->where('status', 'Aktif')->get(),
            'pegawais' => $pegawais,
        ]);
    }
}
