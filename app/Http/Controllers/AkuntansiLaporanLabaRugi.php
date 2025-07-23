<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Carbon;

class AkuntansiLaporanLabaRugi extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-laba-rugi.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $pendapatan = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajaran_id', $selectedTahunAjar)
            ->where('posisi', 'kredit')
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]))
            ->whereHas('akuntansi_rekening', fn($q) => $q->where('kode_rekening', 'like', '4%'))
            ->get()
            ->groupBy([
                fn($i) => $i->akuntansi_rekening->nama_rekening,
                fn($i) => Carbon::parse($i->tanggal_transaksi)->format('Y-m')
            ]);

        $beban = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajaran_id', $selectedTahunAjar)
            ->where('posisi', 'debit')
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]))
            ->whereHas('akuntansi_rekening', fn($q) => $q->where('kode_rekening', 'like', '5%'))
            ->get()
            ->groupBy([
                fn($i) => $i->akuntansi_rekening->nama_rekening,
                fn($i) => Carbon::parse($i->tanggal_transaksi)->format('Y-m')
            ]);

        $bulanHeaders = collect($pendapatan)->merge($beban)->flatMap(fn($i) => collect($i)->keys())->unique()->sort()->values();
        $bulanIndo = $bulanHeaders->mapWithKeys(fn($b) => [$b => HelperController::formatTanggalIndonesia($b . '-01', 'F Y')]);

        $judul = 'Laporan Laba Rugi';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start_date && $request->end_date) {
            $periode = 'Periode ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->start_date, 'F Y') .
                ' sampai ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->end_date, 'F Y');
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
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Ln(3);
        $pdf::SetFont('times', '', 9);

        $html = '<table border="0.5" cellspacing="0" cellpadding="2" width="100%">';

        // HEAD
        $html .= '<tr><th align="left">Nama Rekening</th>';
        foreach ($bulanIndo as $b) $html .= "<th>$b</th>";
        $html .= '<th>Total</th></tr>';

        // Pendapatan
        $html .= '<tr><td colspan="' . ($bulanIndo->count() + 2) . '"><strong>Pendapatan</strong></td></tr>';
        foreach ($pendapatan as $nama => $perBulan) {
            $total = 0;
            $html .= "<tr><td>$nama</td>";
            foreach ($bulanIndo as $key => $_) {
                $sum = optional($perBulan[$key] ?? null)->sum('nominal');
                $total += $sum;
                $html .= '<td>Rp' . number_format($sum, 0, ',', '.') . '</td>';
            }
            $html .= '<td><strong>Rp' . number_format($total, 0, ',', '.') . '</strong></td></tr>';
        }

        // Total Pendapatan per Bulan
        $html .= '<tr><td><strong>Total Pendapatan</strong></td>';
        $grandPendapatan = 0;
        foreach ($bulanIndo as $key => $_) {
            $bulanSum = $pendapatan->reduce(fn($c, $pb) => $c + optional($pb[$key] ?? null)->sum('nominal'), 0);
            $grandPendapatan += $bulanSum;
            $html .= '<td><strong>Rp' . number_format($bulanSum, 0, ',', '.') . '</strong></td>';
        }
        $html .= '<td><strong>Rp' . number_format($grandPendapatan, 0, ',', '.') . '</strong></td></tr>';

        // Beban
        $html .= '<tr><td colspan="' . ($bulanIndo->count() + 2) . '"><strong>Beban</strong></td></tr>';
        foreach ($beban as $nama => $perBulan) {
            $total = 0;
            $html .= "<tr><td>$nama</td>";
            foreach ($bulanIndo as $key => $_) {
                $sum = optional($perBulan[$key] ?? null)->sum('nominal');
                $total += $sum;
                $html .= '<td>Rp' . number_format($sum, 0, ',', '.') . '</td>';
            }
            $html .= '<td><strong>Rp' . number_format($total, 0, ',', '.') . '</strong></td></tr>';
        }

        // Total Beban per Bulan
        $html .= '<tr><td><strong>Total Beban</strong></td>';
        $grandBeban = 0;
        foreach ($bulanIndo as $key => $_) {
            $bulanSum = $beban->reduce(fn($c, $pb) => $c + optional($pb[$key] ?? null)->sum('nominal'), 0);
            $grandBeban += $bulanSum;
            $html .= '<td><strong>Rp' . number_format($bulanSum, 0, ',', '.') . '</strong></td>';
        }
        $html .= '<td><strong>Rp' . number_format($grandBeban, 0, ',', '.') . '</strong></td></tr>';

        // Laba Rugi
        $html .= '<tr><td><strong>Laba (Rugi)</strong></td>';
        $totalLaba = 0;
        foreach ($bulanIndo as $bulan => $_) {
            $pend = $pendapatan->map(fn($rek) => optional($rek[$bulan] ?? null)->sum('nominal'))->sum();
            $beb = $beban->map(fn($rek) => optional($rek[$bulan] ?? null)->sum('nominal'))->sum();
            $laba = $pend - $beb;
            $totalLaba += $laba;
            $html .= '<td><strong>Rp' . number_format($laba, 0, ',', '.') . '</strong></td>';
        }
        $html .= '<td><strong>Rp' . number_format($totalLaba, 0, ',', '.') . '</strong></td></tr>';
        $html .= '</table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_laba_rugi.pdf', 'I');
    }
}
