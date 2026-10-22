<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBCalonSiswa;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DetailSiswa extends Component
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

        $calonSiswa = PPDBCalonSiswa::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->firstOrFail();

        return view('livewire.p-p-d-b.pendaftaran.detail-siswa',
            [
                'pendaftaran' => $pendaftaran,
                'calonSiswa' => $calonSiswa,
                'bisaUbahData' => $this->bolehUbahData(
                    $pendaftaran->status
                ),
            ]
        );
    }
}
