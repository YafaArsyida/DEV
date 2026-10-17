<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use Livewire\Component;

class DataPendidikan extends Component
{
    public $pendaftaranId;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;
    }
    public function render()
    {
        $pendaftaran = PPDBPendaftaran::with('ppdb_pendidikan')
            ->find($this->pendaftaranId);

        $pendidikan = $pendaftaran?->ppdb_pendidikan;

        return view('livewire.p-p-d-b.admin.verifikasi.data-pendidikan',[
            'pendidikan' => $pendidikan,
        ]);
    }
}
