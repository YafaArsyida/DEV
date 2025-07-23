<?php

namespace App\Http\Livewire\Pegawai;

use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\Pegawai;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';

    public $selectedJenjang = null;

    protected $listeners = ['refreshPegawais' => '$refresh']; // Gunakan Livewire refresh untuk memuat ulang data

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedJenjang()
    {
        $this->resetPage();
    }

    public function render()
    {
        $select_jenjang = [];
        $select_jenjang = Jenjang::get();

        $query = Pegawai::query()->with('ms_educard');;

        if ($this->selectedJenjang) {
            $query->where('ms_jenjang_id', $this->selectedJenjang);
        }

        $pegawais = $query->where(function ($query) {
            $query->where('nama_pegawai', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
        })
            ->orderBy('ms_jenjang_id', 'ASC') // Tambahkan pengurutan berdasarkan ms_jabatan_id
            ->orderBy('ms_jabatan_id', 'ASC') // Tambahkan pengurutan berdasarkan ms_jabatan_id
            ->orderBy('nama_pegawai', 'ASC') // Lanjutkan pengurutan berdasarkan nama_pegawai
            ->paginate(500);

        return view('livewire.pegawai.index', [
            'select_jenjang' => $select_jenjang,
            'pegawais' => $pegawais
        ]);
    }
}
