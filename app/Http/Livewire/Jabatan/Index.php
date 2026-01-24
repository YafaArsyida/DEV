<?php

namespace App\Http\Livewire\Jabatan;

use App\Models\Jabatan;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';

    protected $listeners = ['JabatanIndex' => '$refresh']; // Gunakan Livewire refresh untuk memuat ulang data

    public function render()
    {
        $query = Jabatan::query();

        $jabatans = $query->where(function ($query) {
            $query->where('nama_jabatan', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
        })
            // ->orderBy('nama_jabatan', 'ASC')
            ->paginate(50);

        return view('livewire.jabatan.index', [
            'jabatans' => $jabatans,
        ]);
    }
}
