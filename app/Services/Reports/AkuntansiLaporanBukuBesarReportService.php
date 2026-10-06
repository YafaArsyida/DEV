<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AkuntansiLaporanBukuBesarReportService
{
    public function getData(Request $request): array
    {
        $jenjangId = $request->input('jenjang');
        $kodeRekening = $request->input('rekening');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = trim((string) $request->input('search', ''));

        $rekening = AkuntansiRekening::where('kode_rekening', $kodeRekening)->first();
        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $saldoAwalData = AkuntansiJurnalDetail::query()
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->where('akuntansi_jurnal_detail.kode_rekening', $kodeRekening)
            ->where('akuntansi_jurnal.ms_jenjang_id', $jenjangId)
            ->where('akuntansi_jurnal.tanggal_transaksi', '<', $start)
            ->selectRaw("
                SUM(CASE WHEN akuntansi_jurnal_detail.posisi = 'debit' THEN akuntansi_jurnal_detail.nominal ELSE 0 END) AS total_debit,
                SUM(CASE WHEN akuntansi_jurnal_detail.posisi = 'kredit' THEN akuntansi_jurnal_detail.nominal ELSE 0 END) AS total_kredit
            ")
            ->first();

        $totalDebitBefore = (float) ($saldoAwalData->total_debit ?? 0);
        $totalKreditBefore = (float) ($saldoAwalData->total_kredit ?? 0);
        $saldoAwal = $rekening->posisi_normal === 'kredit'
            ? $totalKreditBefore - $totalDebitBefore
            : $totalDebitBefore - $totalKreditBefore;

        $transaksiJurnal = AkuntansiJurnalDetail::with([
            'akuntansi_jurnal.ms_pengguna',
            'akuntansi_rekening',
        ])
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->where('akuntansi_jurnal_detail.kode_rekening', $kodeRekening)
            ->where('akuntansi_jurnal.ms_jenjang_id', $jenjangId)
            ->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$start, $end])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('akuntansi_jurnal.deskripsi', 'like', '%' . $search . '%')
                        ->orWhere('akuntansi_jurnal.nomor_jurnal', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->orderBy('akuntansi_jurnal.akuntansi_jurnal_id')
            ->orderBy('akuntansi_jurnal_detail.akuntansi_jurnal_detail_id')
            ->select('akuntansi_jurnal_detail.*')
            ->get();

        $saldo = $saldoAwal;
        $data = $transaksiJurnal->map(function ($transaksi, $index) use (&$saldo, $rekening) {
            $nominal = (float) $transaksi->nominal;
            $saldo += $rekening->posisi_normal === 'kredit'
                ? ($transaksi->posisi === 'kredit' ? $nominal : -$nominal)
                : ($transaksi->posisi === 'debit' ? $nominal : -$nominal);

            return [
                'nomor' => $index + 1,
                'tanggal' => $transaksi->akuntansi_jurnal->tanggal_transaksi
                    ? HelperController::formatTanggalIndonesia(
                        $transaksi->akuntansi_jurnal->tanggal_transaksi,
                        'd F Y H:i:s'
                    )
                    : '-',
                'nomorJurnal' => $transaksi->akuntansi_jurnal->nomor_jurnal ?? '-',
                'petugas' => $transaksi->akuntansi_jurnal->ms_pengguna->nama ?? '-',
                'deskripsi' => $transaksi->akuntansi_jurnal->deskripsi ?? '-',
                'debit' => $transaksi->posisi === 'debit' ? $nominal : null,
                'kredit' => $transaksi->posisi === 'kredit' ? $nominal : null,
                'saldo' => $saldo,
            ];
        });

        $totalDebit = (float) $transaksiJurnal->where('posisi', 'debit')->sum('nominal');
        $totalKredit = (float) $transaksiJurnal->where('posisi', 'kredit')->sum('nominal');
        $saldoAkhir = $saldo;

        return [
            'judul' => 'Laporan Buku Besar',
            'rekening' => $rekening,
            'periode' => 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y'),
            'pencarian' => $search ?: 'Semua transaksi',
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'saldoAwal' => $saldoAwal,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'saldoAkhir' => $saldoAkhir,
            'jumlahTransaksi' => $transaksiJurnal->count(),
            'data' => $data,
        ];
    }
}
