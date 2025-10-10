<?php

namespace App\Http\Livewire\WhatsAppTransaksiEduPay;

use App\Models\WhatsAppTransaksiEduPay;
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
            $pesans = WhatsAppTransaksiEduPay::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.whats-app-transaksi-edu-pay.index', [
            'selectedJenjang' => $this->selectedJenjang,
            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_transaksi_edupay_id : null,
            'pesans' => $pesans,
        ]);
    }
}
