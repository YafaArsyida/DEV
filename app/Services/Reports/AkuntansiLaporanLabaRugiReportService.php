<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnal;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AkuntansiLaporanLabaRugiReportService
{
    public function getData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenjang = Jenjang::find($selectedJenjang);

        $jurnals = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
        ])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [
                    Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                    Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay(),
                ]);
            })
            ->get();

        $pendapatanDetails = collect();
        $bebanDetails = collect();

        foreach ($jurnals as $jurnal) {
            foreach ($jurnal->akuntansi_jurnal_detail as $detail) {
                $rekening = $detail->akuntansi_rekening;
                if (!$rekening) {
                    continue;
                }

                $detail->tanggal_transaksi = $jurnal->tanggal_transaksi;
                $nominal = (float) $detail->nominal;

                if (str_starts_with((string) $detail->kode_rekening, '4')) {
                    $detail->nominal_laporan = $detail->posisi === 'kredit' ? $nominal : -$nominal;
                    $pendapatanDetails->push($detail);
                }

                if (str_starts_with((string) $detail->kode_rekening, '5')) {
                    $detail->nominal_laporan = $detail->posisi === 'debit' ? $nominal : -$nominal;
                    $bebanDetails->push($detail);
                }
            }
        }

        $groupByRekeningDanBulan = function ($details) {
            return $details->groupBy([
                fn ($item) => $item->akuntansi_rekening->nama_rekening,
                fn ($item) => Carbon::parse($item->tanggal_transaksi)->format('Y-m'),
            ]);
        };

        $pendapatanPerBulan = $groupByRekeningDanBulan($pendapatanDetails);
        $bebanPerBulan = $groupByRekeningDanBulan($bebanDetails);

        $bulanHeaders = $pendapatanPerBulan
            ->flatMap(fn ($dataPerBulan) => $dataPerBulan->keys())
            ->merge($bebanPerBulan->flatMap(fn ($dataPerBulan) => $dataPerBulan->keys()))
            ->unique()
            ->sort()
            ->values();

        $bulanIndo = $bulanHeaders->mapWithKeys(fn ($bulan) => [
            $bulan => HelperController::formatTanggalIndonesia($bulan . '-01', 'F Y'),
        ]);

        $totalPerRekening = function ($groupedDetails) {
            return $groupedDetails->map(function ($dataPerBulan) {
                return $dataPerBulan->sum(fn ($details) => $details->sum('nominal_laporan'));
            })->all();
        };

        $totalPendapatanRekening = $totalPerRekening($pendapatanPerBulan);
        $totalBebanRekening = $totalPerRekening($bebanPerBulan);

        $totalPerBulan = function ($groupedDetails) use ($bulanHeaders) {
            return $bulanHeaders->mapWithKeys(function ($bulan) use ($groupedDetails) {
                return [
                    $bulan => $groupedDetails->sum(
                        fn ($dataPerBulan) => ($dataPerBulan->get($bulan) ?? collect())->sum('nominal_laporan')
                    ),
                ];
            })->all();
        };

        $totalPendapatanPerBulan = $totalPerBulan($pendapatanPerBulan);
        $totalBebanPerBulan = $totalPerBulan($bebanPerBulan);
        $labaRugiPerBulan = [];

        foreach ($bulanHeaders as $bulan) {
            $labaRugiPerBulan[$bulan] = ($totalPendapatanPerBulan[$bulan] ?? 0)
                - ($totalBebanPerBulan[$bulan] ?? 0);
        }

        $periode = 'Semua Periode';
        if ($startDate && $endDate) {
            $periode = 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y');
        }

        return [
            'judul' => 'Laporan Laba Rugi',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'deskripsiJenjang' => $jenjang->deskripsi ?? '-',
            'periode' => $periode,
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'bulanHeaders' => $bulanHeaders,
            'bulanIndo' => $bulanIndo,
            'pendapatanPerBulan' => $pendapatanPerBulan,
            'bebanPerBulan' => $bebanPerBulan,
            'totalPendapatanRekening' => $totalPendapatanRekening,
            'totalBebanRekening' => $totalBebanRekening,
            'totalPendapatanPerBulan' => $totalPendapatanPerBulan,
            'totalBebanPerBulan' => $totalBebanPerBulan,
            'labaRugiPerBulan' => $labaRugiPerBulan,
            'totalPendapatan' => array_sum($totalPendapatanPerBulan),
            'totalBeban' => array_sum($totalBebanPerBulan),
            'totalLabaRugi' => array_sum($labaRugiPerBulan),
        ];
    }
}
