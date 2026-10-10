<?php

namespace App\Http\Livewire\PPDB\Periode;

use App\Models\PPDBPeriode;
use Livewire\Component;

class Detail extends Component
{
    public $selectedPeriodeDetail = null;

    protected $listeners = [
        'loadDetailPeriode' => 'loadDetail',
    ];

    public function loadDetail($periodeId)
    {
        $this->selectedPeriodeDetail = $periodeId;
    }

    public function render()
    {
        $periodeDetail = null;

        if ($this->selectedPeriodeDetail) {
            $periodeDetail = PPDBPeriode::with([
                'ms_jenjang',
                'ms_tahun_ajar',
            ])
                ->withCount('ppdb_gelombang')
                ->find($this->selectedPeriodeDetail);
        }

        return view('livewire.p-p-d-b.periode.detail',[
            'periodeDetail' => $periodeDetail,
        ]);
    }
}
