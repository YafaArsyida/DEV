<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnal;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Elibyy\TCPDF\Facades\TCPDF;

class AkuntansiLaporanLabaRugi extends Controller
{
    public function index()
    {
        return view('keuangan.akuntansi.laporan-laba-rugi');
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

        $jurnals = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
        ])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')
            ->when(
                $startDate && $endDate,
                function ($query) use ($startDate, $endDate) {
                    $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

                    $query->whereBetween('tanggal_transaksi', [$start, $end]);
                }
            )
            ->get();

        $pendapatanDetails = collect();
        $bebanDetails = collect();

        foreach ($jurnals as $jurnal) {

            foreach ($jurnal->akuntansi_jurnal_detail as $detail) {

                // Simpan tanggal jurnal ke detail
                $detail->tanggal_transaksi = $jurnal->tanggal_transaksi;

                // ==========================================
                // PENDAPATAN (4xxx)
                // ==========================================
                if (str_starts_with($detail->kode_rekening, '4')) {

                    // Kredit = pendapatan bertambah
                    // Debit  = pendapatan berkurang / reversal
                    $detail->nominal_laporan =
                        $detail->posisi === 'kredit'
                            ? $detail->nominal
                            : - $detail->nominal;

                    $pendapatanDetails->push($detail);
                }

                // ==========================================
                // BEBAN (5xxx)
                // ==========================================
                if (str_starts_with($detail->kode_rekening, '5')) {

                    // Debit  = beban bertambah
                    // Kredit = beban berkurang / reversal
                    $detail->nominal_laporan =
                        $detail->posisi === 'debit'
                            ? $detail->nominal
                            : - $detail->nominal;

                    $bebanDetails->push($detail);
                }
            }
        }

        $pendapatanPerBulan = $pendapatanDetails->groupBy([
            fn($item) => $item->akuntansi_rekening->nama_rekening,
            
            fn($item) => Carbon::parse($item->tanggal_transaksi)
                ->format('Y-m'),
        ]);

        $bebanPerBulan = $bebanDetails->groupBy([
            fn($item) => $item->akuntansi_rekening->nama_rekening,
            fn($item) => Carbon::parse($item->tanggal_transaksi)->format('Y-m'),
        ]);

        $bulanHeaders = $pendapatanPerBulan
            ->keys()
            ->merge($bebanPerBulan->keys())
            ->unique()
            ->sort()
            ->values();

        $bulanHeaders = $pendapatanPerBulan
            ->merge($bebanPerBulan)
            ->flatMap(function ($dataPerBulan) {
                return $dataPerBulan->keys();
            })
            ->unique()
            ->sort()
            ->values();


        // ==========================================
        // FORMAT BULAN INDONESIA
        // ==========================================
        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {

            return [
                $bulan => HelperController::formatTanggalIndonesia(
                    $bulan . '-01',
                    'F Y'
                ),
            ];
        });

        // ==========================================
        // TOTAL PENDAPATAN PER REKENING
        // ==========================================
        $totalPendapatanRekening = [];

        foreach ($pendapatanPerBulan as $namaRekening => $dataPerBulan) {

            $totalPendapatanRekening[$namaRekening] =
                $dataPerBulan->sum(function ($details) {
                    return $details->sum('nominal_laporan');
                });
        }

        // ==========================================
        // TOTAL BEBAN PER REKENING
        // ==========================================
        $totalBebanRekening = [];

        foreach ($bebanPerBulan as $namaRekening => $dataPerBulan) {

            $totalBebanRekening[$namaRekening] =
                $dataPerBulan->sum(function ($details) {
                    return $details->sum('nominal_laporan');
                });
        }


        // ==========================================
        // TOTAL PENDAPATAN PER BULAN
        // ==========================================
        $totalPendapatanPerBulan = [];

        foreach ($bulanHeaders as $bulan) {

            $totalPendapatanPerBulan[$bulan] =
                $pendapatanPerBulan->sum(function ($dataPerBulan) use ($bulan) {

                    return optional(
                        $dataPerBulan[$bulan] ?? null
                    )->sum('nominal_laporan');
                });
        }


        // ==========================================
        // TOTAL BEBAN PER BULAN
        // ==========================================
        $totalBebanPerBulan = [];

        foreach ($bulanHeaders as $bulan) {

            $totalBebanPerBulan[$bulan] =
                $bebanPerBulan->sum(function ($dataPerBulan) use ($bulan) {

                    return optional(
                        $dataPerBulan[$bulan] ?? null
                    )->sum('nominal_laporan');
                });
        }

        // ==========================================
        // LABA / RUGI PER BULAN
        // ==========================================
        $labaRugiPerBulan = [];

        foreach ($bulanHeaders as $bulan) {

            $pendapatan = $totalPendapatanPerBulan[$bulan] ?? 0;

            $beban = $totalBebanPerBulan[$bulan] ?? 0;

            $labaRugiPerBulan[$bulan] = $pendapatan - $beban;
        }


        // ==========================================
        // GRAND TOTAL
        // ==========================================
        $totalPendapatan = array_sum($totalPendapatanPerBulan);

        $totalBeban = array_sum($totalBebanPerBulan);

        $totalLabaRugi =  array_sum($labaRugiPerBulan);

        $judul = 'Laporan Laba Rugi';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start_date && $request->end_date) {
            $periode = 'Periode ' . HelperController::formatTanggalIndonesia($request->start_date, 'd F Y') .
                ' sampai ' . HelperController::formatTanggalIndonesia($request->end_date, 'd F Y');
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

        $html = '<table border="0.5" cellspacing="0" cellpadding="4" width="100%">';
        $html .= '<tr style="background-color:#f2f2f2;"><th align="left">Nama Rekening</th>';
        foreach ($bulanIndo as $label) {
            $html .= '<th>' . $label . '</th>';
        }
        $html .= '<th>Total</th></tr>';

        $html .= '<tr><td colspan="' . ($bulanIndo->count() + 2) . '"><strong>Pendapatan</strong></td></tr>';
        foreach ($pendapatanPerBulan as $nama => $perBulan) {
            $total = 0;
            $html .= '<tr><td>' . htmlspecialchars($nama) . '</td>';

            foreach ($bulanIndo as $key => $_) {
                $sum = optional($perBulan[$key] ?? null)->sum('nominal_laporan');
                $total += $sum;
                $html .= '<td>Rp' . number_format($sum, 0, ',', '.') . '</td>';
            }

            $html .= '<td><strong>Rp' . number_format($total, 0, ',', '.') . '</strong></td></tr>';
        }

        $html .= '<tr style="background-color:#d1d1d1;"><td><strong>Total Pendapatan</strong></td>';
        foreach ($bulanIndo as $key => $_) {
            $bulanSum = $pendapatanPerBulan->reduce(function ($carry, $dataPerBulan) use ($key) {
                return $carry + optional($dataPerBulan[$key] ?? null)->sum('nominal_laporan');
            }, 0);
            $html .= '<td><strong>Rp' . number_format($bulanSum, 0, ',', '.') . '</strong></td>';
        }
        $html .= '<td><strong>Rp' . number_format($totalPendapatan, 0, ',', '.') . '</strong></td></tr>';

        $html .= '<tr><td colspan="' . ($bulanIndo->count() + 2) . '"><strong>Beban</strong></td></tr>';
        foreach ($bebanPerBulan as $nama => $perBulan) {
            $total = 0;
            $html .= '<tr><td>' . htmlspecialchars($nama) . '</td>';

            foreach ($bulanIndo as $key => $_) {
                $sum = optional($perBulan[$key] ?? null)->sum('nominal_laporan');
                $total += $sum;
                $html .= '<td>Rp' . number_format($sum, 0, ',', '.') . '</td>';
            }

            $html .= '<td><strong>Rp' . number_format($total, 0, ',', '.') . '</strong></td></tr>';
        }

        $html .= '<tr style="background-color:#d1d1d1;"><td><strong>Total Beban</strong></td>';
        foreach ($bulanIndo as $key => $_) {
            $bulanSum = $bebanPerBulan->reduce(function ($carry, $dataPerBulan) use ($key) {
                return $carry + optional($dataPerBulan[$key] ?? null)->sum('nominal_laporan');
            }, 0);
            $html .= '<td><strong>Rp' . number_format($bulanSum, 0, ',', '.') . '</strong></td>';
        }
        $html .= '<td><strong>Rp' . number_format($totalBeban, 0, ',', '.') . '</strong></td></tr>';

        $html .= '<tr style="background-color:#f2f2f2;"><td><strong>LABA (RUGI)</strong></td>';
        foreach ($bulanIndo as $key => $_) {
            $laba = ($totalPendapatanPerBulan[$key] ?? 0) - ($totalBebanPerBulan[$key] ?? 0);
            $html .= '<td><strong>Rp' . number_format($laba, 0, ',', '.') . '</strong></td>';
        }
        $html .= '<td><strong>Rp' . number_format($totalLabaRugi, 0, ',', '.') . '</strong></td></tr>';
        $html .= '</table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_laba_rugi.pdf', 'I');
    }
}
