<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBBerkas;
use App\Models\PPDBPendaftaran;
use Livewire\Component;

class DataBerkas extends Component
{
    public $pendaftaranId;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;
    }

    public function render()
    {
        $berkas = PPDBBerkas::query()
                    ->where('ppdb_pendaftaran_id', $this->pendaftaranId);

        return view('livewire.p-p-d-b.admin.verifikasi.data-berkas',[
             'kartuKeluarga' => (clone $berkas)
                ->where('jenis_berkas', 'KARTU_KELUARGA')
                ->first(),

            'aktaKelahiran' => (clone $berkas)
                ->where('jenis_berkas', 'AKTA_KELAHIRAN')
                ->first(),

            'pasFoto' => (clone $berkas)
                ->where('jenis_berkas', 'PAS_FOTO')
                ->first(),

            'dokumenPendukung' => (clone $berkas)
                ->where('jenis_berkas', 'DOKUMEN_PENDUKUNG')
                ->first(),
        ]);
    }
}
