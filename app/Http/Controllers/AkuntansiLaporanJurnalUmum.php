<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class AkuntansiLaporanJurnalUmum extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-jurnal-umum.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $data = AkuntansiJurnalDetail::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajaran_id', $selectedTahunAjar)
            ->when($startDate &&  $endDate, fn($q) => $q->whereBetween('tanggal_transaksi', [$startDate,  $endDate]))
            ->when($search, fn($q) => $q->where('deskripsi', 'like', "%{$search}%"))
            ->orderBy('tanggal_transaksi')
            ->get()
            ->groupBy(['deskripsi', 'nominal']);

        $judul = 'Laporan Jurnal Keuangan';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start_date && $request->end_date) {
            $periode = 'Periode ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->start_date, 'F Y') .
                ' sampai ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->end_date, 'F Y');
        } else {
            $periode = 'Semua Periode';
        }

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage('L');

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $yayasan, 0, 1, 'C');
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 9);
        $pdf::setCellHeightRatio(1.2);

        $html = '<table border="0.5" cellspacing="0" style="width:100%;">
        <thead>
            <tr style="background-color: #f5f5f5;">
                <th width="3%">No</th>
                <th width="10%">Tanggal</th>
                <th width="10%">Petugas</th>
                <th width="37%">Deskripsi</th>
                <th width="15%">Akun Debit</th>
                <th width="15%">Akun Kredit</th>
                <th width="10%" align="left">Nominal</th>
            </tr>
        </thead>
        <tbody>';

        $no = 1;
        foreach ($data as $deskripsi => $itemsByNominal) {
            foreach ($itemsByNominal as $nominal => $transaksi) {
                $debit = $transaksi->where('posisi', 'debit')->first();
                $kredit = $transaksi->where('posisi', 'kredit')->first();
                $tanggal = optional($transaksi->first())->tanggal_transaksi;
                $petugas = $debit->ms_pengguna->nama ?? ($kredit->ms_pengguna->nama ?? '-');

                $html .= '<tr>
                <td width="3%" align="center">' . $no++ . '.</td>
                <td width="10%">' . ($tanggal ? HelperController::formatTanggalIndonesia($tanggal, 'd F Y') : '-') . '</td>
                <td width="10%">' . htmlspecialchars($petugas) . '</td>
                <td width="37%">' . htmlspecialchars($deskripsi) . '</td>
                <td width="15%" align="left">' . ($debit->akuntansi_rekening->nama_rekening ?? '-') . '</td>
                <td width="15%" align="left">' . ($kredit->akuntansi_rekening->nama_rekening ?? '-') . '</td>
                <td width="10%" align="">Rp' . number_format($nominal, 0, ',', '.') . '</td>
            </tr>';
            }
        }

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_jurnal_keuangan.pdf', 'I');
    }
}
