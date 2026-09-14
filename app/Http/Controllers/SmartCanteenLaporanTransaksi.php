<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Controllers\HelperController;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class SmartCanteenLaporanTransaksi extends Controller
{
    public function index()
    {
        return view('SMARTCANTEEN.laporan-transaksi.v_index');
    }
    public function cetakPdf(Request $request)
    {
        $startDate  = $request->start_date;
        $endDate    = $request->end_date;
        $petugas    = $request->petugas;
        $sumber     = $request->sumber;
        $settlement = $request->settlement;
        $kantin     = $request->kantin;

        $query = TransaksiSmartCanteen::query();

        // STATUS TRANSAKSI 
        $query->where('status_transaksi', '!=', 'dibatalkan');

        // KANTIN
        if (!empty($kantin)) {
            $query->where('ms_kantin_id', $kantin);
        }

        // PETUGAS
        if (!empty($petugas)) {
            $query->where('ms_pengguna_id', $petugas);
        }

        // SUMBER DANA / JENJANG
        if ($sumber !== null && $sumber !== '') {
            if ($sumber === 'umum') {
                $query->whereNull('ms_jenjang_id');
            } else {
                $query->where('ms_jenjang_id', $sumber);
            }
        }

        // STATUS SETTLEMENT
        if ($settlement !== null && $settlement !== '') {
            $query->where('status_settlement', $settlement);
        }

        // PERIODE
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_transaksi', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $laporan = $query
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        $totalTransaksi = $laporan->sum('total_transaksi');

        // PDF init
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Laporan Transaksi smartCanteen');
        $pdf::AddPage('L');

        // Header
        $pdf::SetFont('times', 'B', 14);
        $pdf::Cell(0, 7, 'Laporan Transaksi smartCanteen', 0, 1, 'C');
        $pdf::Ln(2);

        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 6, "Periode: " . ($startDate ? Carbon::parse($startDate)->format('d/m/Y') : '-') .
            " s/d " . ($endDate ? Carbon::parse($endDate)->format('d/m/Y') : '-'), 0, 1, 'C');
        $pdf::Ln(5);

        // Tabel
       $html = '
            <table border="0.5" cellpadding="3">
                <thead>
                    <tr style="background-color:#f2f2f2;">
                        <th width="5%">No</th>
                        <th width="17%">Tanggal</th>
                        <th width="23%">Pembeli</th>
                        <th width="13%">Metode</th>
                        <th width="15%">Petugas</th>
                        <th width="15%" align="right">Nominal</th>
                        <th width="12%" align="center">Settlement</th>
                    </tr>
                </thead>
                <tbody>';

        $no = 1;

        foreach ($laporan as $item) {

            // Pembeli
            if ($item->user_type === 'siswa') {
                $pembeli = $item->ms_siswa->nama_siswa ?? '-';
                // $sub = $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '';
            } elseif ($item->user_type === 'pegawai') {
                $pembeli = $item->ms_pegawai->nama_pegawai ?? '-';
                // $sub = $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '';
            } else {
                $pembeli = 'Umum';
                // $sub = '';
            }

            // Petugas
            $petugas = $item->ms_pengguna->nama ?? '-';

            // Status Settlement
            if ($item->status_settlement === 'sudah') {
                $settlement = 'Sudah';

            } elseif ($item->status_settlement === 'belum') {
                $settlement = 'Menunggu';

            } else {
                $settlement = 'Langsung';
            }

            $html .= '
                <tr>
                    <td width="5%">' . $no++ . '.</td>

                    <td width="17%">
                        ' . HelperController::formatTanggalIndonesia($item->tanggal_transaksi, 'd F Y') . '
                    </td>

                    <td width="23%">
                        ' . htmlspecialchars($pembeli) . '
                    </td>

                    <td width="13%">
                        ' . htmlspecialchars($item->metode_pembayaran ?? '-') . '
                    </td>

                    <td width="15%">
                        ' . htmlspecialchars($petugas) . '
                    </td>

                    <td width="15%" align="right">
                        Rp' . number_format($item->total_transaksi,0,',','.') . '
                    </td>

                    <td width="12%" align="center">
                        ' . $settlement . '
                    </td>
                </tr>';
        }

        $html .= '
                <tr style="background-color:#f9f9f9;">
                    <td colspan="5" align="right">
                        <b>Total</b>
                    </td>

                    <td align="right">
                        <b>Rp' . number_format($totalTransaksi,0,',','.') . '</b>
                    </td>

                    <td></td>
                </tr>
            </tbody>
        </table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_smartCanteen.pdf');
    }
}
