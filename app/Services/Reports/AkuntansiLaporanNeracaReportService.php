<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkuntansiLaporanNeracaReportService
{
    public function getData(Request $request): array
    {
        $jenjang = Jenjang::find($request->input('jenjang'));
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $endDateValue = $endDate
            ? Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()
            : null;
        $startDateValue = $startDate
            ? Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay()
            : ($endDateValue ? $endDateValue->copy()->startOfYear()->startOfDay() : null);

        $akunSaldo = AkuntansiJurnalDetail::query()
            ->join('akuntansi_rekening', 'akuntansi_jurnal_detail.kode_rekening', '=', 'akuntansi_rekening.kode_rekening')
            ->join('akuntansi_jurnal', 'akuntansi_jurnal_detail.akuntansi_jurnal_id', '=', 'akuntansi_jurnal.akuntansi_jurnal_id')
            ->where('akuntansi_jurnal.ms_jenjang_id', $request->input('jenjang'))
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
            ->whereRaw("LEFT(akuntansi_jurnal_detail.kode_rekening, 1) IN ('1', '2', '3')")
            ->when($startDateValue && $endDateValue, function ($query) use ($startDateValue, $endDateValue) {
                $query->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$startDateValue, $endDateValue]);
            })
            ->when($endDateValue && !$startDateValue, function ($query) use ($endDateValue) {
                $query->where('akuntansi_jurnal.tanggal_transaksi', '<=', $endDateValue);
            })
            ->select(
                'akuntansi_jurnal_detail.kode_rekening',
                'akuntansi_rekening.nama_rekening',
                'akuntansi_rekening.posisi_normal',
                DB::raw('SUM(CASE WHEN akuntansi_jurnal_detail.posisi = "debit" THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN akuntansi_jurnal_detail.posisi = "kredit" THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_kredit')
            )
            ->groupBy(
                'akuntansi_jurnal_detail.kode_rekening',
                'akuntansi_rekening.nama_rekening',
                'akuntansi_rekening.posisi_normal'
            )
            ->orderBy('akuntansi_jurnal_detail.kode_rekening')
            ->get();

        $kelompok = [
            'aset' => [],
            'kewajiban' => [],
            'ekuitas' => [],
        ];

        foreach ($akunSaldo as $item) {
            $saldo = $item->posisi_normal === 'debit'
                ? ((float) $item->total_debit - (float) $item->total_kredit)
                : ((float) $item->total_kredit - (float) $item->total_debit);
            $kodeAwal = substr((string) $item->kode_rekening, 0, 1);
            $kelompokKey = [
                '1' => 'aset',
                '2' => 'kewajiban',
                '3' => 'ekuitas',
            ][$kodeAwal] ?? null;

            if ($kelompokKey) {
                $kelompok[$kelompokKey][] = [
                    'kode' => $item->kode_rekening,
                    'nama' => $item->nama_rekening,
                    'saldo' => $saldo,
                ];
            }
        }

        $dateFilter = function ($query) use ($startDateValue, $endDateValue) {
            if ($startDateValue && $endDateValue) {
                $query->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$startDateValue, $endDateValue]);
            } elseif ($endDateValue) {
                $query->where('akuntansi_jurnal.tanggal_transaksi', '<=', $endDateValue);
            }
        };

        $getTotalByAccountPrefix = function (string $prefix, string $normalPosition) use ($request, $dateFilter) {
            return (float) AkuntansiJurnalDetail::query()
                ->join(
                    'akuntansi_jurnal',
                    'akuntansi_jurnal_detail.akuntansi_jurnal_id',
                    '=',
                    'akuntansi_jurnal.akuntansi_jurnal_id'
                )
                ->join(
                    'akuntansi_rekening',
                    'akuntansi_jurnal_detail.kode_rekening',
                    '=',
                    'akuntansi_rekening.kode_rekening'
                )
                ->where('akuntansi_jurnal.ms_jenjang_id', $request->input('jenjang'))
                ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
                ->where('akuntansi_rekening.kode_rekening', 'like', $prefix . '%')
                ->where($dateFilter)
                ->selectRaw(
                    'COALESCE(SUM(CASE WHEN akuntansi_jurnal_detail.posisi = ? THEN akuntansi_jurnal_detail.nominal WHEN akuntansi_jurnal_detail.posisi = ? THEN -akuntansi_jurnal_detail.nominal ELSE 0 END), 0) AS total',
                    [$normalPosition, $normalPosition === 'kredit' ? 'debit' : 'kredit']
                )
                ->value('total');
        };

        $pendapatan = $getTotalByAccountPrefix('4', 'kredit');
        $beban = $getTotalByAccountPrefix('5', 'debit');
        $labaRugi = $pendapatan - $beban;
        $totalAset = collect($kelompok['aset'])->sum('saldo');
        $totalKewajiban = collect($kelompok['kewajiban'])->sum('saldo');
        $totalEkuitas = collect($kelompok['ekuitas'])->sum('saldo') + $labaRugi;
        $totalPassiva = $totalKewajiban + $totalEkuitas;
        $selisih = $totalAset - $totalPassiva;

        if ($startDate && $endDate) {
            $periode = 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y');
        } elseif ($endDate) {
            $periode = 'Posisi per ' . HelperController::formatTanggalIndonesia($endDate, 'd F Y');
        } else {
            $periode = 'Semua Periode';
        }

        return [
            'judul' => 'Laporan Neraca',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'deskripsiJenjang' => $jenjang->deskripsi ?? '-',
            'periode' => $periode,
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'kelompok' => $kelompok,
            'labaRugi' => $labaRugi,
            'totalAset' => $totalAset,
            'totalKewajiban' => $totalKewajiban,
            'totalEkuitas' => $totalEkuitas,
            'totalPassiva' => $totalPassiva,
            'selisih' => $selisih,
        ];
    }
}
