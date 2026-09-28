<?php

namespace App\Http\Livewire\Keuangan\KuitansiTransaksiEduPay;

use App\Models\KuitansiTransaksiEduPay;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang;

    protected $listeners = ['kuitansiEduPay'];

    public function kuitansiEduPay($ms_jenjang_id)
    {
        // Jika ada logika lain yang diperlukan untuk merefresh, tambahkan di sini.
        $this->emitSelf('render');
        $this->selectedJenjang = $ms_jenjang_id;
    }

    public function render()
    {
        $kuitansi = null;

        if ($this->selectedJenjang) {
                $kuitansi = KuitansiTransaksiEduPay::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.keuangan.kuitansi-transaksi-edu-pay.index', compact('kuitansi'));
    }
}
