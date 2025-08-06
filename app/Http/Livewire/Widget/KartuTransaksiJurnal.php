<?php

namespace App\Http\Livewire\Widget;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use App\Models\PendapatanLainnya;
use Livewire\Component;

class KartuTransaksiJurnal extends Component
{
    public function render()
    {
        return view('livewire.widget.kartu-transaksi-jurnal');
    }
}
