<?php

namespace App\Http\Livewire\KuitansiTransaksiTabungan;

use App\Models\KuitansiTransaksiTabungan;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang;

    protected $listeners = ['refreshKuitansiTabungan'];

    public function refreshKuitansiTabungan($ms_jenjang_id)
    {
        // Jika ada logika lain yang diperlukan untuk merefresh, tambahkan di sini.
        $this->emitSelf('render');
        $this->selectedJenjang = $ms_jenjang_id;
    }

    public function render()
    {
        $kuitansi = null;

        if ($this->selectedJenjang) {
            $kuitansi = KuitansiTransaksiTabungan::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }
        return view('livewire.kuitansi-transaksi-tabungan.index');
    }
}
