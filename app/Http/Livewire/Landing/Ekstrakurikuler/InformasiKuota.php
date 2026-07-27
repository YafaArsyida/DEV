<?php

namespace App\Http\Livewire\Landing\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use Livewire\Component;

class InformasiKuota extends Component
{
    protected $listeners = [
        'ekstrakurikulerTerdaftar' => '$refresh',
    ];

    public function render()
    {
        // Query hanya jika jenjang dan tahun ajar dipilih
        $data = Ekstrakurikuler::query()
            ->where('ms_jenjang_id', '1')
            ->where('ms_tahun_ajar_id', '1')
            ->withCount('ms_penempatan_ekstrakurikuler')
            ->orderByDesc('ms_ekstrakurikuler_id')
            ->get();

        return view('livewire.landing.ekstrakurikuler.informasi-kuota',[
             'data' => $data
        ]);
    }
}
