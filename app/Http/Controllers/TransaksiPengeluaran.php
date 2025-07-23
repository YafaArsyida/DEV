<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiRekening;
use App\Models\Jenjang;
use App\Models\Pengeluaran;
use App\Models\TahunAjar;

use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class TransaksiPengeluaran extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.pengeluaran.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $selectedRekening = $request->rekening;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $query = Pengeluaran::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            });

        if (!empty($selectedRekening)) {
            $query->where('kode_rekening', $selectedRekening);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', '%' . $search . '%')
                    ->orWhereHas('akuntansi_rekening', function ($qr) use ($search) {
                        $qr->where('nama_rekening', 'like', '%' . $search . '%');
                    });
            });
        }

        $data = $query->orderBy('tanggal', 'ASC')->get();
        $total = $data->sum('nominal');

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);
        $rekening = $selectedRekening ? AkuntansiRekening::where('kode_rekening', $selectedRekening)->first() : null;

        $judul = 'Transaksi Pengeluaran';
        $subjudul = 'Jenjang: ' . ($jenjang->nama_jenjang ?? '-') .
            ' | Tahun Ajar: ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        if ($rekening) {
            $subjudul .= ' | Rekening: ' . $rekening->nama_rekening;
        }


        // Mulai PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage();
        $pdf::SetFont('times', '', 9);

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $subjudul, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 9);

        $html = '
            <table border="0.5" cellpadding="1" cellspacing="0" style="width:100%;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th width="3%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="55%">Transaksi</th>
                        <th width="10%">Petugas</th>
                        <th width="10%" align="right">Nominal</th>
                        <th width="12%" align="right">Total</th>
                    </tr>
                </thead>
                <tbody>';

        $saldo = 0;
        foreach ($data as $i => $item) {
            $tanggal = \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal, 'd F Y');
            $rekening = $item->akuntansi_rekening->nama_rekening ?? '-';
            $deskripsi = $item->deskripsi ?? '-';
            $metode = $item->metode_pembayaran;
            $petugas = $item->ms_pengguna->nama ?? '-';
            $nominal = $item->nominal;
            $saldo += $nominal;

            $html .= '
                <tr>
                    <td width="3%" align="center">' . ($i + 1) . '.</td>
                    <td width="10%">' . $tanggal . '</td>
                    <td width="55%">
                        <span style="margin:0;padding:0;">
                            <strong style="color:red;">RP' . number_format($nominal, 0, ',', '.') . '</strong> 
                            <em>– ' . htmlspecialchars($judul) . '</em><br>
                            <span style="font-size:9px;color:#888;">' . htmlspecialchars($deskripsi) . '</span>
                        </span>
                    </td>
                    <td width="10%">
                        <span style="margin:0;padding:0;">
                            ' . htmlspecialchars($metode) . '<br>
                            <span style="font-size:9px;color:#888;">' . htmlspecialchars($petugas) . '</span>
                        </span>
                    </td>
                    <td width="10%" align="right">
                        <span style="color:red;">RP' . number_format($nominal, 0, ',', '.') . '</span>
                    </td>
                    <td width="12%" align="right">
                        <span style="color:red;">RP' . number_format($saldo, 0, ',', '.') . '</span>
                    </td>
                </tr>';
        }

        $html .= '
            </tbody>
            </table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_pengeluaran.pdf', 'I');
    }
}
