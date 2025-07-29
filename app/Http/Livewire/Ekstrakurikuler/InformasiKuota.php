<?php

namespace App\Http\Livewire\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use Livewire\Component;

class InformasiKuota extends Component
{
    public function render()
    {
        // Query hanya jika jenjang dan tahun ajar dipilih
        $data = Ekstrakurikuler::query();

        // Ambil data yang sudah difilter dan paginasi
        $data = $data->get();
        return view('livewire.ekstrakurikuler.informasi-kuota',[
            'data' => $data
        ]);
    }
}
