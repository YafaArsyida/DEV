<?php

namespace App\Http\Livewire\SuratTagihanSiswa;

use App\Models\SuratTagihan;
use App\Models\SuratTagihanSiswa;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang;

    protected $listeners = ['refreshSurat'];

    public function refreshSurat($ms_jenjang_id)
    {
        // Jika ada logika lain yang diperlukan untuk merefresh, tambahkan di sini.
        $this->emitSelf('render');
        $this->selectedJenjang = $ms_jenjang_id;
    }

    public function render()
    {
        $surat = null;

        if ($this->selectedJenjang) {
            $surat = SuratTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.surat-tagihan-siswa.index', compact('surat'));
    }
}
