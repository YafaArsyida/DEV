<?php

namespace App\Http\Livewire\PPDB\Pertanyaan;

use App\Models\PPDBPertanyaan;
use Livewire\Component;

class Detail extends Component
{
    public $selectedPertanyaanDetail = null;

    protected $listeners = [
        'loadDetailPertanyaan' => 'loadDetail',
    ];

    /**
     * Memuat ID pertanyaan yang akan ditampilkan.
     */
    public function loadDetail($pertanyaanId)
    {
        $this->selectedPertanyaanDetail = $pertanyaanId;
    }

    public function render()
    {
        $pertanyaanDetail = null;

        if ($this->selectedPertanyaanDetail) {
            $pertanyaanDetail = PPDBPertanyaan::with([
                'ppdb_periode.ms_jenjang',
                'ppdb_periode.ms_tahun_ajar',
            ])->find($this->selectedPertanyaanDetail);
        }
        return view('livewire.p-p-d-b.pertanyaan.detail',[
            'pertanyaanDetail' => $pertanyaanDetail,
        ]);
    }
}
