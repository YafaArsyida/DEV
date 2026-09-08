<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;

class AkuntansiLaporanPengeluaran extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-pengeluaran.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $jenjang = Jenjang::find($selectedJenjang);
        if (!$selectedJenjang) {
            return response()->json(['error' => 'Jenjang wajib dipilih'], 400);
        }


        $bebanPerBulan = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
        ])
            // ==========================================
            // FILTER HEADER JURNAL
            // ==========================================
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')

            // ==========================================
            // FILTER TANGGAL
            // ==========================================
            ->when(
                $startDate && $endDate,
                fn ($q) => $q->whereBetween('tanggal_transaksi', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59',
                ])
            )

            // ==========================================
            // HANYA JURNAL BEBAN
            // ==========================================
            ->whereHas('akuntansi_jurnal_detail.akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '5%');
            })

            ->get()

            // ==========================================
            // FLATTEN DETAIL JURNAL
            // ==========================================
            ->flatMap(function ($jurnal) {

                return $jurnal->akuntansi_jurnal_detail

                    ->filter(function ($detail) {

                        return str_starts_with(
                            (string) $detail->akuntansi_rekening->kode_rekening,
                            '5'
                        );

                    })

                    ->map(function ($detail) use ($jurnal) {

                        // Beban:
                        // debit  = +
                        // kredit = -
                        $nominal = $detail->posisi === 'debit'
                            ? (float) $detail->nominal
                            : -(float) $detail->nominal;

                        return [
                            'nama_rekening' => $detail->akuntansi_rekening->nama_rekening,
                            'tanggal_transaksi' => $jurnal->tanggal_transaksi,
                            'nominal' => $nominal,
                        ];
                    });
            })


            // ==========================================
            // GROUP REKENING → BULAN
            // ==========================================
            ->groupBy([
                'nama_rekening',
                function ($item) {
                    return Carbon::parse($item['tanggal_transaksi'])
                    ->format('Y-m');
                },
            ]);

        // ==========================================
        // AMBIL BULAN
        // ==========================================
        $bulanHeaders = collect($bebanPerBulan)
            ->flatMap(function ($item) {
                return collect($item)->keys()->all();
            })
            ->unique()
            ->sort()
            ->values();

        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {
            return [$bulan => \App\Http\Controllers\HelperController::formatTanggalIndonesia($bulan . '-01', 'F Y')];
        });

        $judul = 'Laporan Pengeluaran Sekolah';
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

        // Header
        $html = '<table border="0.5" cellpadding="3" cellspacing="0" width="100%">';
        $html .= '<thead><tr style="background-color:#f5f5f5;"><th>Nama Rekening</th>';

        foreach ($bulanIndo as $namaBulan) {
            $html .= '<th>' . $namaBulan . '</th>';
        }
        $html .= '<th>Total</th></tr></thead><tbody>';

        // Body
        foreach ($bebanPerBulan as $namaRekening => $dataPerBulan) {
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
            $totalBulan = $bebanPerBulan->reduce(function ($carry, $dataPerBulan) use ($key) {
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
