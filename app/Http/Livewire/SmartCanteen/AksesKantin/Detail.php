<?php

namespace App\Http\Livewire\SmartCanteen\AksesKantin;

use App\Models\User;
use Livewire\Component;

class Detail extends Component
{
    public $penggunaDetail;

    public $nama;
    public $email;
    public $peran;
    public $telepon;
    public $created_at;

    public $aksesKantin;

    protected $listeners = [
        'detailPengguna' => 'detailPengguna',
    ];

    public function detailPengguna($ms_pengguna_id)
    {
        $pengguna = User::with('ms_kantin')
            ->findOrFail($ms_pengguna_id);

        $this->penggunaDetail = $pengguna;

        $this->nama = $pengguna->nama;
        $this->email = $pengguna->email;
        $this->peran = $pengguna->peran;
        $this->telepon = $pengguna->telepon;

        $this->created_at = $pengguna->created_at
            ? $pengguna->created_at->format('d F Y H:i')
            : null;

        $this->aksesKantin = $pengguna->ms_kantin->first();
    }
    
    public function render()
    {
        return view('livewire.smart-canteen.akses-kantin.detail');
    }
}
