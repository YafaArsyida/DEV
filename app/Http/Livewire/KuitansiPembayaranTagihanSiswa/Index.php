<?php

namespace App\Http\Livewire\KuitansiPembayaranTagihanSiswa;

use App\Models\KuitansiPembayaran;
use App\Models\KuitansiPembayaranTagihanSiswa;
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
            $kuitansi = KuitansiPembayaranTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.kuitansi-pembayaran-tagihan-siswa.index', compact('kuitansi'));
    }
}
