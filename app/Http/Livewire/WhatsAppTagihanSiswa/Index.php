<?php

namespace App\Http\Livewire\WhatsAppTagihanSiswa;

use App\Models\SuratTagihan;
use App\Models\WhatsAppTagihanSiswa;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;

    protected $listeners = [
        'UpdatePesanTagihanSiswa',
        'parameterUpdated' => 'updateParameters',
    ];

    public function UpdatePesanTagihanSiswa()
    {
        $this->render();
    }

    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;
    }

    public function render()
    {
        $surat = SuratTagihan::first();

        $pesans = null;

        if ($this->selectedJenjang) {
            $pesans = WhatsAppTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.whats-app-tagihan-siswa.index', [
            'surat' => $surat,
            'selectedJenjang' => $this->selectedJenjang,
            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_tagihan_siswa_id : null,
            'pesans' => $pesans,
        ]);
    }
}
