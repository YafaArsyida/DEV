<?php

namespace App\Http\Livewire\KuitansiPembayaran;

use App\Models\KuitansiPembayaran;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang;

    protected $listeners = ['refreshKuitansi'];

    public function refreshKuitansi($ms_jenjang_id)
    {
        // Jika ada logika lain yang diperlukan untuk merefresh, tambahkan di sini.
        $this->emitSelf('render');
        $this->selectedJenjang = $ms_jenjang_id;
    }

    public function render()
    {
        $kuitansi = null;

        if ($this->selectedJenjang) {
            $kuitansi = KuitansiPembayaran::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.kuitansi-pembayaran.index', compact('kuitansi'));
    }
}
