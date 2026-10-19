<?php

namespace App\Http\Livewire\PPDB\Admin\Dashboard;

use Livewire\Component;
use App\Models\PPDBPeriode;
use App\Models\PPDBPendaftaran;

class AntreanPekerjaan extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjangId = null, $tahunAjarId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;
    }

    public function render()
    {
        $periode = null;
        $menungguVerifikasi = 0;
        $perluPerbaikan = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $periode = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->where('status', 'aktif')
                ->first();

            if ($periode) {
                $query = PPDBPendaftaran::query()
                    ->where('ppdb_periode_id', $periode->ppdb_periode_id);

                $menungguVerifikasi = (clone $query)
                    ->whereIn('status', [
                        'pengecekan',
                        // 'menunggu_verifikasi',
                    ])
                    ->count();

                $perluPerbaikan = (clone $query)
                    ->where('status', 'perbaikan')
                    ->count();
            }
        }
        return view('livewire.p-p-d-b.admin.dashboard.antrean-pekerjaan',
            compact(
                'periode',
                'menungguVerifikasi',
                'perluPerbaikan'
            )
        );
    }
}
