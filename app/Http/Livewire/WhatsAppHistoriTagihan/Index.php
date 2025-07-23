<?php

namespace App\Http\Livewire\WhatsAppHistoriTagihan;

use App\Models\WhatsAppHistoriTagihan;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;

    protected $listeners = [
        'UpdatePesanTransaksiTagihan',
        'parameterUpdated'
    ];

    public function UpdatePesanTransaksiTagihan()
    {
        $this->render();
    }

    public function parameterUpdated($jenjang)
    {
        $this->selectedJenjang = $jenjang;
    }

    public function render()
    {
        $pesans = null;

        if ($this->selectedJenjang) {
            $pesans = WhatsAppHistoriTagihan::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }
        return view('livewire.whats-app-histori-tagihan.index', [
            'selectedJenjang' => $this->selectedJenjang,
            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_histori_tagihan_id : null,
            'pesans' => $pesans,
        ]);
    }
}
