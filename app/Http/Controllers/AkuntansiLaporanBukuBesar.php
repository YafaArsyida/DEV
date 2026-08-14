<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;

class AkuntansiLaporanBukuBesar extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-buku-besar.v_index');
    }

    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedRekening = $request->rekening;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;

        if (!$selectedJenjang || !$selectedRekening || !$startDate || !$endDate) {
            return response()->json(['error' => 'Semua filter wajib dipilih'], 400);
        }

        if ($startDate > $endDate) {
            return response()->json(['error' => 'Tanggal mulai harus lebih awal atau sama dengan tanggal selesai.'], 400);
        }

        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $rekening = AkuntansiRekening::where('kode_rekening', $selectedRekening)->first();
        $posisiNormal = $rekening ? $rekening->posisi_normal : 'debit';

        $transaksiJurnal = AkuntansiJurnalDetail::with(['akuntansi_jurnal.ms_pengguna', 'akuntansi_rekening'])
            ->join('akuntansi_jurnal', 'akuntansi_jurnal.akuntansi_jurnal_id', '=', 'akuntansi_jurnal_detail.akuntansi_jurnal_id')
            ->where('akuntansi_jurnal_detail.kode_rekening', $selectedRekening)
            ->where('akuntansi_jurnal.ms_jenjang_id', $selectedJenjang)
            ->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$start, $end])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('akuntansi_jurnal.deskripsi', 'like', '%' . $search . '%')
                        ->orWhere('akuntansi_jurnal.nomor_jurnal', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->select('akuntansi_jurnal_detail.*')
            ->get();

        $saldoAwalData = AkuntansiJurnalDetail::join('akuntansi_jurnal', 'akuntansi_jurnal.akuntansi_jurnal_id', '=', 'akuntansi_jurnal_detail.akuntansi_jurnal_id')
            ->where('akuntansi_jurnal_detail.kode_rekening', $selectedRekening)
            ->where('akuntansi_jurnal.ms_jenjang_id', $selectedJenjang)
            ->where('akuntansi_jurnal.tanggal_transaksi', '<', $start)
            ->selectRaw("\n                SUM(CASE WHEN akuntansi_jurnal_detail.posisi = 'debit' THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_debit,\n                SUM(CASE WHEN akuntansi_jurnal_detail.posisi = 'kredit' THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_kredit\n            ")
            ->first();

        $totalDebitBefore = $saldoAwalData->total_debit ?? 0;
        $totalKreditBefore = $saldoAwalData->total_kredit ?? 0;
        if ($posisiNormal === 'kredit') {
            $saldoAwal = $totalKreditBefore - $totalDebitBefore;
        } else {
            $saldoAwal = $totalDebitBefore - $totalKreditBefore;
        }

        $totalDebitPeriod = $transaksiJurnal->where('posisi', 'debit')->sum('nominal');
        $totalKreditPeriod = $transaksiJurnal->where('posisi', 'kredit')->sum('nominal');
        if ($posisiNormal === 'kredit') {
            $saldoAkhir = $saldoAwal + ($totalKreditPeriod - $totalDebitPeriod);
        } else {
            $saldoAkhir = $saldoAwal + ($totalDebitPeriod - $totalKreditPeriod);
        }

        $judul = 'Laporan Buku Besar';
        $periode = 'Periode ' . HelperController::formatTanggalIndonesia($startDate, 'd F Y') .
            ' sampai ' . HelperController::formatTanggalIndonesia($endDate, 'd F Y');

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage('L');

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, 'Rekening: ' . $selectedRekening . ' - ' . ($rekening->nama_rekening ?? '-'), 0, 1, 'C');
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 9);
        $pdf::setCellHeightRatio(1.2);

        $html = '<table border="0.5" cellpadding="4">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="3%" align="center"><b>No</b></th>
                    <th width="12%"><b>Tanggal</b></th>
                    <th width="12%"><b>Nomor Jurnal</b></th>
                    <th width="15%"><b>Petugas</b></th>
                    <th width="32%"><b>Deskripsi</b></th>
                    <th width="12%" align="center"><b>Debit</b></th>
                    <th width="12%" align="center"><b>Kredit</b></th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($transaksiJurnal as $trx) {
            $html .= '<tr>
                <td width="3%" align="center">' . $no++ . '.</td>
                <td width="12%">' . HelperController::formatTanggalIndonesia($trx->akuntansi_jurnal->tanggal_transaksi, 'd F Y') . '</td>
                <td width="12%">' . $trx->akuntansi_jurnal->nomor_jurnal . '</td>
                <td width="15%">' . optional($trx->akuntansi_jurnal->ms_pengguna)->nama . '</td>
                <td width="32%">' . $trx->akuntansi_jurnal->deskripsi . '</td>
                <td width="12%" align="center">' . ($trx->posisi === 'debit' ? 'RP' . number_format($trx->nominal, 0, ',', '.') : '-') . '</td>
                <td width="12%" align="center">' . ($trx->posisi === 'kredit' ? 'RP' . number_format($trx->nominal, 0, ',', '.') : '-') . '</td>
            </tr>';
        }

        $html .= '<tr style="background-color:#f2f2f2;">
                <td colspan="4" align="right"><b>TOTAL MUTASI</b></td>
                <td></td>
                <td align="right"><b>RP' . number_format($totalDebitPeriod, 0, ',', '.') . '</b></td>
                <td align="right"><b>RP' . number_format($totalKreditPeriod, 0, ',', '.') . '</b></td>
            </tr>
            <tr style="background-color:#000;color:#fff;">
                <td colspan="5" align="right"><b>SALDO AWAL</b></td>
                <td colspan="2" align="center"><b>RP' . number_format($saldoAwal, 0, ',', '.') . '</b></td>
            </tr>
            <tr>
                <td colspan="5" align="right"><b>SALDO AKHIR</b></td>
                <td colspan="2" align="center"><b>RP' . number_format($saldoAkhir, 0, ',', '.') . '</b></td>
            </tr>';

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_buku_besar.pdf', 'I');
    }
}
