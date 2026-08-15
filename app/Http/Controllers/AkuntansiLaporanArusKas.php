<?php

namespace App\Http\Controllers;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;

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
        $selectedRekening = $request->rekening;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        if ($startDate && $endDate && Carbon::parse($startDate)->greaterThan(Carbon::parse($endDate))) {
            return response()->json(['error' => 'Tanggal mulai harus lebih awal atau sama dengan tanggal selesai.'], 400);
        }

        $akunKasBank = ['11001', '11002'];
        $rekeningList = $selectedRekening ? [$selectedRekening] : $akunKasBank;

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $query = AkuntansiJurnalDetail::with(['akuntansi_rekening', 'akuntansi_jurnal.ms_pengguna'])
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->whereIn('akuntansi_jurnal_detail.kode_rekening', $rekeningList)
            ->where('akuntansi_jurnal.ms_jenjang_id', $selectedJenjang)
            ->where('akuntansi_jurnal.ms_tahun_ajaran_id', $selectedTahunAjar)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH');

        if ($startDate && $endDate) {
            $query->whereBetween('akuntansi_jurnal.tanggal_transaksi', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('akuntansi_jurnal.deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('akuntansi_jurnal.nomor_jurnal', 'like', '%' . $search . '%');
            });
        }

        $transaksiJurnal = $query
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->orderBy('akuntansi_jurnal.akuntansi_jurnal_id')
            ->orderBy('akuntansi_jurnal_detail.akuntansi_jurnal_detail_id')
            ->select('akuntansi_jurnal_detail.*')
            ->get();

        $saldoAwal = 0;
        if ($startDate) {
            $start = Carbon::parse($startDate)->startOfDay();

            $saldoBefore = AkuntansiJurnalDetail::join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
                ->whereIn('akuntansi_jurnal_detail.kode_rekening', $rekeningList)
                ->where('akuntansi_jurnal.ms_jenjang_id', $selectedJenjang)
                ->where('akuntansi_jurnal.ms_tahun_ajaran_id', $selectedTahunAjar)
                ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
                ->where('akuntansi_jurnal.tanggal_transaksi', '<', $start)
                ->selectRaw("SUM(CASE WHEN akuntansi_jurnal_detail.posisi = 'debit' THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_debit,
                    SUM(CASE WHEN akuntansi_jurnal_detail.posisi = 'kredit' THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_kredit")
                ->first();

            $saldoAwal = ($saldoBefore->total_debit ?? 0) - ($saldoBefore->total_kredit ?? 0);
        }

        $totalKasMasuk = $transaksiJurnal->where('posisi', 'debit')->sum('nominal');
        $totalKasKeluar = $transaksiJurnal->where('posisi', 'kredit')->sum('nominal');
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;

        $judul = 'Laporan Arus Kas';
        $unitLabel = $jenjang ? ($jenjang->nama_jenjang ?? 'Unit') : 'Unit';
        $periode = ($startDate && $endDate)
            ? 'Periode ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($startDate, 'd F Y') . ' sampai ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($endDate, 'd F Y')
            : 'Semua Periode';

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage('L');

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 6, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 6, 'Yayasan Drul Khukama', 0, 1, 'C');
        $pdf::Cell(0, 6, 'Unit ' . $unitLabel, 0, 1, 'C');
        $pdf::SetFont('times', '', 10);
        $pdf::Cell(0, 6, $periode, 0, 1, 'C');
        $pdf::Ln(3);

        $html = '<table border="0.5" cellpadding="4" style="border-collapse:collapse; width:100%;">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="5%" align="center"><b>No</b></th>
                    <th width="13%"><b>Tanggal</b></th>
                    <th width="14%"><b>Akun</b></th>
                    <th width="14%"><b>Petugas</b></th>
                    <th width="34%"><b>Deskripsi</b></th>
                    <th width="10%" align="center"><b>Pemasukan</b></th>
                    <th width="10%" align="center"><b>Pengeluaran</b></th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($transaksiJurnal as $trx) {
            $html .= '<tr>
                <td width="5%" align="center">' . $no++ . '.</td>
                <td width="13%">' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($trx->akuntansi_jurnal->tanggal_transaksi, 'd F Y') . '</td>
                <td width="14%">' . ($trx->akuntansi_rekening->kode_rekening ?? '-') . ' - ' . ($trx->akuntansi_rekening->nama_rekening ?? '-') . '</td>
                <td width="14%">' . optional($trx->akuntansi_jurnal->ms_pengguna)->nama . '</td>
                <td width="34%">' . ($trx->akuntansi_jurnal->deskripsi ?? '-') . '</td>
                <td width="10%" align="center">' . ($trx->posisi === 'debit' ? 'Rp' . number_format($trx->nominal, 0, ',', '.') : '-') . '</td>
                <td width="10%" align="center">' . ($trx->posisi === 'kredit' ? 'Rp' . number_format($trx->nominal, 0, ',', '.') : '-') . '</td>
            </tr>';
        }

        $html .= '<tr style="background-color:#f2f2f2;">
                <td colspan="5" align="right"><b>Total</b></td>
                <td align="center"><b>Rp' . number_format($totalKasMasuk, 0, ',', '.') . '</b></td>
                <td align="center"><b>Rp' . number_format($totalKasKeluar, 0, ',', '.') . '</b></td>
            </tr>
            <tr style="background-color:#000;color:#fff;">
                <td colspan="5" align="right"><b>Saldo Awal</b></td>
                <td colspan="2" align="center"><b>Rp' . number_format($saldoAwal, 0, ',', '.') . '</b></td>
            </tr>
            <tr>
                <td colspan="5" align="right"><b>Saldo Akhir</b></td>
                <td colspan="2" align="center"><b>Rp' . number_format($saldoAkhir, 0, ',', '.') . '</b></td>
            </tr>';

        $html .= '</tbody></table>';

        $pdf::SetFont('times', '', 8);
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_arus_kas.pdf', 'I');
    }
}
