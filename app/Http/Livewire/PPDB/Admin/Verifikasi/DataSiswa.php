<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use Livewire\Component;

class DataSiswa extends Component
{
    public $pendaftaranId;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;
    }
    public function render()
    {
        $pendaftaran = PPDBPendaftaran::with('ppdb_calon_siswa')
            ->find($this->pendaftaranId);

        $siswa = $pendaftaran?->ppdb_calon_siswa;

        return view('livewire.p-p-d-b.admin.verifikasi.data-siswa',[
            'siswa' => $siswa,
        ]);
    }
}
