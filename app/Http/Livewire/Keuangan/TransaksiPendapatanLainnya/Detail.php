<?php

namespace App\Http\Livewire\Keuangan\TransaksiPendapatanLainnya;

use App\Models\TransaksiPendapatanLainnya;
use Livewire\Component;

class Detail extends Component
{
    public $transaksi;

    protected $listeners = [
        'loadDetailTransaksiPendapatanLainnya',
    ];

    public function loadDetailTransaksiPendapatanLainnya($id)
    {
        $transaksi = TransaksiPendapatanLainnya::with([
            'ms_pengguna',
            'akuntansi_rekening',

            'akuntansi_jurnal.ms_pengguna',
            'akuntansi_jurnal.akuntansi_jurnal_detail',

            'akuntansi_jurnal_reversal.ms_pengguna',
            'akuntansi_jurnal_reversal.akuntansi_jurnal_detail',
        ])->find($id);

        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }

        $this->transaksi = $transaksi;
    }

    public function render()
    {
        return view('livewire.keuangan.transaksi-pendapatan-lainnya.detail');
    }
}
