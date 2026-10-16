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

    protected function ambilPendaftaranMilikPengguna()
    {
        return PPDBPendaftaran::query()
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

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = PPDBPendaftaran::query()
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->first();

        if (! $pendaftaran) {
            return redirect()->route('ppdb.pendaftaran.jalur');
        }

        $adaDataSiswa =PPDBCalonSiswa::query()
            ->where(
                'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
            )
            ->exists();

        if (! $adaDataSiswa) {
            return redirect()->route('ppdb.pendaftaran.data-siswa');
        }

        $orangTua = PPDBOrangtua::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if (! $orangTua) {
            return redirect()->route('ppdb.pendaftaran.orang-tua');
        }
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
