<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnal;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AkuntansiLaporanJurnalUmumReportService
{
    public function getData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $departemen = $request->input('departemen');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = trim((string) $request->input('search', ''));

        $jenjang = Jenjang::find($selectedJenjang);

        $jurnals = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
            'ms_pengguna',
        ])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->when($departemen, function ($query) use ($departemen) {
                $query->where('ms_departemen_id', $departemen);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [
                    Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                    Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay(),
                ]);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('deskripsi', 'like', '%' . $search . '%')
                        ->orWhere('nomor_jurnal', 'like', '%' . $search . '%')
                        ->orWhereHas('akuntansi_jurnal_detail', function ($query) use ($search) {
                            $query->where('kode_rekening', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('tanggal_transaksi')
            ->orderBy('akuntansi_jurnal_id')
            ->get();

        $totalNominal = 0;
        $data = $jurnals->map(function ($jurnal, $index) use (&$totalNominal) {
            $debit = $jurnal->akuntansi_jurnal_detail->where('posisi', 'debit');
            $kredit = $jurnal->akuntansi_jurnal_detail->where('posisi', 'kredit');
            $nominal = (float) $debit->sum('nominal');
            $totalNominal += $nominal;

            return [
                'nomor' => $index + 1,
                'tanggal' => $jurnal->tanggal_transaksi
                    ? HelperController::formatTanggalIndonesia($jurnal->tanggal_transaksi, 'd F Y')
                    : '-',
                'deskripsi' => $jurnal->deskripsi ?? '-',
                'petugas' => $jurnal->ms_pengguna->nama ?? '-',
                'akunDebit' => $debit->map(function ($detail) {
                    return $detail->kode_rekening . ' - '
                        . ($detail->akuntansi_rekening->nama_rekening ?? '-');
                })->values(),
                'akunKredit' => $kredit->map(function ($detail) {
                    return $detail->kode_rekening . ' - '
                        . ($detail->akuntansi_rekening->nama_rekening ?? '-');
                })->values(),
                'nominal' => $nominal,
            ];
        });

        $periode = $startDate && $endDate
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Jurnal Keuangan',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'deskripsiJenjang' => $jenjang->deskripsi ?? '-',
            'departemen' => $departemen ?: 'Semua Departemen',
            'periode' => $periode,
            'pencarian' => $search ?: 'Semua jurnal',
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahJurnal' => $jurnals->count(),
            'totalNominal' => $totalNominal,
            'data' => $data,
        ];
    }
}
