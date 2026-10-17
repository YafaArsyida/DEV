<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use Livewire\Component;

class DataAlamat extends Component
{
    public $pendaftaranId;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;
    }
    public function render()
    {
        $pendaftaran = PPDBPendaftaran::with('ppdb_alamat')
            ->find($this->pendaftaranId);

        $alamat = $pendaftaran?->ppdb_alamat;
        return view('livewire.p-p-d-b.admin.verifikasi.data-alamat',[
            'alamat' => $alamat,
        ]);
    }
}
