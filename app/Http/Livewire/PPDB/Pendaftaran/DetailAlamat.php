<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBAlamat;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DetailAlamat extends Component
{
    public $pendaftaranId;

    protected function ambilPendaftaranMilikPengguna()
    {
        return PPDBPendaftaran::query()
            ->whereKey($this->pendaftaranId)
            ->where('ms_penguna_id', Auth::id())
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
            ->where('ms_penguna_id', Auth::id())
            ->first();

        if (! $pendaftaran) {
            return redirect()->route('ppdb.pendaftaran.jalur');
        }

        // Data orang tua wajib tersedia sebelum membuka alamat.
        $orangTua = PPDBOrangTua::query()
            ->where(
                'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
            )
            ->exists();

        if (! $orangTua) {
            return redirect()->route('ppdb.pendaftaran.orang-tua');
        }

        $alamat = PPDBAlamat::query()
            ->where(
                'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if (! $alamat) {
            return redirect()->route('ppdb.pendaftaran.alamat');
        }
    }

    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $alamat = PPDBAlamat::where(
            'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
        )->first();

        if (! $alamat) {
            return redirect()->route('ppdb.pendaftaran.alamat');
        }

        return view('livewire.p-p-d-b.pendaftaran.detail-alamat',[
            'pendaftaran' => $pendaftaran,
            'alamat' => $alamat,
            'bisaUbahData' => $this->bolehUbahData(
                $pendaftaran->status
            ),
        ]);
    }
}
