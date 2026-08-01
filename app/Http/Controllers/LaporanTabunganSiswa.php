<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use App\Models\TransaksiTabungan;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Http\Controllers\HelperController;
use App\Models\PenempatanSiswa;
use App\Models\SaldoTabungan;

class LaporanTabunganSiswa extends Controller
{
    public function index()
    {
        return view('LAPORAN.tabungan-siswa.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $kelas = $request->kelas;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $jenisTransaksi = $request->jenis_transaksi;

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

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
            ->where('ms_penempatan_siswa.ms_jenjang_id', $selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $selectedTahunAjar)
            ->where('ms_transaksi_tabungan.user_type', 'siswa')
            ->orderBy('tanggal', 'ASC');

        if ($kelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $kelas);
        }

        if ($jenisTransaksi) {
            $query->whereIn('jenis_transaksi', $jenisTransaksi);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $laporan = $query->get();

        $totalKredit = (clone $query)->where('jenis_transaksi', 'setoran')->sum('nominal');
        $totalDebit = (clone $query)->where('jenis_transaksi', 'penarikan')->sum('nominal');
        $totalSaldo = $totalKredit - $totalDebit;


        $judul = 'Laporan Transaksi Tabungan Siswa';
        $yayasan = 'Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start_date && $request->end_date) {
            $periode = 'Periode ' . HelperController::formatTanggalIndonesia($request->start_date, 'F Y') .
                ' sampai ' . HelperController::formatTanggalIndonesia($request->end_date, 'F Y');
        } else {
            $periode = 'Semua Periode';
        }

        // Mulai PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage('L');

        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $yayasan, 0, 1, 'C');
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 9);
        $pdf::setCellHeightRatio(1.2);

        // Tabel
        $html = '
        <table border="0.5" cellpadding="2">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="4%">No</th>
                    <th width="14%">Tanggal</th>
                    <th width="20%">Siswa</th>
                    <th width="12%">Kelas</th>
                    <th width="10%">Transaksi</th>
                    <th width="10%">Petugas</th>
                    <th width="15%">Kredit</th>
                    <th width="15%">Debit</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($laporan as $item) {
            $html .= '<tr>
            <td width="4%" align="center">' . $no++ . '</td>
            <td width="14%">' . HelperController::formatTanggalIndonesia($item->tanggal, 'd F Y') . '</td>
            <td width="20%">' . $item->ms_siswa->nama_siswa . '</td>
            <td width="12%">' . ($item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-') . '</td>
            <td width="10%">RP' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->jenis_transaksi) . '</i><br><small>' . $item->deskripsi . '</small></td>
            <td width="10%">' . ($item->ms_pengguna->nama ?? '-') . '</td>
            <td width="15%" align="right">' . ($item->jenis_transaksi === 'setoran' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-') . '</td>
            <td width="15%" align="right">' . ($item->jenis_transaksi === 'penarikan' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-') . '</td>
        </tr>';
        }

        // Footer
        $html .= '
            <tr style="background-color:#f2f2f2;">
                <td colspan="6" align="right"><b>Total Kredit</b></td>
                <td colspan="2" align="right"><b>RP' . number_format($totalKredit, 0, ',', '.') . '</b></td>
            </tr>
            <tr style="background-color:#f2f2f2;">
                <td colspan="6" align="right"><b>Total Debit</b></td>
                <td colspan="2" align="right"><b>RP' . number_format($totalDebit, 0, ',', '.') . '</b></td>
            </tr>
            <tr style="background-color:#d9edf7;">
                <td colspan="6" align="right"><b>Total Saldo</b></td>
                <td colspan="2" align="right"><b>RP' . number_format($totalSaldo, 0, ',', '.') . '</b></td>
            </tr>
        ';

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_tabungan_siswa.pdf', 'I');
    }

    public function cetakSaldoPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $kelas = $request->kelas;

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $query = PenempatanSiswa::with(['ms_siswa.ms_saldo_tabungan', 'ms_kelas'])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar);

        if ($kelas) {
            $query->where('ms_kelas_id', $kelas);
        }

        $query->whereHas('ms_siswa.ms_saldo_tabungan', function ($q) {
            $q->where('saldo_tabungan', '!=', 0);
        });

        $laporan = $query->get();

        $totalSaldo = $laporan->sum(function ($item) {
            return $item->ms_siswa->ms_saldo_tabungan->saldo_tabungan ?? 0;
        });

        $judul = 'Laporan Tabungan Siswa';
        $yayasan = 'Unit ' . ($jenjang->nama_jenjang ?? '-');

        // Mulai PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage('P');

        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $yayasan, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 10);
        $pdf::setCellHeightRatio(1.2);

        $html = '
        <table border="0.5" cellpadding="4">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="6%">No</th>
                    <th width="54%">Siswa</th>
                    <th width="20%">Kelas</th>
                    <th width="20%">Saldo</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($laporan as $item) {
            $saldo = $item->ms_siswa->ms_saldo_tabungan->saldo_tabungan ?? 0;
            $html .= '<tr>
                <td width="6%" align="center">' . $no++ . '</td>
                <td width="54%">' . ($item->ms_siswa->nama_siswa ?? '-') . '</td>
                <td width="20%">' . ($item->ms_kelas->nama_kelas ?? '-') . '</td>
                <td width="20%" align="right">RP' . number_format($saldo, 0, ',', '.') . '</td>
            </tr>';
        }

        $html .= '<tr style="background-color:#d9edf7;">
                <td colspan="3" align="right"><b>Total Saldo</b></td>
                <td align="right"><b>RP' . number_format($totalSaldo, 0, ',', '.') . '</b></td>
            </tr>';

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_saldo_tabungan_siswa.pdf', 'I');
    }
}
