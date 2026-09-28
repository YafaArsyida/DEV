<?php

namespace App\Http\Livewire\Akademik\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use Livewire\Component;

class Detail extends Component
{
    public $ms_ekstrakurikuler_id;

    protected $listeners = [
        'detailEkstrakurikuler',
    ];

    public function detailEkstrakurikuler($ms_ekstrakurikuler_id)
    {
        $this->ms_ekstrakurikuler_id = $ms_ekstrakurikuler_id;
    }

    public function render()
    {
        $ekstrakurikuler = null;

        if ($this->ms_ekstrakurikuler_id) {
            $ekstrakurikuler = Ekstrakurikuler::with([
                'ms_jenjang',
                'ms_tahun_ajar',
            ])
                ->withCount([
                    'ms_penempatan_ekstrakurikuler as total_peserta',
                ])
                ->find($this->ms_ekstrakurikuler_id);
        }
        return view('livewire.akademik.ekstrakurikuler.detail', [
            'ekstrakurikuler' => $ekstrakurikuler,
        ]);
    }
}
