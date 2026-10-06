<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnal;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AkuntansiLaporanPengeluaranReportService
{
    public function getData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenjang = Jenjang::find($selectedJenjang);

        $jurnals = AkuntansiJurnal::with('akuntansi_jurnal_detail.akuntansi_rekening')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59',
                ]);
            })
            ->whereHas('akuntansi_jurnal_detail.akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '5%');
            })
            ->get();

        $pengeluaranPerBulan = $jurnals
            ->flatMap(function ($jurnal) {
                return $jurnal->akuntansi_jurnal_detail
                    ->filter(function ($detail) {
                        return str_starts_with(
                            (string) $detail->akuntansi_rekening?->kode_rekening,
                            '5'
                        );
                    })
                    ->map(function ($detail) use ($jurnal) {
                        $nominal = strtolower((string) $detail->posisi) === 'debit'
                            ? (float) $detail->nominal
                            : -(float) $detail->nominal;

                        return [
                            'nama_rekening' => $detail->akuntansi_rekening->nama_rekening,
                            'bulan' => Carbon::parse($jurnal->tanggal_transaksi)->format('Y-m'),
                            'nominal' => $nominal,
                        ];
                    });
            })
            ->groupBy('nama_rekening')
            ->map(function ($items) {
                return $items->groupBy('bulan')
                    ->map(fn ($items) => $items->sum('nominal'));
            });

        $bulanHeaders = $pengeluaranPerBulan
            ->flatMap(fn ($pengeluaran) => $pengeluaran->keys())
            ->unique()
            ->sort()
            ->values();

        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {
            return [
                $bulan => HelperController::formatTanggalIndonesia($bulan . '-01', 'F Y'),
            ];
        });

        $data = $pengeluaranPerBulan->map(function ($pengeluaran, $namaRekening) use ($bulanHeaders) {
            $bulanan = $bulanHeaders->mapWithKeys(function ($bulan) use ($pengeluaran) {
                return [$bulan => (float) ($pengeluaran->get($bulan) ?? 0)];
            });

            return [
                'namaRekening' => $namaRekening,
                'bulanan' => $bulanan,
                'total' => $bulanan->sum(),
            ];
        })->values();

        $totalPerBulan = $bulanHeaders->mapWithKeys(function ($bulan) use ($pengeluaranPerBulan) {
            return [
                $bulan => $pengeluaranPerBulan->sum(fn ($pengeluaran) => (float) ($pengeluaran->get($bulan) ?? 0)),
            ];
        });

        $periode = $startDate && $endDate
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Pengeluaran Sekolah',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'deskripsiJenjang' => $jenjang->deskripsi ?? '-',
            'periode' => $periode,
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'bulanHeaders' => $bulanHeaders,
            'bulanIndo' => $bulanIndo,
            'data' => $data,
            'totalPerBulan' => $totalPerBulan,
            'grandTotal' => $totalPerBulan->sum(),
        ];
    }
}
