<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use Livewire\Component;

class DataOrangTua extends Component
{
    public $pendaftaranId;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;
    }
    public function render()
    {
        $pendaftaran = PPDBPendaftaran::with('ppdb_orangtua')
            ->find($this->pendaftaranId);

        $orangTua = $pendaftaran?->ppdb_orangtua;

        return view('livewire.p-p-d-b.admin.verifikasi.data-orang-tua',[
            'orangTua' => $orangTua,
        ]);
    }
}
