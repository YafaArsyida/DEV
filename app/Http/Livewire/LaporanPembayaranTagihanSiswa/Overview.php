<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use App\Models\DetailTransaksi;
use App\Models\DetailTransaksiTagihanSiswa;
use Carbon\Carbon;
use Livewire\Component;

class Overview extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $startDate = null;
    public $endDate = null;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'bulanUpdated' => 'updateBulan',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function updatedStartDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function render()
    {
        // Metode Pembayaran
        $methods = DetailTransaksiTagihanSiswa::join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
            ->join('ms_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_tagihan_siswa_id', '=', 'ms_tagihan_siswa.ms_tagihan_siswa_id')
            ->join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
            ->selectRaw("
                CASE
                    WHEN metode_pembayaran = 'Teller Tunai' THEN 'Teller Tunai'
                    WHEN metode_pembayaran = 'EduPay' THEN 'EduPay'
                    WHEN metode_pembayaran = 'Transfer ke Rekening Sekolah' THEN 'Transfer ke Rekening Sekolah'
                    ELSE 'Lainnya'
                END as metode,
                SUM(dt_transaksi_tagihan_siswa.jumlah_bayar) as total
            ")
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

                $query->whereBetween('ms_transaksi_tagihan_siswa.tanggal_transaksi', [$startDate, $endDate]);
            })
            ->groupBy('metode')
            ->orderBy('total', 'DESC')
            ->get()
            ->toArray();

        $totalMethods = array_sum(array_column($methods, 'total'));

        // Pembayaran per Kelas
        $classes = DetailTransaksiTagihanSiswa::join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
            ->join('ms_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_tagihan_siswa_id', '=', 'ms_tagihan_siswa.ms_tagihan_siswa_id')
            ->join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
            ->join('ms_kelas', 'ms_penempatan_siswa.ms_kelas_id', '=', 'ms_kelas.ms_kelas_id')
            ->selectRaw("
            ms_kelas.nama_kelas,
            SUM(dt_transaksi_tagihan_siswa.jumlah_bayar) as total
        ")
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

                $query->whereBetween('ms_transaksi_tagihan_siswa.tanggal_transaksi', [$startDate, $endDate]);
            })
            ->groupBy('ms_kelas.nama_kelas')
            ->orderBy('total', 'DESC')
            ->get()
            ->toArray();

        $totalClasses = array_sum(array_column($classes, 'total'));

        // Pembayaran per Bulan
        $months = DetailTransaksiTagihanSiswa::join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
            ->join('ms_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_tagihan_siswa_id', '=', 'ms_tagihan_siswa.ms_tagihan_siswa_id')
            ->join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
            ->selectRaw("
            DATE_FORMAT(ms_transaksi_tagihan_siswa.tanggal_transaksi, '%Y-%m') as bulan,
            SUM(dt_transaksi_tagihan_siswa.jumlah_bayar) as total
        ")
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

                $query->whereBetween('ms_transaksi_tagihan_siswa.tanggal_transaksi', [$startDate, $endDate]);
            })
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->get()
            ->toArray();

        $totalMonths = array_sum(array_column($months, 'total'));

        return view('livewire.laporan-pembayaran-tagihan-siswa.overview', [
            'methods' => $methods,
            'totalMethods' => $totalMethods,
            'classes' => $classes,
            'totalClasses' => $totalClasses,
            'months' => $months,
            'totalMonths' => $totalMonths,
        ]);
    }
}
