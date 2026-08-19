<?php

namespace App\Http\Controllers;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;

class AkuntansiLaporanArusKas extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-arus-kas.v_index');
    }

    public function cetakPDF(Request $request)
    {
        // | PARAMETER
        $selectedJenjang = $request->jenjang;
        $selectedRekening = $request->rekening;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // | VALIDASI
        if (!$selectedJenjang) {
            return response()->json([
                'error' => 'Jenjang wajib dipilih'
            ], 400);
        }

        if (!$startDate || !$endDate) {
            return response()->json([
                'error' => 'Periode tanggal wajib dipilih'
            ], 400);
        }

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        if ($start->greaterThan($end)) {
            return response()->json([
                'error' => 'Tanggal mulai harus lebih awal atau sama dengan tanggal selesai.'
            ], 400);
        }

        // | REKENING KAS / BANK
        $akunKasBank = [
            '11001',
            '11002',
        ];

        /*
        | Jika rekening dipilih → hanya rekening tersebut
        | Jika tidak → seluruh Kas & Bank
        */
        if ($selectedRekening) {
            if (!in_array($selectedRekening, $akunKasBank)) {
                return response()->json([
                    'error' => 'Rekening yang dipilih bukan rekening Kas/Bank.'
                ], 400);
            }
            $rekeningList = [$selectedRekening];
        } else {
            $rekeningList = $akunKasBank;
        }


        // | INFORMASI REKENING
        $namaRekening = 'Kas & Bank';

        if ($selectedRekening === '11001') {
            $namaRekening = 'Kas Besar';
        } elseif ($selectedRekening === '11002') {
            $namaRekening = 'Bank Sekolah';
        }

        // | DATA JENJANG
        $jenjang = Jenjang::find($selectedJenjang);

        if (!$jenjang) {
            return response()->json([
                'error' => 'Data jenjang tidak ditemukan.'
            ], 404);
        }

        $unitLabel = $jenjang->nama_jenjang ?? 'Unit';


        // | QUERY TRANSAKSI
        $transaksiJurnal = AkuntansiJurnalDetail::query()
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
            ->whereIn(
                'akuntansi_jurnal_detail.kode_rekening',$rekeningList
            )
            ->where(
                'akuntansi_jurnal.ms_jenjang_id',$selectedJenjang
            )
            ->where(
                'akuntansi_jurnal.ms_departemen_id', 'SEKOLAH'
            )
            ->whereBetween(
                'akuntansi_jurnal.tanggal_transaksi', [$start, $end]
            )
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->orderBy('akuntansi_jurnal.akuntansi_jurnal_id')
            ->orderBy(
                'akuntansi_jurnal_detail.akuntansi_jurnal_detail_id'
            )
            ->select('akuntansi_jurnal_detail.*')
            ->get();


        // | TOTAL KAS MASUK / KAS KELUAR
        $totalKasMasuk = $transaksiJurnal
            ->where('posisi', 'debit')
            ->sum('nominal');

        $totalKasKeluar = $transaksiJurnal
            ->where('posisi', 'kredit')
            ->sum('nominal');

        // | SALDO AWAL
        // | Semua transaksi Kas/Bank sebelum tanggal mulai.
        $saldoBefore = AkuntansiJurnalDetail::query()
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->whereIn(
                'akuntansi_jurnal_detail.kode_rekening',
                $rekeningList
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
                COALESCE(
                    SUM(
                        CASE
                            WHEN akuntansi_jurnal_detail.posisi = 'debit'
                            THEN akuntansi_jurnal_detail.nominal
                            ELSE 0
                        END
                    ),
                    0
                ) AS total_debit,

                COALESCE(
                    SUM(
                        CASE
                            WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                            THEN akuntansi_jurnal_detail.nominal
                            ELSE 0
                        END
                    ),
                    0
                ) AS total_kredit
            ")
            ->first();

        $saldoAwal =
            ($saldoBefore->total_debit ?? 0)
            -
            ($saldoBefore->total_kredit ?? 0);


        // | SALDO AKHIR
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;

        // | PERIODE
        $periode =
            'Periode ' . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
            . ' sampai '
            . HelperController::formatTanggalIndonesia($endDate,'d F Y');

        // | PDF
        $judul = 'Laporan Arus Kas - ' . $namaRekening;

        $pdf = new TCPDF('L','mm','A4',true,'UTF-8',false);

        $pdf::SetTitle($judul);
        $pdf::SetAuthor('TemanSekolah');

        // | Margin
        $pdf::SetMargins(10, 10, 10);
        $pdf::SetAutoPageBreak(true, 10);

        $pdf::AddPage('L');

        // | HEADER
        $pdf::SetFont('times', 'B', 14);

        $pdf::Cell(0, 7, $judul, 0, 1, 'C');

        $pdf::SetFont('times', '', 11);

        $pdf::Cell(0, 6, 'Yayasan Drul Khukama', 0, 1, 'C');

        $pdf::Cell(0, 6, 'Unit ' . $unitLabel, 0, 1, 'C');

        $pdf::SetFont('times', '', 10);

        $pdf::Cell(0, 6, $periode, 0, 1, 'C');

        $pdf::Ln(4);

        // | HTML TABLE
        $html = '
        <table border="0.5" cellpadding="4" cellspacing="0" style="border-collapse:collapse;width:100%;">

            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="5%" align="center">
                        <b>No</b>
                    </th>

                    <th width="13%">
                        <b>Tanggal</b>
                    </th>

                    <th width="14%">
                        <b>Akun</b>
                    </th>

                    <th width="14%">
                        <b>Petugas</b>
                    </th>

                    <th width="34%">
                        <b>Deskripsi</b>
                    </th>

                    <th width="10%" align="right">
                        <b>Kas Masuk</b>
                    </th>

                    <th width="10%" align="right">
                        <b>Kas Keluar</b>
                    </th>
                </tr>
            </thead>
            <tbody>
        ';

        // | TRANSAKSI
        $no = 1;

        foreach ($transaksiJurnal as $trx) {

            $tanggal = HelperController::formatTanggalIndonesia(
                $trx->akuntansi_jurnal->tanggal_transaksi,
                'd F Y'
            );

            $akun = ($trx->akuntansi_rekening->kode_rekening ?? '-')
                . ' - '
                . ($trx->akuntansi_rekening->nama_rekening ?? '-');

            $petugas = optional($trx->akuntansi_jurnal->ms_pengguna)->nama
                ?? '-';

            $deskripsi = $trx->akuntansi_jurnal->deskripsi
                ?? '-';

            $kasMasuk = $trx->posisi === 'debit' ? 'Rp ' . number_format($trx->nominal, 0, ',', '.') : '-';

            $kasKeluar = $trx->posisi === 'kredit' ? 'Rp ' . number_format($trx->nominal,0, ',', '.') : '-';

            $html .= '
                <tr>
                    <td width="5%" align="center">' . $no++ . '.</td>

                    <td width="13%">' . $tanggal . '</td>

                    <td width="14%">' . $akun . '</td>

                    <td width="14%">' . $petugas . '</td>

                    <td width="34%">' . $deskripsi . '</td>

                    <td width="10%" align="right">' . $kasMasuk . '</td>

                    <td width="10%" align="right">' . $kasKeluar . '</td>
                </tr>
            ';
        }


        // | JIKA TIDAK ADA TRANSAKSI
        if ($transaksiJurnal->isEmpty()) {
            $html .= '
                <tr>
                    <td colspan="7" align="center">
                        Tidak ada transaksi pada periode yang dipilih.
                    </td>
                </tr>
            ';
        }


        // | TOTAL
        $html .= '
            <tr style="background-color:#f2f2f2;">
                <td colspan="5" align="right">
                    <b>Total</b>
                </td>

                <td align="right">
                    <b>
                        Rp ' . number_format($totalKasMasuk,0,',','.') . '
                    </b>
                </td>

                <td align="right">
                    <b>
                        Rp ' . number_format($totalKasKeluar,0,',','.' ) . '
                    </b>
                </td>
            </tr>
        ';

        // | SALDO AWAL
        $html .= '
            <tr>

                <td colspan="5" align="right">
                    <b>Saldo Awal</b>
                </td>

                <td colspan="2" align="right">
                    <b>
                        Rp ' . number_format($saldoAwal,0,',','.') . '
                    </b>
                </td>
            </tr>
        ';

        // | SALDO AKHIR
        $html .= '
            <tr>

                <td colspan="5" align="right">
                    <b>Saldo Akhir</b>
                </td>

                <td colspan="2" align="right">
                    <b>
                        Rp ' . number_format($saldoAkhir,0,',','.') . '
                    </b>
                </td>
            </tr>
        ';

        $html .= '
            </tbody>
        </table>
        ';

        // | RENDER PDF
        $pdf::SetFont('times', '', 8);

        $pdf::writeHTML($html,true,false,true,false,'');

        // | OUTPUT
        $namaFile = 'Laporan-Arus-Kas-' . str_replace(' ', '-', $namaRekening) . '-' . $startDate . '-' . $endDate . '.pdf';
        
        $pdf::Output($namaFile,'I');
    }
}
