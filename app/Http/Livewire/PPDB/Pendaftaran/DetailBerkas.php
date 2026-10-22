<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBAlamat;
use App\Models\PPDBBerkas;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DetailBerkas extends Component
{
    public $pendaftaranId;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;
    }

    protected function ambilPendaftaranMilikPengguna()
    {
        return PPDBPendaftaran::query()
            ->with('ppdb_periode.ms_jenjang')
            ->whereKey($this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->firstOrFail();
    }

     protected function bolehUbahData($status)
    {
        return ! in_array(
            strtolower(trim((string) $status)),
            ['diterima', 'pengecekan'],
            true
        );
    }

    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $berkas = PPDBBerkas::query()
            ->where(
                'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
            );

        return view('livewire.p-p-d-b.pendaftaran.detail-berkas',[
            'pendaftaran' => $pendaftaran,

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

            'bisaUbahData' => $this->bolehUbahData(
                $pendaftaran->status
            ),
        ]);
    }
}
