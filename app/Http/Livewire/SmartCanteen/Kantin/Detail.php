<?php

namespace App\Http\Livewire\SmartCanteen\Kantin;

use App\Models\SmartCanteen\Kantin;
use Livewire\Component;

class Detail extends Component
{
    public $kantinDetail;

    public $nama_kantin;
    public $deskripsi;
    public $created_at;
    public $petugas = [];

    protected $listeners = ['detailKantin'];

    public function detailKantin($ms_kantin_id)
    {
        $kantin = Kantin::with(['ms_pengguna']) // ✅ ambil petugas
            ->findOrFail($ms_kantin_id);

        $this->kantinDetail = $kantin;

        $this->nama_kantin = $kantin->nama_kantin;
        $this->deskripsi = $kantin->deskripsi;
        $this->created_at = optional($kantin->created_at)->format('d F Y H:i');

        $this->petugas = $kantin->ms_pengguna
            ->pluck('nama')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.smart-canteen.kantin.detail');
    }
}
