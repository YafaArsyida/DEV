<?php

namespace App\Http\Livewire\AkuntansiRekening;

use App\Models\AkuntansiKelompokRekening;
use App\Models\AkuntansiRekening;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    public $selectedKelompokRekening = null;

    // Listener untuk Livewire
    protected $listeners = [
        'refreshAkuntansiRekening' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function updatingSelectedKelompokRekening()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function render()
    {
        $select_kelompok = AkuntansiKelompokRekening::get();

        $query = AkuntansiRekening::with(['akuntansi_kelompok_rekening']);

        if ($this->selectedKelompokRekening) {
            $query->where('akuntansi_kelompok_rekening_id', $this->selectedKelompokRekening);
        }

        // Filter berdasarkan pencarian nama
        if ($this->search) {
            $query->where('nama_rekening', 'like', '%' . $this->search . '%');
        }

        $rekening = $query->orderBy('kode_rekening', 'ASC')->get();

        return view('livewire.akuntansi-rekening.index', [
            'select_kelompok' => $select_kelompok,
            'rekening' => $rekening
        ]);
    }
}
