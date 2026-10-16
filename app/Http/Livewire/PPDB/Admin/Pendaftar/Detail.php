<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use App\Models\PPDBPendaftaran;
use App\Models\PPDBJawaban;
use Livewire\Component;

class Detail extends Component
{
    public $pendaftaran = null;
    public $jawabanTambahan = [];
    public $pendaftaranId = null;

    protected $listeners = [
        'loadDetailPendaftar' => 'loadDetail',
    ];

    public function loadDetail($id)
    {
        $this->reset([
            'pendaftaran',
            'jawabanTambahan',
        ]);

        $this->pendaftaranId = $id;

        $this->pendaftaran = PPDBPendaftaran::query()
            ->with([
                'ppdb_periode',
                'ppdb_gelombang',
                'ppdb_jalur',
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_alamat',
                'ppdb_pendidikan',
                'ppdb_berkas',
                'ms_pengguna',
            ])
            ->findOrFail($id);

        $this->jawabanTambahan = PPDBJawaban::query()
            ->with('ppdb_pertanyaan')
            ->where(
                'ppdb_pendaftaran_id',
                $this->pendaftaran->ppdb_pendaftaran_id
            )
            ->where(
                'ppdb_periode_id',
                $this->pendaftaran->ppdb_periode_id
            )
            ->whereHas('ppdb_pertanyaan', function ($query) {
                $query->where('status', 'aktif');
            })
            ->get()
            ->sortBy(function ($item) {
                return $item->ppdb_pertanyaan->urutan ?? 0;
            })
            ->values();
    }
    
    public function render()
    {
        return view('livewire.p-p-d-b.admin.pendaftar.detail');
    }
}
