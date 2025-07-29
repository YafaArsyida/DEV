<?php

namespace App\Http\Livewire\WhatsAppPembayaranTagihanSiswa;

use App\Models\WhatsAppHistoriTagihan;
use App\Models\WhatsAppPembayaranTagihanSiswa;
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
            $pesans = WhatsAppPembayaranTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }
        return view('livewire.whats-app-pembayaran-tagihan-siswa.index', [
            'selectedJenjang' => $this->selectedJenjang,
            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_pembayaran_tagihan_siswa_id : null,
            'pesans' => $pesans,
        ]);
    }
}
