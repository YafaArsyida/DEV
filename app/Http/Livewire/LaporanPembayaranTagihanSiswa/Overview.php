<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use App\Models\DetailTransaksi;
use App\Models\DetailTransaksiTagihanSiswa;
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

    public function resetTanggal()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function showExportOverviewPembayaran()
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
            ->when($this->selectedBulan, function ($query) {
                $query->whereMonth('ms_transaksi_tagihan_siswa.tanggal_transaksi', $this->selectedBulan);
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
            ->when($this->selectedBulan, function ($query) {
                $query->whereMonth('ms_transaksi_tagihan_siswa.tanggal_transaksi', $this->selectedBulan);
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
            ->when($this->selectedBulan, function ($query) {
                $query->whereMonth('ms_transaksi_tagihan_siswa.tanggal_transaksi', $this->selectedBulan);
            })
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->get()
            ->toArray();

        $totalMonths = array_sum(array_column($months, 'total'));

        $this->emit('prepareExportOverview', $methods, $classes, $months, $totalClasses);
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
                $query->whereBetween('ms_transaksi_tagihan_siswa.tanggal_transaksi', [$this->startDate, $this->endDate]);
            })
            // ->when($this->selectedBulan, function ($query) {
            //     $query->whereMonth('ms_transaksi_tagihan_siswa.tanggal_transaksi', $this->selectedBulan);
            // })
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
            // ->when($this->selectedBulan, function ($query) {
            //     $query->whereMonth('ms_transaksi_tagihan_siswa.tanggal_transaksi', $this->selectedBulan);
            // })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('ms_transaksi_tagihan_siswa.tanggal_transaksi', [$this->startDate, $this->endDate]);
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
            // ->when($this->selectedBulan, function ($query) {
            //     $query->whereMonth('ms_transaksi_tagihan_siswa.tanggal_transaksi', $this->selectedBulan);
            // })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('ms_transaksi_tagihan_siswa.tanggal_transaksi', [$this->startDate, $this->endDate]);
            })
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->get()
            ->toArray();

        $totalMonths = array_sum(array_column($months, 'total'));

        // $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);

        return view('livewire.laporan-pembayaran-tagihan-siswa.overview', [
            // 'select_bulan' => $select_bulan,
            'methods' => $methods,
            'totalMethods' => $totalMethods,
            'classes' => $classes,
            'totalClasses' => $totalClasses,
            'months' => $months,
            'totalMonths' => $totalMonths,
        ]);
    }
}
