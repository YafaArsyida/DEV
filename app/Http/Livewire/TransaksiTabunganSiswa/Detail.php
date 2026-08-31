<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\TransaksiTabungan;
use Livewire\Component;

class Detail extends Component
{
    public $transaksi;

    protected $listeners = [
        'loadDetailTransaksiTabungan',
    ];

    public function loadDetailTransaksiTabungan($id)
    {
        $transaksi = TransaksiTabungan::with([
            'ms_pengguna',

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
        return view('livewire.transaksi-tabungan-siswa.detail');
    }
}
