<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Elibyy\TCPDF\Facades\TCPDF;

class AkuntansiLaporanBukuBesar extends Controller
{
    public function index()
    {
        return view('keuangan.akuntansi.laporan-buku-besar');
    }

    public function cetakPDF(Request $request)
    {
        // ==========================================
        // FILTER
        // ==========================================
        $selectedJenjang = $request->jenjang;
        $selectedRekening = $request->rekening;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;

        if (!$selectedJenjang || !$selectedRekening || !$startDate || !$endDate) {
            return response()->json([
                'error' => 'Semua filter wajib dipilih'
            ], 400);
        }

        if ($startDate > $endDate) {
            return response()->json([
                'error' => 'Tanggal mulai harus lebih awal atau sama dengan tanggal selesai.'
            ], 400);
        }

        $start = Carbon::createFromFormat(
            'Y-m-d',
            $startDate
        )->startOfDay();

        $end = Carbon::createFromFormat(
            'Y-m-d',
            $endDate
        )->endOfDay();


        // ==========================================
        // DATA REKENING
        // ==========================================
        $rekening = AkuntansiRekening::where(
            'kode_rekening',
            $selectedRekening
        )->first();

        if (!$rekening) {
            return response()->json([
                'error' => 'Rekening tidak ditemukan'
            ], 404);
        }

        $posisiNormal = $rekening->posisi_normal;


        // ==========================================
        // SALDO AWAL
        // ==========================================
        $saldoAwalData = AkuntansiJurnalDetail::join(
            'akuntansi_jurnal',
            'akuntansi_jurnal.akuntansi_jurnal_id',
            '=',
            'akuntansi_jurnal_detail.akuntansi_jurnal_id'
        )
            ->where(
                'akuntansi_jurnal_detail.kode_rekening',
                $selectedRekening
            )
            ->where(
                'akuntansi_jurnal.ms_jenjang_id',
                $selectedJenjang
            )
            ->where(
                'akuntansi_jurnal.ms_departemen_id',
                'SEKOLAH'
            )
            ->where(
                'akuntansi_jurnal.tanggal_transaksi',
                '<',
                $start
            )
            ->selectRaw("
                SUM(
                    CASE
                        WHEN akuntansi_jurnal_detail.posisi = 'debit'
                        THEN akuntansi_jurnal_detail.nominal
                        ELSE 0
                    END
                ) AS total_debit,

                SUM(
                    CASE
                        WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                        THEN akuntansi_jurnal_detail.nominal
                        ELSE 0
                    END
                ) AS total_kredit
            ")
            ->first();

        $totalDebitBefore = $saldoAwalData->total_debit ?? 0;
        $totalKreditBefore = $saldoAwalData->total_kredit ?? 0;

        if ($posisiNormal === 'kredit') {
            $saldoAwal = $totalKreditBefore - $totalDebitBefore;
        } else {
            $saldoAwal = $totalDebitBefore - $totalKreditBefore;
        }


        // ==========================================
        // TRANSAKSI JURNAL
        // ==========================================
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
            ->where(
                'akuntansi_jurnal_detail.kode_rekening',
                $selectedRekening
            )
            ->where(
                'akuntansi_jurnal.ms_jenjang_id',
                $selectedJenjang
            )
            ->where(
                'akuntansi_jurnal.ms_departemen_id',
                'SEKOLAH'
            )
            ->whereBetween(
                'akuntansi_jurnal.tanggal_transaksi',
                [$start, $end]
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where(
                        'akuntansi_jurnal.deskripsi',
                        'like',
                        '%' . $search . '%'
                    )
                        ->orWhere(
                            'akuntansi_jurnal.nomor_jurnal',
                            'like',
                            '%' . $search . '%'
                        );
                });
            })
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->orderBy('akuntansi_jurnal.akuntansi_jurnal_id')
            ->orderBy(
                'akuntansi_jurnal_detail.akuntansi_jurnal_detail_id'
            )
            ->select('akuntansi_jurnal_detail.*')
            ->get();


        // ==========================================
        // TOTAL MUTASI
        // ==========================================
        $totalDebitPeriod = $transaksiJurnal
            ->where('posisi', 'debit')
            ->sum('nominal');

        $totalKreditPeriod = $transaksiJurnal
            ->where('posisi', 'kredit')
            ->sum('nominal');


        // ==========================================
        // SALDO AKHIR
        // ==========================================
        if ($posisiNormal === 'kredit') {
            $saldoAkhir =
                $saldoAwal
                + $totalKreditPeriod
                - $totalDebitPeriod;
        } else {
            $saldoAkhir =
                $saldoAwal
                + $totalDebitPeriod
                - $totalKreditPeriod;
        }


        // ==========================================
        // JUDUL
        // ==========================================
        $judul = 'Laporan Buku Besar';

        $periode =
            'Periode '
            . HelperController::formatTanggalIndonesia(
                $startDate, 'd F Y'
            )
            . ' sampai '
            . HelperController::formatTanggalIndonesia(
                $endDate, 'd F Y'
            );


        // ==========================================
        // PDF
        // ==========================================
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf::SetTitle($judul);
        $pdf::AddPage('L');

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');

        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, 'Rekening: '. $selectedRekening . ' - ' . $rekening->nama_rekening, 0, 1, 'C');

        $pdf::Cell(0, 5, $periode, 0, 1, 'C');

        $pdf::Ln(3);

        $pdf::SetFont('times', '', 8.5);
        $pdf::setCellHeightRatio(1.2);


        // ==========================================
        // TABLE HEADER
        // ==========================================
        $html = '
        <table border="0.5" cellpadding="3">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="3%" align="center"><b>No</b></th>
                    <th width="10%" align="left"><b>Tanggal</b></th>
                    <th width="10%" align="left"><b>Nomor Jurnal</b></th>
                    <th width="10%" align="left"><b>Petugas</b></th>
                    <th width="39%" align="left"><b>Deskripsi Transaksi</b></th>
                    <th width="10%" align="center"><b>Debit</b></th>
                    <th width="10%" align="center"><b>Kredit</b></th>
                    <th width="8%" align="center"><b>Saldo</b></th>
                </tr>
            </thead>
            <tbody>';


        // ==========================================
        // SALDO AWAL
        // ==========================================
        $html .= '
            <tr style="background-color:#e9ecef;">
                <td width="72%" colspan="7" align="center">
                    <b>SALDO AWAL</b>
                </td>
                <td width="28%" align="center">
                    <b>
                        Rp' . number_format($saldoAwal, 0, ',', '.'). 
                    '</b>
                </td>
            </tr>';


        // ==========================================
        // TRANSAKSI
        // ==========================================
        $saldo = $saldoAwal;
        $no = 1;

        foreach ($transaksiJurnal as $trx) {

            // ------------------------------------------
            // Hitung saldo berjalan
            // ------------------------------------------
            if ($posisiNormal === 'debit') {

                if ($trx->posisi === 'debit') {
                    $saldo += $trx->nominal;
                } else {
                    $saldo -= $trx->nominal;
                }

            } else {

                if ($trx->posisi === 'kredit') {
                    $saldo += $trx->nominal;
                } else {
                    $saldo -= $trx->nominal;
                }
            }


            // ------------------------------------------
            // Data tampilan
            // ------------------------------------------
            $tanggal = HelperController::formatTanggalIndonesia(
                $trx->akuntansi_jurnal->tanggal_transaksi, 'd F Y'
                // $trx->akuntansi_jurnal->tanggal_transaksi, 'd F Y H:i:s'
            );

            $nomorJurnal = $trx->akuntansi_jurnal->nomor_jurnal;

            $petugas = optional($trx->akuntansi_jurnal->ms_pengguna)->nama ?? '-';

            $deskripsi = $trx->akuntansi_jurnal->deskripsi;

            $debit = $trx->posisi === 'debit' ? 'Rp' . number_format($trx->nominal, 0, ',', '.') : '-';

            $kredit = $trx->posisi === 'kredit' ? 'Rp' . number_format($trx->nominal, 0, ',', '.') : '-';

            $saldoFormatted = 'Rp' . number_format($saldo, 0, ',', '.');

            // ------------------------------------------
            // Row
            // ------------------------------------------
            $html .= '
                <tr>
                    <td width="3%" align="center">'. $no++ . '.</td>
                    <td width="10%">'. $tanggal . '</td>
                    <td width="10%">'. $nomorJurnal . '</td>
                    <td width="10%">'. $petugas . '</td>
                    <td width="39%">'. $deskripsi . '</td>
                    <td width="10%" align="right">' . $debit . '</td>
                    <td width="10%" align="right">' . $kredit . '</td>
                    <td width="8%" align="right">' . $saldoFormatted . '</td>
                </tr>';
        }

        // ==========================================
        // TOTAL MUTASI
        // ==========================================
        // $html .= '
        //     <tr style="background-color:#f2f2f2;">
        //         <td colspan="5" align="right">
        //             <b>TOTAL MUTASI</b>
        //         </td>

        //         <td align="right">
        //             <b>Rp'
        //                 . number_format($totalDebitPeriod, 0, ',', '.') .
        //             '</b>
        //         </td>

        //         <td align="right">
        //             <b>Rp'
        //                 . number_format($totalKreditPeriod, 0, ',', '.') . 
        //             '</b>
        //         </td>
        //         <td></td>
        //     </tr>';


        // ==========================================
        // SALDO AKHIR
        // ==========================================
        // $html .= '
        //     <tr style="background-color:#e9ecef;">
        //         <td width="72%" colspan="7" align="center">
        //             <b>SALDO AKHIR</b>
        //         </td>

        //         <td width="28%" align="center">
        //             <b>Rp'
        //                 . number_format($saldoAkhir, 0, ',', '.') . 
        //             '</b>

        //         </td>
        //     </tr>';

        $html .= '
            </tbody>
        </table>';


        // ==========================================
        // RENDER PDF
        // ==========================================
        $pdf::writeHTML($html, true, false, true, false, '');


        // ==========================================
        // NAMA FILE DINAMIS
        // ==========================================
        $namaRekening = preg_replace(
            '/[^A-Za-z0-9_-]+/',
            '_',
            trim($rekening->nama_rekening)
        );

        $namaFile =
            'Laporan_Buku_Besar_'
            . $selectedRekening
            . '_'
            . $namaRekening
            . '.pdf';

        $pdf::Output(
            $namaFile, 'I'
        );
    }
}
