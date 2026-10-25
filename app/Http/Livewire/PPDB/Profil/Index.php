<?php

namespace App\Http\Livewire\PPDB\Profil;

use App\Models\Jenjang;
use App\Models\PPDBPendaftaran;
use Livewire\Component;

class Index extends Component
{
    public $jenjangSlug;

    protected $listeners = [
        'profilUpdated' => '$refresh',
    ];

    public function mount($jenjang)
    {
        $this->jenjangSlug = is_object($jenjang)
            ? $jenjang->slug
            : $jenjang;
    }

    public function render()
    {
        $pengguna = auth()->user();

        $jenjang = Jenjang::where('slug', $this->jenjangSlug)
            ->firstOrFail();

        $pendaftaran = PPDBPendaftaran::with('ppdb_periode')
            ->where('ms_pengguna_id', $pengguna->ms_pengguna_id)
            ->whereHas('ppdb_periode', function ($query) use ($jenjang) {
                $query->where(
                    'ms_jenjang_id',
                    $jenjang->ms_jenjang_id
                );
            })
            ->latest('created_at')
            ->first();

        return view('livewire.p-p-d-b.profil.index',[
            'pengguna' => $pengguna,
            'jenjang' => $jenjang,
            'pendaftaran' => $pendaftaran,
        ]);
    }
}
