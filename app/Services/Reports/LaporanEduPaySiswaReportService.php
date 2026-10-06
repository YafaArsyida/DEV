<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\Jenjang;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;
use App\Models\TransaksiEduPay;
use Illuminate\Http\Request;

class LaporanEduPaySiswaReportService
{
    private const PEMASUKAN_JENIS = ['topup tunai', 'topup online', 'pengembalian dana'];

    private const PENGELUARAN_JENIS = ['penarikan', 'pembayaran', 'kantin'];

    public function getTransactionData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $kelas = $request->input('kelas');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenisTransaksi = $request->input('jenis_transaksi', []);

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $query = TransaksiEduPay::with([
            'ms_siswa.ms_penempatan_siswa' => function ($query) use ($selectedJenjang, $selectedTahunAjar) {
                $query->with('ms_kelas')
                    ->where('ms_jenjang_id', $selectedJenjang)
                    ->where('ms_tahun_ajar_id', $selectedTahunAjar);
            },
            'ms_pengguna',
        ])
            ->where('ms_transaksi_edupay.user_type', 'siswa')
            ->where('ms_transaksi_edupay.status_transaksi', '!=', 'dibatalkan')
            ->whereHas('ms_siswa.ms_penempatan_siswa', function ($query) use ($selectedJenjang, $selectedTahunAjar) {
                $query->where('ms_jenjang_id', $selectedJenjang)
                    ->where('ms_tahun_ajar_id', $selectedTahunAjar);
            });

        if ($kelas) {
            $query->whereHas('ms_siswa.ms_penempatan_siswa', function ($query) use ($selectedJenjang, $selectedTahunAjar, $kelas) {
                $query->where('ms_jenjang_id', $selectedJenjang)
                    ->where('ms_tahun_ajar_id', $selectedTahunAjar)
                    ->where('ms_kelas_id', $kelas);
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('ms_transaksi_edupay.tanggal', [$startDate, $endDate]);
        }

        if ($jenisTransaksi) {
            $query->whereIn('ms_transaksi_edupay.jenis_transaksi', $jenisTransaksi);
        }

        $laporan = $query->orderBy('ms_transaksi_edupay.tanggal', 'ASC')->get();
        $totalPemasukan = $laporan
            ->whereIn('jenis_transaksi', self::PEMASUKAN_JENIS)
            ->sum('nominal');
        $totalPengeluaran = $laporan
            ->whereIn('jenis_transaksi', self::PENGELUARAN_JENIS)
            ->sum('nominal');

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'tanggal' => HelperController::formatTanggalIndonesia($item->tanggal),
                'siswa' => ucfirst($item->ms_siswa->nama_siswa ?? '-'),
                'kelas' => $item->ms_siswa->ms_penempatan_siswa->first()?->ms_kelas->nama_kelas ?? '-',
                'jenisTransaksi' => ucfirst($item->jenis_transaksi),
                'petugas' => $item->ms_pengguna->nama ?? '-',
                'pemasukan' => in_array($item->jenis_transaksi, self::PEMASUKAN_JENIS, true)
                    ? $this->formatRupiah($item->nominal)
                    : '-',
                'pengeluaran' => in_array($item->jenis_transaksi, self::PENGELUARAN_JENIS, true)
                    ? $this->formatRupiah($item->nominal)
                    : '-',
            ];
        });

        $periode = $startDate && $endDate
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Transaksi EduPay Siswa',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'periode' => $periode,
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahTransaksi' => $laporan->count(),
            'totalPemasukan' => $this->formatRupiah($totalPemasukan),
            'totalPengeluaran' => $this->formatRupiah($totalPengeluaran),
            'totalSaldo' => $this->formatRupiah($totalPemasukan - $totalPengeluaran),
            'data' => $data,
        ];
    }

    public function getSaldoData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $kelas = $request->input('kelas');

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $query = PenempatanSiswa::with(['ms_siswa.ms_saldo_edupay', 'ms_kelas'])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar);

        if ($kelas) {
            $query->where('ms_kelas_id', $kelas);
        }

        $query->whereHas('ms_siswa.ms_saldo_edupay', function ($query) {
            $query->where('saldo_edupay', '!=', 0);
        });

        $laporan = $query->get();
        $totalSaldo = $laporan->sum(function ($item) {
            return $item->ms_siswa?->ms_saldo_edupay?->saldo_edupay ?? 0;
        });

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'siswa' => $item->ms_siswa->nama_siswa ?? '-',
                'kelas' => $item->ms_kelas->nama_kelas ?? '-',
                'saldo' => $this->formatRupiah($item->ms_siswa?->ms_saldo_edupay?->saldo_edupay ?? 0),
            ];
        });

        return [
            'judul' => 'Laporan EduPay Siswa',
            'yayasan' => 'Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahSiswa' => $laporan->count(),
            'totalSaldo' => $this->formatRupiah($totalSaldo),
            'data' => $data,
        ];
    }

    private function formatRupiah($nominal): string
    {
        return number_format($nominal, 0, ',', '.');
    }
}
