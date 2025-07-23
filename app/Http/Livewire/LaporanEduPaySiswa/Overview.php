<?php

namespace App\Http\Livewire\LaporanEduPaySiswa;

use App\Models\EduPaySiswa;
use App\Models\Kelas;
use Livewire\Component;

class Overview extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

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
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $query = EduPaySiswa::query()
            ->with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_edupay_siswa.ms_siswa_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_edupay_siswa.ms_penempatan_siswa_id')
            ->select('ms_edupay_siswa.*', 'ms_siswa.nama_siswa', 'ms_penempatan_siswa.ms_jenjang_id', 'ms_penempatan_siswa.ms_tahun_ajar_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        // Hitung total pemasukan, pengeluaran, dan saldo untuk setiap jenis transaksi
        $total_topup = (clone $query)
            ->where('jenis_transaksi', 'topup')
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

        $saldo = $total_topup + $total_topup_online + $total_pengembalian_dana - $total_penarikan - $total_pembayaran;

        // Return masing-masing variabel
        return view('livewire.laporan-edu-pay-siswa.overview', [
            'select_kelas' => $select_kelas,
            'total_topup' => $total_topup,
            'total_topup_online' => $total_topup_online,
            'total_pengembalian_dana' => $total_pengembalian_dana,
            'total_penarikan' => $total_penarikan,
            'total_pembayaran' => $total_pembayaran,
            'saldo' => $saldo,
        ]);
    }
}
