<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Http\Controllers\HelperController;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use App\Models\TransaksiEduPay;

class LaporanEduPaySiswa extends Controller
{
    public function index()
    {
        return view('LAPORAN.edupay-siswa.v_index');
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

        $pemasukanJenis = ['topup tunai', 'topup online', 'pengembalian dana'];
        $pengeluaranJenis = ['penarikan', 'pembayaran', 'kantin'];

        // Ambil data transaksi
        $query = TransaksiEduPay::with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa.ms_kelas'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_transaksi_edupay.user_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_transaksi_edupay.ms_penempatan_siswa_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $selectedTahunAjar);

        if ($kelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $kelas);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if (!empty($jenisTransaksi)) {
            $query->whereIn('jenis_transaksi', $jenisTransaksi);
        }

        $laporan = $query->orderBy('tanggal', 'ASC')->get();

        $totalPemasukan = $laporan->whereIn('jenis_transaksi', $pemasukanJenis)->sum('nominal');
        $totalPengeluaran = $laporan->whereIn('jenis_transaksi', $pengeluaranJenis)->sum('nominal');
        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        // === CETAK PDF ===
        $judul = 'Laporan Transaksi EduPay Siswa';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

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
                    <th width="15%">Pemasukan</th>
                    <th width="15%">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($laporan as $item) {
            $html .= '<tr>
                <td align="center">' . $no++ . '.</td>
                <td>' . HelperController::formatTanggalIndonesia($item->tanggal) . '</td>
                <td>' . ucfirst($item->ms_siswa->nama_siswa) . '</td>
                <td>' . ($item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-') . '</td>
                <td>' . ucfirst($item->jenis_transaksi) . '</td>
                <td>' . ($item->ms_pengguna->nama ?? '-') . '</td>
                <td align="right">' . (in_array($item->jenis_transaksi, $pemasukanJenis) ? 'Rp' . number_format($item->nominal, 0, ',', '.') : '-') . '</td>
                <td align="right">' . (in_array($item->jenis_transaksi, $pengeluaranJenis) ? 'Rp' . number_format($item->nominal, 0, ',', '.') : '-') . '</td>
            </tr>';
        }
        $html .= '
            </tbody>
            <tfoot>
                <tr style="font-weight:bold; background:#fafafa;">
                    <td colspan="6" align="right">Total Pemasukan</td>
                    <td colspan="2" align="right">Rp' . number_format($totalPemasukan, 0, ',', '.') . '</td>
                </tr>
                <tr style="font-weight:bold; background:#fafafa;">
                    <td colspan="6" align="right">Total Pengeluaran</td>
                    <td colspan="2" align="right">Rp' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
                </tr>
                <tr style="font-weight:bold; background:#e8f4ff;">
                    <td colspan="6" align="right">Total Saldo</td>
                    <td colspan="2" align="right">Rp' . number_format($totalSaldo, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_edupay_siswa.pdf', 'I');
    }
}
