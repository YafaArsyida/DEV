<?php

namespace App\Http\Livewire\PPDB\Admin\Dashboard;

use Livewire\Component;
use App\Models\PPDBPeriode;
use App\Models\PPDBJalur;

class InformasiPeriode extends Component
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
        $kuota = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $periode = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->where('status', 'aktif')
                ->first();

            if ($periode) {
                $kuota = PPDBJalur::query()
                    ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                    ->where('status', 'aktif')
                    ->sum('kuota');
            }
        }

        return view('livewire.p-p-d-b.admin.dashboard.informasi-periode',
            compact('periode', 'kuota')
        );
    }
}
