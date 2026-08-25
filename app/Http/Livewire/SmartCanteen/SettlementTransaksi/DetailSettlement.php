<?php

namespace App\Http\Livewire\SmartCanteen\SettlementTransaksi;

use App\Models\SmartCanteen\SettlementSmartCanteen;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use Livewire\Component;

class DetailSettlement extends Component
{
    protected $listeners = ['openDetailSettlement' => 'loadDetail'];

    public $settlement;      // data settlement utama
    public $detailList = [];

    public function loadDetail($settlementId)
    {
        // ambil data settlement
        $this->settlement = SettlementSmartCanteen::with('ms_pengguna')
            ->where('ms_settlement_kantin_id', $settlementId)
            ->first();

       $this->detailList = TransaksiSmartCanteen::with([
            'ms_siswa',
            'ms_pegawai',
            'dt_transaksi_kantin.ms_produk_kantin',
        ])
            ->where('ms_settlement_kantin_id', $settlementId)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.smart-canteen.settlement-transaksi.detail-settlement');
    }
}
