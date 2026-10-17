<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBJawaban;
use App\Models\PPDBPendaftaran;
use Livewire\Component;

class DataTambahan extends Component
{
     public $pendaftaranId;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;
    }
    public function render()
    {
        $pendaftaran = PPDBPendaftaran::findOrFail(
            $this->pendaftaranId
        );

        $jawaban = PPDBJawaban::query()
            ->with('ppdb_pertanyaan')
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->getKey()
            )
            ->where(
                'ppdb_periode_id',
                $pendaftaran->ppdb_periode_id
            )
            ->get();
        return view('livewire.p-p-d-b.admin.verifikasi.data-tambahan', compact('jawaban'));
    }
}
