<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JenisTagihanSiswa;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;

class TagihanJenis extends Controller
{
    public function index()
    {
        return view('KEUANGAN.tagihan-jenis.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $selectedKategoriTagihan = $request->kategori;
        $search = $request->search;

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Filter Jenjang dan Tahun Ajar wajib diisi'], 400);
        }

        // Query data
        $query = JenisTagihanSiswa::with('ms_tagihan_siswa')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar);

        if ($selectedKategoriTagihan) {
            $query->where('ms_kategori_tagihan_siswa_id', $selectedKategoriTagihan);
        }

        if ($search) {
            $query->where('nama_jenis_tagihan_siswa', 'like', '%' . $search . '%');
        }

        $tagihans = $query->get();

        // Inisialisasi total
        $totalSiswa = 0;
        $totalEstimasi = 0;
        $totalDibayarkan = 0;
        $totalKekurangan = 0;

        // Judul dan subjudul
        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $judul = 'Administrasi Jenis Tagihan Siswa';
        $subjudul = 'Jenjang: ' . ($jenjang->nama_jenjang ?? '-') .
            ' | Tahun Ajar: ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        // Inisialisasi PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage();
        $pdf::SetFont('times', '', 8);

        // Judul
        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 1, $judul, 0, 1, 'C');

        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 1, $subjudul, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 8);
        $html = '
        <table border="0.5" cellpadding="1" cellspacing="0" style="width:100%;">
            <thead>
                <tr style="background-color: #f5f5f5;">
                    <th align="center" width="3%">No</th>
                    <th width="25%">Jenis Tagihan</th>
                    <th width="12%">Kategori</th>
                    <th align="center" width="8%">Tagihan</th>
                    <th align="center" width="15%">Estimasi</th>
                    <th align="center" width="15%">Dibayarkan</th>
                    <th align="center" width="15%">Kekurangan</th>
                    <th align="center" width="7%">Lunas</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($tagihans as $item) {
            $jumlah = $item->ms_tagihan_siswa->pluck('ms_penempatan_siswa_id')->unique()->count();
            $estimasi = $item->total_tagihan_siswa();
            $dibayar = $item->total_tagihan_siswa_dibayarkan();
            $kekurangan = $estimasi - $dibayar;
            $persen = $estimasi > 0 ? round(($dibayar / $estimasi) * 100, 2) : 0;

            $totalSiswa += $jumlah;
            $totalEstimasi += $estimasi;
            $totalDibayarkan += $dibayar;
            $totalKekurangan += $kekurangan;

            $html .= '
            <tr>
                <td width="3%" align="center">' . $no++ . '</td>
                <td width="25%">
                    ' . htmlspecialchars($item->nama_jenis_tagihan_siswa) . '
                </td>
                <td width="12%">' . htmlspecialchars($item->nama_kategori_tagihan_siswa()) . '</td>
                <td width="8%" align="center">' . $jumlah . ' item</td>
                <td width="15%" align="center">Rp' . number_format($estimasi, 0, ',', '.') . '</td>
                <td width="15%" align="center">Rp' . number_format($dibayar, 0, ',', '.') . '</td>
                <td width="15%" align="center">Rp' . number_format($kekurangan, 0, ',', '.') . '</td>
                <td width="7%" align="center">' . $persen . '%</td>
            </tr>';
        }

        $totalPersen = $totalEstimasi > 0 ? round(($totalDibayarkan / $totalEstimasi) * 100, 2) : 0;

        $html .= '
        <tr style="font-weight: bold; background-color: #f0f0f0;">
            <td></td>
            <td></td>
            <td align="right">TOTAL</td>
            <td align="center">' . $totalSiswa . ' item</td>
            <td align="center">Rp' . number_format($totalEstimasi, 0, ',', '.') . '</td>
            <td align="center">Rp' . number_format($totalDibayarkan, 0, ',', '.') . '</td>
            <td align="center">Rp' . number_format($totalKekurangan, 0, ',', '.') . '</td>
            <td align="center">' . number_format($totalPersen, 2) . '%</td>
        </tr>
        </tbody>
    </table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('administrasi_jenis_tagihan_siswa.pdf', 'I');
    }
}
