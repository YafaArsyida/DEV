<?php

namespace App\Http\Livewire\AksesJenjang;

use App\Models\AksesJenjang;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $pengguna = [];

    protected $listeners = ['refreshPengguna' => 'loadPengguna']; // Gunakan Livewire refresh untuk memuat ulang data

    public function mount()
    {
        $this->loadPengguna();
    }

    public function updatedSearch()
    {
        $this->loadPengguna();
    }

    public function loadPengguna()
    {
        $query = User::with('ms_jenjang')
            ->where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('peran', 'like', '%' . $this->search . '%')
            ->get();

        $this->pengguna = $query->map(function ($user) {
            return [
                'ms_pengguna_id' => $user->ms_pengguna_id,
                'nama' => $user->nama,
                'email' => $user->email,
                'peran' => $user->peran,
                'aksesJenjang' => $user->ms_jenjang->pluck('nama_jenjang')->toArray(),
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.akses-jenjang.index');
    }
}
