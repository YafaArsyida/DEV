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
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $petugas = $request->petugas;
        $pembeli = $request->pembeli;

        $query = TransaksiSmartCanteen::query();

        if (!empty($petugas)) {
            $query->where('ms_pengguna_id', $petugas);
        }
        if (!empty($pembeli)) {
            $query->where('user_type', $pembeli);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_transaksi', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $laporan = $query->orderBy('tanggal_transaksi', 'asc')->get();
        $totalTransaksi = $query->sum('total_transaksi');

        // PDF init
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Laporan Transaksi smartCanteen');
        $pdf::AddPage();

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
                    <th width="20%">Tanggal</th>
                    <th width="25%">Pembeli</th>
                    <th width="30%">Transaksi</th>
                    <th align="right" width="20%">Nominal</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($laporan as $item) {
            // Pembeli (siswa / pegawai)
            if ($item->user_type == 'siswa') {
                $pembeli = $item->ms_siswa->nama_siswa ?? '-';
                $sub = $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '';
            } elseif ($item->user_type == 'pegawai') {
                $pembeli = $item->ms_pegawai->nama_pegawai ?? '-';
                $sub = $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '';
            } else {
                $pembeli = '-';
                $sub = '';
            }

            $html .= '<tr>
                        <td width="5%">' . $no++ . '.</td>
                        <td width="20%">' . HelperController::formatTanggalIndonesia($item->tanggal_transaksi, 'd F Y') .  '</td>
                        <td width="25%">' . $pembeli . '<br>' . $sub . '</td>
                        <td width="30%">' . $item->metode_pembayaran . ' - ' . $item->ms_pengguna->nama . '</td>
                        <td width="20%" align="right">Rp' . number_format($item->total_transaksi, 0, ',', '.') . '</td>
                    </tr>';
        }

        $html .= '
                        <tr style="background-color:#f9f9f9;">
                            <td colspan="4" align="right"><b>Total</b></td>
                            <td align="right"><b>Rp' . number_format($totalTransaksi, 0, ',', '.') . '</b></td>
                        </tr>
                    ';

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_smartCanteen.pdf');
    }
}
