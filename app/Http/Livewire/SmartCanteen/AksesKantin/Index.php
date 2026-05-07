<?php

namespace App\Http\Livewire\SmartCanteen\AksesKantin;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $pengguna = [];

    protected $listeners = [
        'refreshPengguna' => 'loadPengguna',

        'refreshKantin' => 'loadPengguna',
    ]; // Gunakan Livewire refresh untuk memuat ulang data

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
        $query = User::with(['ms_kantin'])
            ->where('peran', 'KANTIN')

            // 🔍 search
            ->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })

            ->get();

        $this->pengguna = $query->map(function ($user) {
            return [
                'ms_pengguna_id' => $user->ms_pengguna_id,
                'nama' => $user->nama,
                'email' => $user->email,
                'peran' => $user->peran,
                'aksesKantin' => $user->ms_kantin
                    ->pluck('nama_kantin')
                    ->toArray(),
            ];
        })->toArray();
    }
    public function render()
    {
        return view('livewire.smart-canteen.akses-kantin.index');
    }
}
