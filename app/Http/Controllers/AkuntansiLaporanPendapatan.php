<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class AkuntansiLaporanPendapatan extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-pendapatan.v_index');
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

        $pendapatanPerBulan = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajaran_id', $selectedTahunAjar)
            ->where('posisi', 'kredit')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })
            ->whereHas('akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '4%');
            })
            ->get()
            ->groupBy([
                fn($item) => $item->akuntansi_rekening->nama_rekening,
                fn($item) => \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m'),
            ]);

        // Ambil bulan unik
        $bulanHeaders = collect($pendapatanPerBulan)->flatMap(function ($item) {
            return collect($item)->keys()->all();
        })->unique()->sort()->values();

        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {
            return [$bulan => \App\Http\Controllers\HelperController::formatTanggalIndonesia($bulan . '-01', 'F Y')];
        });

        $judul = 'Laporan Pendapatan';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start_date && $request->end_date) {
            $periode = 'Periode ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->start_date, 'F Y') .
                ' sampai ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->end_date, 'F Y');
        } else {
            $periode = 'Semua Periode';
        }

        // Mulai PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage('L'); // L = Landscape

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $yayasan, 0, 1, 'C');
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Cell(0, 6, 'Tahun Ajaran: ' . ($tahunAjar->nama_tahun_ajar ?? '-'), 0, 1, 'C');
        $pdf::SetFont('times', '', 10);
        $pdf::MultiCell(0, 6, ($jenjang->deskripsi ?? '-'), 0, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 9);
        $pdf::setCellHeightRatio(1.2);

        // Header
        $html = '<table border="0.5" cellpadding="3" cellspacing="0" width="100%">';
        $html .= '<thead><tr style="background-color:#f5f5f5;"><th>Nama Rekening</th>';

        foreach ($bulanIndo as $namaBulan) {
            $html .= '<th>' . $namaBulan . '</th>';
        }
        $html .= '<th>Total</th></tr></thead><tbody>';

        // Body
        foreach ($pendapatanPerBulan as $namaRekening => $dataPerBulan) {
            $html .= '<tr><td>' . htmlspecialchars($namaRekening) . '</td>';
            $totalRekening = 0;

            foreach ($bulanIndo as $key => $namaBulan) {
                $jumlah = optional($dataPerBulan[$key] ?? null)->sum('nominal');
                $totalRekening += $jumlah;
                $html .= '<td align="left">Rp' . number_format($jumlah, 0, ',', '.') . '</td>';
            }

            $html .= '<td align="left"><strong>Rp' . number_format($totalRekening, 0, ',', '.') . '</strong></td></tr>';
        }

        // Footer Total
        $html .= '<tr style="background-color:#f0f0f0;font-weight:bold;"><td>TOTAL</td>';
        $grandTotal = 0;

        foreach ($bulanIndo as $key => $namaBulan) {
            $totalBulan = $pendapatanPerBulan->reduce(function ($carry, $dataPerBulan) use ($key) {
                return $carry + optional($dataPerBulan[$key] ?? null)->sum('nominal');
            }, 0);

            $grandTotal += $totalBulan;
            $html .= '<td align="left">Rp' . number_format($totalBulan, 0, ',', '.') . '</td>';
        }

        $html .= '<td align="left">Rp' . number_format($grandTotal, 0, ',', '.') . '</td></tr>';
        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_pendapatan.pdf', 'I');
    }
}
