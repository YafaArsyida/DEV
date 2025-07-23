<?php

namespace App\Http\Livewire\AkuntansiKelompokRekening;

use App\Models\AkuntansiKelompokRekening;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    protected $listeners = [
        'refreshAkuntansiKelompokRekening' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function render()
    {
        $kelompok = AkuntansiKelompokRekening::query();

        if ($this->search) {
            $kelompok->where('nama_kelompok_rekening', 'like', '%' . $this->search . '%');
        }
        $kelompok = $kelompok->get();
        return view('livewire.akuntansi-kelompok-rekening.index', [
            'kelompok' => $kelompok
        ]);
    }
}
