<?php

namespace App\Http\Livewire\Keuangan\LaporanEduPaySiswa;

use App\Models\Kelas;
use App\Models\TransaksiEduPay;
use Livewire\Component;

class Overview extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function render()
    {
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $query = TransaksiEduPay::query()
            ->where('ms_transaksi_edupay.user_type', 'siswa')
            ->where('ms_transaksi_edupay.status_transaksi', '!=', 'dibatalkan')
            ->whereHas('ms_siswa.ms_penempatan_siswa', function ($query) {
                $query->where('ms_jenjang_id', $this->selectedJenjang)
                    ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);
            })
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->whereHas('ms_siswa.ms_penempatan_siswa', function ($query) {
                $query->where('ms_jenjang_id', $this->selectedJenjang)
                    ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                    ->where('ms_kelas_id', $this->selectedKelas);
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

        // Return masing-masing variabel
        return view('livewire.keuangan.laporan-edu-pay-siswa.overview', [
            'select_kelas' => $select_kelas,
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
