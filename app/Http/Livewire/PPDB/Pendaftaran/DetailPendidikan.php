<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBPendaftaran;
use App\Models\PPDBOrangTua;
use App\Models\PPDBAlamat;
use App\Models\PpdbPendidikan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DetailPendidikan extends Component
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

        $pendidikan = PpdbPendidikan::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->firstOrFail();

        return view('livewire.p-p-d-b.pendaftaran.detail-pendidikan',[
            'pendaftaran' => $pendaftaran,
            'pendidikan' => $pendidikan,
            'bisaUbahData' => $this->bolehUbahData(
                $pendaftaran->status
            ),
        ]);
    }
}
