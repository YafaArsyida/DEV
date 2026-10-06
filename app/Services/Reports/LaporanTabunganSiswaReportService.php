<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\Jenjang;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;
use App\Models\TransaksiTabungan;
use Illuminate\Http\Request;

class LaporanTabunganSiswaReportService
{
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

        $query = TransaksiTabungan::with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa.ms_kelas'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_transaksi_tabungan.user_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_transaksi_tabungan.ms_penempatan_siswa_id')
            ->select(
                'ms_transaksi_tabungan.*',
                'ms_siswa.nama_siswa',
                'ms_penempatan_siswa.ms_jenjang_id',
                'ms_penempatan_siswa.ms_tahun_ajar_id',
                'ms_penempatan_siswa.ms_kelas_id'
            )
            ->where('ms_transaksi_tabungan.status_transaksi', '!=', 'dibatalkan')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $selectedTahunAjar)
            ->where('ms_transaksi_tabungan.user_type', 'siswa')
            ->orderBy('ms_transaksi_tabungan.tanggal', 'ASC');

        if ($kelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $kelas);
        }

        if ($jenisTransaksi) {
            $query->whereIn('ms_transaksi_tabungan.jenis_transaksi', $jenisTransaksi);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('ms_transaksi_tabungan.tanggal', [$startDate, $endDate]);
        }

        $laporan = $query->get();
        $totalKredit = (clone $query)
            ->where('ms_transaksi_tabungan.jenis_transaksi', 'setoran')
            ->sum('ms_transaksi_tabungan.nominal');
        $totalDebit = (clone $query)
            ->where('ms_transaksi_tabungan.jenis_transaksi', 'penarikan')
            ->sum('ms_transaksi_tabungan.nominal');

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'tanggal' => HelperController::formatTanggalIndonesia($item->tanggal, 'd F Y'),
                'siswa' => $item->ms_siswa->nama_siswa ?? '-',
                'kelas' => $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-',
                'jenisTransaksi' => ucfirst($item->jenis_transaksi),
                'deskripsi' => $item->deskripsi ?? '-',
                'petugas' => $item->ms_pengguna->nama ?? '-',
                'kredit' => $item->jenis_transaksi === 'setoran' ? $this->formatRupiah($item->nominal) : '-',
                'debit' => $item->jenis_transaksi === 'penarikan' ? $this->formatRupiah($item->nominal) : '-',
            ];
        });

        $periode = $startDate && $endDate
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Transaksi Tabungan Siswa',
            'yayasan' => 'Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'periode' => $periode,
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahTransaksi' => $laporan->count(),
            'totalKredit' => $this->formatRupiah($totalKredit),
            'totalDebit' => $this->formatRupiah($totalDebit),
            'totalSaldo' => $this->formatRupiah($totalKredit - $totalDebit),
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

        $query = PenempatanSiswa::with(['ms_siswa.ms_saldo_tabungan', 'ms_kelas'])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar);

        if ($kelas) {
            $query->where('ms_kelas_id', $kelas);
        }

        $query->whereHas('ms_siswa.ms_saldo_tabungan', function ($query) {
            $query->where('saldo_tabungan', '!=', 0);
        });

        $laporan = $query->get();
        $totalSaldo = $laporan->sum(function ($item) {
            return $item->ms_siswa?->ms_saldo_tabungan?->saldo_tabungan ?? 0;
        });

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'siswa' => $item->ms_siswa->nama_siswa ?? '-',
                'kelas' => $item->ms_kelas->nama_kelas ?? '-',
                'saldo' => $this->formatRupiah($item->ms_siswa?->ms_saldo_tabungan?->saldo_tabungan ?? 0),
            ];
        });

        return [
            'judul' => 'Laporan Tabungan Siswa',
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
