<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;

class TagihanSiswa extends Controller
{
    public function index()
    {
        return view('KEUANGAN.tagihan-siswa.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $selectedKelas = $request->kelas;
        $search = $request->search;

        // Validasi minimal jenjang dan tahun ajar
        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Filter jenjang dan tahun ajar wajib diisi'], 400);
        }

        // Ambil data siswa dengan filter
        $query = PenempatanSiswa::with(['ms_siswa', 'ms_kelas'])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar);

        if ($selectedKelas) {
            $query->where('ms_kelas_id', $selectedKelas);
        }

        if ($search) {
            $query->whereHas('ms_siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', '%' . $search . '%');
            });
        }

        $tagihans = $query->get();

        // Inisialisasi total
        $totalTagihan = 0;
        $totalDibayarkan = 0;
        $totalKekurangan = 0;
        $jumlahItem = 0;

        // Ambil nama-nama
        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);
        $kelas = $selectedKelas ? Kelas::find($selectedKelas) : null;

        $judul = 'Administrasi Tagihan Siswa';
        $subjudul = 'Jenjang: ' . ($jenjang->nama_jenjang ?? '-') .
            ' | Tahun Ajar: ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        if ($kelas) {
            $subjudul .= ' | Kelas: ' . $kelas->nama_kelas;
        }

        // Inisialisasi TCPDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape
        $pdf::SetTitle('Administrasi Tagihan Siswa');
        $pdf::AddPage();
        $pdf::SetFont('times', '', 8);

        // Judul Laporan
        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 1, $judul, 0, 1, 'C');

        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 1, $subjudul, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 8);
        // Header Tabel
        $html = '
            <table border="0.5" cellpadding="1" cellspacing="0" style="width:100%;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th width="3%">No</th>
                        <th width="25%">Siswa</th>
                        <th width="12%">Kelas</th>
                        <th align="center" width="8%">Tagihan</th>
                        <th align="center" width="15%">Estimasi</th>
                        <th align="center" width="15%">Dibayarkan</th>
                        <th align="center" width="15%">Kekurangan</th>
                        <th align="center" width="7%">Lunas</th>
                    </tr>
                </thead>
                <tbody>
        ';

        $no = 1;

        foreach ($tagihans as $item) {
            $nama = $item->ms_siswa->nama_siswa;
            $kelas = $item->ms_kelas->nama_kelas ?? '-';
            $jumlah = $item->jumlah_jenis_tagihan_siswa();
            $tagihan = $item->total_tagihan_siswa();
            $dibayar = $item->total_dibayarkan();
            $kekurangan = $tagihan - $dibayar;
            $persen = $tagihan > 0 ? round(($dibayar / $tagihan) * 100, 2) : 0;

            $totalTagihan += $tagihan;
            $totalDibayarkan += $dibayar;
            $totalKekurangan += $kekurangan;
            $jumlahItem += $jumlah;

            $html .= '
            <tr>
                <td width="3%" align="center">' . $no . '</td>
                <td width="25%">' . htmlspecialchars($nama) . '</td>
                <td width="12%">' . htmlspecialchars($kelas) . '</td>
                <td width="8%" align="center">' . $jumlah . ' item</td>
                <td width="15%" align="center">Rp' . number_format($tagihan, 0, ',', '.') . '</td>
                <td width="15%" align="center">Rp' . number_format($dibayar, 0, ',', '.') . '</td>
                <td width="15%" align="center">Rp' . number_format($kekurangan, 0, ',', '.') . '</td>
                <td width="7%" align="center">' . $persen . '%</td>
            </tr>';
            $no++;
        }

        // Hitung total persen akhir
        $totalPersen = $totalTagihan > 0 ? round(($totalDibayarkan / $totalTagihan) * 100, 2) : 0;
        $html .= '
                <tr style="font-weight:bold; background-color:#f0f0f0;">
                    <td></td>
                    <td></td>
                    <td align="right">TOTAL</td>
                    <td align="center">' . $jumlahItem . ' item</td>
                    <td align="center">Rp' . number_format($totalTagihan, 0, ',', '.') . '</td>
                    <td align="center">Rp' . number_format($totalDibayarkan, 0, ',', '.') . '</td>
                    <td align="center">Rp' . number_format($totalKekurangan, 0, ',', '.') . '</td>
                    <td align="center">' . number_format($totalPersen, 2) . '%</td>
                </tr>
            </tbody>
        </table>';

        // Tulis ke PDF
        $pdf::writeHTML($html, true, false, true, false, '');

        // Output
        $pdf::Output('administrasi_tagihan_siswa.pdf', 'I'); // Inline view di browser
    }
}
