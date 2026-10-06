<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AkuntansiLaporanArusKasReportService
{
    private const REKENING_KAS_BANK = ['11001', '11002'];

    public function getData(Request $request): array
    {
        $jenjang = Jenjang::find($request->input('jenjang'));
        $rekening = $request->input('rekening');
        $rekeningList = $rekening ? [$rekening] : self::REKENING_KAS_BANK;
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();

        $transaksi = AkuntansiJurnalDetail::query()
            ->with([
                'akuntansi_rekening',
                'akuntansi_jurnal.ms_pengguna',
            ])
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->whereIn('akuntansi_jurnal_detail.kode_rekening', $rekeningList)
            ->where('akuntansi_jurnal.ms_jenjang_id', $request->input('jenjang'))
            ->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->orderBy('akuntansi_jurnal.akuntansi_jurnal_id')
            ->orderBy('akuntansi_jurnal_detail.akuntansi_jurnal_detail_id')
            ->select('akuntansi_jurnal_detail.*')
            ->get();

        $saldoAwal = (float) AkuntansiJurnalDetail::query()
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->whereIn('akuntansi_jurnal_detail.kode_rekening', $rekeningList)
            ->where('akuntansi_jurnal.ms_jenjang_id', $request->input('jenjang'))
            ->where('akuntansi_jurnal.tanggal_transaksi', '<', $startDate)
            ->selectRaw("
                COALESCE(SUM(CASE
                    WHEN akuntansi_jurnal_detail.posisi = 'debit'
                    THEN akuntansi_jurnal_detail.nominal
                    ELSE 0
                END), 0)
                -
                COALESCE(SUM(CASE
                    WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                    THEN akuntansi_jurnal_detail.nominal
                    ELSE 0
                END), 0) AS saldo
            ")
            ->value('saldo');

        $totalKasMasuk = (float) $transaksi
            ->where('posisi', 'debit')
            ->sum('nominal');
        $totalKasKeluar = (float) $transaksi
            ->where('posisi', 'kredit')
            ->sum('nominal');

        if ($rekening === '11001') {
            $namaRekening = 'Kas Besar';
        } elseif ($rekening === '11002') {
            $namaRekening = 'Bank Sekolah';
        } else {
            $namaRekening = 'Kas & Bank';
        }

        return [
            'judul' => 'Laporan Arus Kas - ' . $namaRekening,
            'namaRekening' => $namaRekening,
            'yayasan' => 'Yayasan Drul Khukama',
            'unit' => $jenjang->nama_jenjang ?? 'Unit',
            'deskripsiJenjang' => $jenjang->deskripsi ?? '-',
            'periode' => 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y'),
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'transaksi' => $transaksi,
            'saldoAwal' => $saldoAwal,
            'totalKasMasuk' => $totalKasMasuk,
            'totalKasKeluar' => $totalKasKeluar,
            'saldoAkhir' => $saldoAwal + $totalKasMasuk - $totalKasKeluar,
        ];
    }
}
