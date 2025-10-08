<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Carbon\Carbon;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class AkuntansiLaporanArusKas extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-arus-kas.v_index');
    }

    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $rekening = $request->rekening;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $akunKasBank = [11001, 11002];

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        // Ambil transaksi sesuai filter
        $transaksiJurnal = AkuntansiJurnalDetail::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_tahun_ajaran_id', $selectedTahunAjar)
            ->where('ms_jenjang_id', $selectedJenjang)
            ->when($rekening, function ($query) use ($rekening) {
                $query->where('kode_rekening', $rekening);
            }, function ($query) use ($akunKasBank) {
                $query->whereIn('kode_rekening', $akunKasBank);
            })
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                $end   = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

                $q->whereBetween('tanggal_transaksi', [$start, $end]);
            })
            ->orderBy('tanggal_transaksi')
            ->get();

        $totalDebit = $transaksiJurnal->where('posisi', 'debit')->sum('nominal');
        $totalKredit = $transaksiJurnal->where('posisi', 'kredit')->sum('nominal');

        // Hitung saldo awal
        $saldoAwal = 0;
        if ($startDate && $endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $saldoAwal = AkuntansiJurnalDetail::where('ms_tahun_ajaran_id', $selectedTahunAjar)
                ->where('ms_jenjang_id', $selectedJenjang)
                ->when($rekening, function ($query) use ($rekening) {
                    $query->where('kode_rekening', $rekening);
                }, function ($query) use ($akunKasBank) {
                    $query->whereIn('kode_rekening', $akunKasBank);
                })
                ->where('tanggal_transaksi', '<', $startDate)
                ->selectRaw("
                SUM(CASE WHEN posisi = 'debit' THEN nominal ELSE 0 END) -
                SUM(CASE WHEN posisi = 'kredit' THEN nominal ELSE 0 END) as saldo
            ")
                ->value('saldo');
        }

        $saldoAkhir = $saldoAwal + ($totalDebit - $totalKredit);

        // ========================== PDF ==========================
        $judul = 'Laporan Arus Kas';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start_date && $request->end_date) {
            $periode = 'Periode ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->start_date, 'd F Y') .
                ' sampai ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->end_date, 'd F Y');
        } else {
            $periode = 'Semua Periode';
        }

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage('L');

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $yayasan, 0, 1, 'C');
        $pdf::SetFont('times', '', 10);
        $pdf::MultiCell(0, 6, ($jenjang->deskripsi ?? '-'), 0, 'C');
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 9);
        $pdf::setCellHeightRatio(1.2);

        // Header tabel
        $html = '<table border="0.5" cellpadding="4">
        <thead>
            <tr style="background-color:#f2f2f2;">
                <th width="3%" align="center"><b>No</b></th>
                <th width="10%"><b>Tanggal</b></th>
                <th width="10%"><b>Akun</b></th>
                <th width="10%"><b>Petugas</b></th>
                <th width="47%"><b>Deskripsi</b></th>
                <th width="10%" align="center"><b>Pemasukan</b></th>
                <th width="10%" align="center"><b>Pengeluaran</b></th>
            </tr>
        </thead>
        <tbody>';

        $no = 1;
        foreach ($transaksiJurnal as $trx) {
            $html .= '<tr>
            <td width="3%" align="center">' . $no++ . '.</td>
            <td width="10%">' . HelperController::formatTanggalIndonesia($trx->tanggal_transaksi, 'd F Y') . '</td>
            <td width="10%">' . $trx->akuntansi_rekening->nama_rekening . '</td>
            <td width="10%">' . $trx->ms_pengguna->nama . '</td>
            <td width="47%">' . $trx->deskripsi . '</td>
            <td width="10%" align="center">' . ($trx->posisi == 'debit' ? 'RP' . number_format($trx->nominal, 0, ',', '.') : '-') . '</td>
            <td width="10%" align="center">' . ($trx->posisi == 'kredit' ? 'RP' . number_format($trx->nominal, 0, ',', '.') : '-') . '</td>
        </tr>';
        }

        // Total, saldo awal, saldo akhir
        $html .= '
            <tr style="background-color:#f2f2f2;">
                <td colspan="5" align="right"><b>TOTAL</b></td>
                <td align="right"><b>RP' . number_format($totalDebit, 0, ',', '.') . '</b></td>
                <td align="right"><b>RP' . number_format($totalKredit, 0, ',', '.') . '</b></td>
            </tr>
            <tr style="background-color:#000;color:#fff;">
                <td colspan="5" align="right"><b>SALDO AWAL</b></td>
                <td colspan="2" align="center"><b>RP' . number_format($saldoAwal, 0, ',', '.') . '</b></td>
            </tr>
            <tr>
                <td colspan="5" align="right"><b>SALDO AKHIR</b></td>
                <td colspan="2" align="center"><b>RP' . number_format($saldoAkhir, 0, ',', '.') . '</b></td>
            </tr>
        ';

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_arus_kas.pdf', 'I');
    }
}
