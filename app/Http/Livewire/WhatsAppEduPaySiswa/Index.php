<?php

namespace App\Http\Livewire\WhatsAppEduPaySiswa;

use App\Models\WhatsAppEduPay;
use App\Models\WhatsAppEduPaySiswa;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;

    protected $listeners = [
        'UpdatePesanTransaksiEduPay',
        'parameterUpdated' => 'updateParameters',
    ];

    public function UpdatePesanTransaksiEduPay()
    {
        $this->render();
    }

    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;
    }

    public function render()
    {
        $pesans = null;

        if ($this->selectedJenjang) {
            $pesans = WhatsAppEduPaySiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.whats-app-edu-pay-siswa.index', [
            'selectedJenjang' => $this->selectedJenjang,
            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_edupay_id : null,
            'pesans' => $pesans,
        ]);
    }
}
