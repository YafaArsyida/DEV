<?php

namespace App\Http\Livewire\LaporanEduPayPegawai;

use App\Models\Jabatan;
use App\Models\TransaksiEduPay;
use Livewire\Component;

class Overview extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedJabatan = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshSaldoEduPay'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function refreshSaldoEduPay()
    {
        $this->emitSelf('$refresh'); //ringan
    }
    public function render()
    {
        $select_jabatan = Jabatan::get();

        // Query dasar: hanya transaksi pegawai
        $query = TransaksiEduPay::with(['ms_pegawai.ms_jabatan', 'ms_pengguna'])
            ->where('user_type', 'pegawai');

        // Filter berdasarkan jenjang
        if ($this->selectedJenjang) {
            $query->whereHas('ms_pegawai', function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang);
            });
        }

        // Filter berdasarkan jabatan (bukan kelas)
        if ($this->selectedJabatan) {
            $query->whereHas('ms_pegawai', function ($q) {
                $q->where('ms_jabatan_id', $this->selectedJabatan);
            });
        }

        // Hitung total pemasukan, pengeluaran, dan saldo untuk setiap jenis transaksi
        $total_topup_tunai = (clone $query)
            ->where('jenis_transaksi', 'topup tunai')
            ->sum('nominal');

        $total_topup_online = (clone $query)
            ->where('jenis_transaksi', 'topup online')
            ->sum('nominal');

        $total_pengembalian_dana = (clone $query)
            ->where('jenis_transaksi', 'pengembalian dana')
            ->sum('nominal');

        $total_penarikan = (clone $query)
            ->where('jenis_transaksi', 'penarikan')
            ->sum('nominal');

        $total_pembayaran = (clone $query)
            ->where('jenis_transaksi', 'pembayaran')
            ->sum('nominal');

        $total_kantin = (clone $query)
            ->where('jenis_transaksi', 'kantin')
            ->sum('nominal');

        $saldo = $total_topup_tunai + $total_topup_online + $total_pengembalian_dana - $total_penarikan - $total_pembayaran - $total_kantin;

        return view('livewire.laporan-edu-pay-pegawai.overview', [
            'select_jabatan' => $select_jabatan,
            'total_topup_tunai' => $total_topup_tunai,
            'total_topup_online' => $total_topup_online,
            'total_pengembalian_dana' => $total_pengembalian_dana,
            'total_penarikan' => $total_penarikan,
            'total_pembayaran' => $total_pembayaran,
            'total_kantin' => $total_kantin,
            'saldo' => $saldo,
        ]);
    }
}
