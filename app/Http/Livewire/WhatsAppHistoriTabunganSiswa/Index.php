<?php

namespace App\Http\Livewire\WhatsAppHistoriTabunganSiswa;

use App\Models\WhatsAppHistoriTabunganSiswa;
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
            $pesans = WhatsAppHistoriTabunganSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.whats-app-histori-tabungan-siswa.index', [
            'selectedJenjang' => $this->selectedJenjang,
            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_histori_tabungan_siswa_id : null,
            'pesans' => $pesans,
        ]);
    }
}
