<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBCalonSiswa;
use App\Models\PPDBOrangtua;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DetailOrangTua extends Component
{
    public $pendaftaranId;
    public $gunakan_wali = false;

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

        $orangTua = PPDBOrangtua::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->firstOrFail();
            
        return view('livewire.p-p-d-b.pendaftaran.detail-orang-tua',[
            'pendaftaran' => $pendaftaran,
            'orangTua' => $orangTua,
            'bisaUbahData' => $this->bolehUbahData(
                $pendaftaran->status
            ),
        ]);
    }
}
