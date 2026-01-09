<?php

namespace App\Http\Livewire\SmartPass\LaporanPresensiPegawai;

use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\SmartPass\PresensiPegawai;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';
    public $selectedJenjang = null;
    public $selectedJabatan = null;

    public $startDate;
    public $endDate;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];


    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;

        $janjang = Jenjang::find($jenjang);

        $this->resetPage(); // Reset pagination ketika parameter berubah
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->resetPage();
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->resetPage();
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->resetPage();
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }
    public function render()
    {
        $query = PresensiPegawai::with(['ms_pegawai.ms_jabatan']);

        // Filter Jenjang (kalau pegawai punya relasi jenjang)
        if ($this->selectedJenjang) {
            $query->whereHas('ms_pegawai', function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang);
            });
        }

        // Filter Jabatan
        if ($this->selectedJabatan) {
            $query->whereHas('ms_pegawai', function ($q) {
                $q->where('ms_jabatan_id', $this->selectedJabatan);
            });
        }

        // Filter Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('ms_pegawai', function ($p) {
                    $p->where('nama_pegawai', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            });
        }

        // Filter Periode
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [
                $this->startDate,
                $this->endDate
            ]);
        }

        $presensi = $query->orderBy('tanggal', 'desc')->paginate(10);

        $jabatan = Jabatan::orderBy('nama_jabatan')->get();

        return view('livewire.smart-pass.laporan-presensi-pegawai.index',[
            'presensi' => $presensi,
            'jabatan'  => $jabatan,
        ]);
    }
}
