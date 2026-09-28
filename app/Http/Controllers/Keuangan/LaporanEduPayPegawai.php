<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\Pegawai;
use App\Models\SaldoEduPay;
use App\Models\TahunAjar;
use App\Models\TransaksiEduPay;
use Illuminate\Http\Request;

use Elibyy\TCPDF\Facades\TCPDF;

class LaporanEduPayPegawai extends Controller
{
    public function index()
    {
        return view('keuangan.laporan.edupay-pegawai');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $selectedJabatan = $request->jabatan;
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

        // Ambil data transaksi pegawai
        $query = TransaksiEduPay::query()
            ->with(['ms_pegawai', 'ms_pengguna', 'ms_pegawai.ms_jabatan'])
            ->join('ms_pegawai', 'ms_pegawai.ms_pegawai_id', '=', 'ms_transaksi_edupay.user_id')
            ->leftJoin('ms_jabatan', 'ms_jabatan.ms_jabatan_id', '=', 'ms_pegawai.ms_jabatan_id')
            ->where('ms_transaksi_edupay.user_type', 'pegawai')
            ->where('ms_pegawai.ms_jenjang_id', $selectedJenjang);

        // Filter jabatan
        if ($selectedJabatan) {
            $query->where('ms_pegawai.ms_jabatan_id', $selectedJabatan);
        }

        // Filter tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Filter jenis transaksi
        if (!empty($jenisTransaksi)) {
            $query->whereIn('jenis_transaksi', $jenisTransaksi);
        }

        $laporan = $query
            ->select(
                'ms_transaksi_edupay.*',
                'ms_pegawai.nama_pegawai',
                'ms_pegawai.ms_jenjang_id',
                'ms_pegawai.ms_jabatan_id',
                'ms_jabatan.nama_jabatan'
            )
            ->orderBy('tanggal', 'ASC')
            ->get();

        $totalPemasukan = $laporan->whereIn('jenis_transaksi', $pemasukanJenis)->sum('nominal');
        $totalPengeluaran = $laporan->whereIn('jenis_transaksi', $pengeluaranJenis)->sum('nominal');
        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        // === CETAK PDF ===
        $judul = 'Laporan Transaksi EduPay Pegawai';
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
                    <th width="20%">Pegawai</th>
                    <th width="12%">Jabatan</th>
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
                <td width="4%" align="center">' . $no++ . '.</td>
                <td width="14%">' . HelperController::formatTanggalIndonesia($item->tanggal) . '</td>
                <td width="20%">' . ucfirst($item->nama_pegawai ?? '-') . '</td>
                <td width="12%">' . ($item->nama_jabatan ?? '-') . '</td>
                <td width="10%">' . ucfirst($item->jenis_transaksi) . '</td>
                <td width="10%">' . ($item->ms_pengguna->nama ?? '-') . '</td>
                <td width="15%" align="right">' . (in_array($item->jenis_transaksi, $pemasukanJenis) ? 'Rp' . number_format($item->nominal, 0, ',', '.') : '-') . '</td>
                <td width="15%" align="right">' . (in_array($item->jenis_transaksi, $pengeluaranJenis) ? 'Rp' . number_format($item->nominal, 0, ',', '.') : '-') . '</td>
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
        $pdf::Output('laporan_edupay_pegawai.pdf', 'I');
    }
 
    public function cetakSaldoPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedJabatan = $request->jabatan;

        // Validasi parameter wajib
        if (!$selectedJenjang) {
            return response()->json([
                'error' => 'Jenjang wajib dipilih'
            ], 400);
        }

        $jenjang = Jenjang::find($selectedJenjang);
        $jabatan = $selectedJabatan ? Jabatan::find($selectedJabatan): null;

        // Query pegawai
        $query = Pegawai::with([
            'ms_jabatan',
            'ms_jenjang',
            'ms_saldo_edupay',
        ])
            ->where('ms_jenjang_id', $selectedJenjang);

        // Filter jabatan
        if ($selectedJabatan) {
            $query->where('ms_jabatan_id', $selectedJabatan);
        }

        // Hanya pegawai yang memiliki saldo EduPay != 0
        $query->whereHas('ms_saldo_edupay', function ($q) {
            $q->where('saldo_edupay', '!=', 0)
            ->where('user_type', 'pegawai');
        });

        // Urutkan berdasarkan saldo terbesar
        $query->orderByDesc(
            SaldoEduPay::select('saldo_edupay')
                ->whereColumn(
                    'user_id',
                    'ms_pegawai.ms_pegawai_id'
                )
                ->where('user_type', 'pegawai')
                ->limit(1)
        );

        $laporan = $query->get();

        // Hitung total seluruh saldo
        $totalSaldo = $laporan->sum(function ($item) {
            return $item->ms_saldo_edupay->saldo_edupay ?? 0;
        });

        $judul = 'Laporan Saldo EduPay Pegawai';

        $unit = 'Unit ' . ($jenjang->nama_jenjang ?? '-');

        // Jika jabatan dipilih, tambahkan nama jabatan
        if ($jabatan) {
            $unit .= ' - ' . ($jabatan->nama_jabatan ?? '-');
        }

        // Mulai PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf::SetTitle($judul);
        $pdf::AddPage('P');

        // Judul
        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');

        // Unit / filter
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $unit, 0, 1, 'C');

        $pdf::Ln(3);

        $pdf::SetFont('times', '', 10);
        $pdf::setCellHeightRatio(1.2);

        $html = '
        <table border="0.5" cellpadding="4">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="6%">No</th>
                    <th width="44%">Pegawai</th>
                    <th width="25%">Jabatan</th>
                    <th width="25%">Saldo EduPay</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($laporan as $item) {

            $saldo = $item->ms_saldo_edupay->saldo_edupay ?? 0;

            $html .= '
                <tr>
                    <td width="6%" align="center">' . $no++ . '</td>
                    <td width="44%">
                        ' . htmlspecialchars(
                            $item->nama_pegawai ?? '-',
                            ENT_QUOTES,
                            'UTF-8'
                        ) . '
                    </td>

                    <td width="25%">
                        ' . htmlspecialchars(
                            $item->ms_jabatan->nama_jabatan ?? '-',
                            ENT_QUOTES,
                            'UTF-8'
                        ) . '
                    </td>
                    <td width="25%" align="right">RP' . number_format($saldo, 0, ',', '.') . '</td>
                </tr>';
        }

        // Total
        $html .= '<tr style="background-color:#d9edf7;">
                <td colspan="3" align="right"><b>Total Saldo</b></td>
                <td align="right"><b>RP' . number_format($totalSaldo, 0, ',', '.') . '</b></td>
            </tr>';

        $html .= '
            </tbody>
        </table>';

        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output('laporan_saldo_edupay_pegawai.pdf', 'I');
    }
}
