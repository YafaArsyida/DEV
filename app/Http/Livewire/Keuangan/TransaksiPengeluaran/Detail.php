<?php

namespace App\Http\Livewire\Keuangan\TransaksiPengeluaran;

use App\Models\TransaksiPengeluaran;
use Livewire\Component;

class Detail extends Component
{
    public $transaksi;

    protected $listeners = [
        'loadDetailTransaksiPengeluaran',
    ];

    public function loadDetailTransaksiPengeluaran($id)
    {
        $transaksi = TransaksiPengeluaran::with([
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
        return view('livewire.keuangan.transaksi-pengeluaran.detail');
    }
}
