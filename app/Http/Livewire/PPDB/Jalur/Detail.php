<?php

namespace App\Http\Livewire\PPDB\Jalur;

use App\Models\PPDBJalur;
use Livewire\Component;

class Detail extends Component
{
    public $selectedJalurDetail = null;

    protected $listeners = [
        'loadDetailJalur' => 'loadDetail',
    ];

    /**
     * Memuat ID jalur yang akan ditampilkan.
     */
    public function loadDetail($jalurId)
    {
        $this->selectedJalurDetail = $jalurId;
    }

    public function render()
    {
        $jalurDetail = null;

        if ($this->selectedJalurDetail) {
            $jalurDetail = PPDBJalur::with([
                'ppdb_periode.ms_jenjang',
                'ppdb_periode.ms_tahun_ajar',
            ])->find($this->selectedJalurDetail);
        }
        return view('livewire.p-p-d-b.jalur.detail',[
            'jalurDetail' => $jalurDetail,
        ]);
    }
}
