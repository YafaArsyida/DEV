<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\TransaksiTagihanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $transaksi;

    protected $listeners = [
        'loadDetailTransaksiTagihan',
    ];

    public function loadDetailTransaksiTagihan($id)
    {
        $transaksi = TransaksiTagihanSiswa::with([
            'ms_pengguna',

            // Siswa & penempatan
            'ms_penempatan_siswa.ms_siswa',
            'ms_penempatan_siswa.ms_kelas',
            'ms_penempatan_siswa.ms_jenjang',
            'ms_penempatan_siswa.ms_tahun_ajar',

            // Detail transaksi → tagihan → jenis tagihan
            'dt_transaksi_tagihan_siswa.ms_tagihan_siswa.ms_jenis_tagihan_siswa',


            // Jurnal asli
            'akuntansi_jurnal.ms_pengguna',
            'akuntansi_jurnal.akuntansi_jurnal_detail.akuntansi_rekening',

            // Jurnal reversal
            'akuntansi_jurnal_reversal.ms_pengguna',
            'akuntansi_jurnal_reversal.akuntansi_jurnal_detail.akuntansi_rekening',
        ])->find($id);

        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }

        $this->transaksi = $transaksi;
    }
    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.detail');
    }
}
