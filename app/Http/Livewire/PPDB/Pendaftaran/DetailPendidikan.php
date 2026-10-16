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
            ->where(
                'ppdb_pendaftaran_id',
                $this->pendaftaranId
            )
            ->where('ms_pengguna_id', Auth::id())
            ->first();

        if (! $pendaftaran) {
            return redirect()->route(
                'ppdb.pendaftaran.jalur'
            );
        }

        // Data alamat wajib tersedia sebelum pendidikan.
        $alamat = PPDBAlamat::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->exists();

        if (! $alamat) {
            return redirect()->route(
                'ppdb.pendaftaran.alamat'
            );
        }

        // Data pendidikan harus sudah tersedia.
        $pendidikan = PpdbPendidikan::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->exists();

        if (! $pendidikan) {
            return redirect()->route(
                'ppdb.pendaftaran.pendidikan'
            );
        }
    }

    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $pendidikan = PpdbPendidikan::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if (! $pendidikan) {
            return redirect()->route(
                'ppdb.pendaftaran.pendidikan'
            );
        }

        return view('livewire.p-p-d-b.pendaftaran.detail-pendidikan',[
            'pendaftaran' => $pendaftaran,
            'pendidikan' => $pendidikan,
            'bisaUbahData' => $this->bolehUbahData(
                $pendaftaran->status
            ),
        ]);
    }
}
