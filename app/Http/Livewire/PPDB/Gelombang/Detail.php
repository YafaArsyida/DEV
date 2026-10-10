<?php

namespace App\Http\Livewire\PPDB\Gelombang;

use App\Models\PPDBGelombang;
use Livewire\Component;

class Detail extends Component
{
    public $selectedGelombangDetail = null;

    protected $listeners = [
        'loadDetailGelombang' => 'loadDetail',
    ];

    public function loadDetail($gelombangId)
    {
        $this->selectedGelombangDetail = $gelombangId;
    }

    public function render()
    {
        $gelombangDetail = null;

        if ($this->selectedGelombangDetail) {
            $gelombangDetail = PPDBGelombang::with([
                'ppdb_periode.ms_jenjang',
                'ppdb_periode.ms_tahun_ajar',
            ])->find($this->selectedGelombangDetail);
        }

        return view('livewire.p-p-d-b.gelombang.detail',[
             'gelombangDetail' => $gelombangDetail,
        ]);
    }
}
