<?php

namespace App\Http\Livewire\WhatsAppTransaksiTabungan;

use App\Models\WhatsAppTransaksiTabungan;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;

    protected $listeners = [
        'UpdatePesanTransaksiTabunganSiswa',
        'parameterUpdated' => 'updateParameters',
    ];

    public function UpdatePesanTransaksiTabunganSiswa()
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
            $pesans = WhatsAppTransaksiTabungan::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.whats-app-transaksi-tabungan.index', [
            'selectedJenjang' => $this->selectedJenjang,
            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_transaksi_tabungan_id : null,
            'pesans' => $pesans,
        ]);
    }
}
