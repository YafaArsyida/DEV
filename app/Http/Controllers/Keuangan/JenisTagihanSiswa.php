<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\JenisTagihanSiswa as ModelsJenisTagihanSiswa;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\TagihanSiswa;
use App\Models\TahunAjar;
use Illuminate\Http\Request;

use Elibyy\TCPDF\Facades\TCPDF;

class JenisTagihanSiswa extends Controller
{
    public function index()
    {
        return view('keuangan.tagihan.jenis-tagihan-siswa');
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
        $query = ModelsJenisTagihanSiswa::with('ms_kategori_tagihan_siswa')
            ->withCount(['ms_tagihan_siswa as jumlah_item'])
            ->withSum(['ms_tagihan_siswa as total_tagihan'], 'jumlah_tagihan_siswa')
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where(
                        'dt_transaksi_tagihan_siswa.status_transaksi',
                        '!=',
                        'dibatalkan'
                    );
                }
            ], 'jumlah_bayar')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar);

        if ($selectedKategoriTagihan) {
            $query->where('ms_kategori_tagihan_siswa_id', $selectedKategoriTagihan);
        }

        if ($search) {
            $query->where('nama_jenis_tagihan_siswa', 'like', '%' . $search . '%');
        }

        $tagihans = $query->orderBy('ms_kategori_tagihan_siswa_id')->get();

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
            $jumlah = $item->jumlah_item ?? 0;
            $estimasi = $item->total_tagihan ?? 0;
            $dibayar = $item->total_bayar ?? 0;
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
                <td width="12%">' . htmlspecialchars($item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-') . '</td>
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

    public function detailPDF(Request $request)
    {
        $selectedJenisTagihan = $request->selectedJenisTagihan;
        $selectedKelas = $request->selectedKelas;
        $search = $request->search;

        if (!$selectedJenisTagihan) {
            return response()->json([
                'error' => 'Data jenis tagihan wajib dipilih'
            ], 400);
        }

        $query = TagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas'
            ])
            ->select('ms_tagihan_siswa.*')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tagihan_siswa.ms_penempatan_siswa_id')
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_penempatan_siswa.ms_siswa_id')
            ->join('ms_kelas', 'ms_kelas.ms_kelas_id', '=', 'ms_penempatan_siswa.ms_kelas_id')
            ->where('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $selectedJenisTagihan)
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where(
                        'dt_transaksi_tagihan_siswa.status_transaksi',
                        '!=',
                        'dibatalkan'
                    );
                }
            ], 'jumlah_bayar');

        if ($selectedKelas) {
            $query->where('ms_kelas.ms_kelas_id', $selectedKelas);
        }

        if ($search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $search . '%');
        }

        $tagihans = $query
            ->orderBy('ms_kelas.nama_kelas')
            ->orderBy('ms_siswa.nama_siswa')
            ->get();

        if ($tagihans->isEmpty()) {
            return response()->json([
                'error' => 'Data tagihan jenis tidak ditemukan'
            ], 404);
        }

        $jenisTagihan = ModelsJenisTagihanSiswa::find($selectedJenisTagihan);
        $jenjang = Jenjang::find($jenisTagihan->ms_jenjang_id ?? null);
        $tahunAjar = TahunAjar::find($jenisTagihan->ms_tahun_ajar_id ?? null);
        $kelasFilter = $selectedKelas ? Kelas::find($selectedKelas) : null;

        $judul = 'DETAIL TAGIHAN JENIS';
        $subjudul = 'Jenis Tagihan: ' . ($jenisTagihan->nama_jenis_tagihan_siswa ?? '-') .
            ' | Jenjang: ' . ($jenjang->nama_jenjang ?? '-') .
            ' | Tahun Ajar: ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        if ($kelasFilter) {
            $subjudul .= ' | Kelas: ' . $kelasFilter->nama_kelas;
        }

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Detail Tagihan Jenis');
        $pdf::SetAuthor('TemanSekolah');
        $pdf::AddPage('L');

        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 1, $judul, 0, 1, 'C');

        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 1, $subjudul, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 8);

        $identitas = '
            <table cellpadding="1">
                <tr>
                    <td width="15%"><b>Jenis Tagihan</b></td>
                    <td width="35%">: ' . htmlspecialchars($jenisTagihan->nama_jenis_tagihan_siswa ?? '-') . '</td>
                    <td width="15%"><b>Kategori</b></td>
                    <td width="35%">: ' . htmlspecialchars($jenisTagihan->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-') . '</td>
                </tr>
                <tr>
                    <td width="15%"><b>Jenjang</b></td>
                    <td width="35%">: ' . htmlspecialchars($jenjang->nama_jenjang ?? '-') . '</td>
                    <td width="15%"><b>Tahun Ajar</b></td>
                    <td width="35%">: ' . htmlspecialchars($tahunAjar->nama_tahun_ajar ?? '-') . '</td>
                </tr>
                <tr>
                    <td width="15%"><b>Filter Kelas</b></td>
                    <td width="35%">: ' . htmlspecialchars($kelasFilter->nama_kelas ?? 'Semua Kelas') . '</td>
                    <td width="15%"><b>Jumlah Tagihan</b></td>
                    <td width="35%">: ' . $tagihans->count() . ' item</td>
                </tr>
            </table>
        ';

        $pdf::writeHTML($identitas, true, false, true, false, '');

        $html = '
            <table border="0.5" cellpadding="2" cellspacing="0" style="width:100%;">
                <thead>
                    <tr style="background-color:#f0f0f0;">
                        <th width="4%" align="center"><b>NO</b></th>
                        <th width="18%"><b>SISWA</b></th>
                        <th width="12%"><b>KELAS</b></th>
                        <th width="10%" align="center"><b>CICILAN</b></th>
                        <th width="12%" align="center"><b>ESTIMASI</b></th>
                        <th width="12%" align="center"><b>DIBAYARKAN</b></th>
                        <th width="12%" align="center"><b>KEKURANGAN</b></th>
                        <th width="10%" align="center"><b>JATUH TEMPO</b></th>
                        <th width="10%" align="center"><b>STATUS</b></th>
                    </tr>
                </thead>
                <tbody>
        ';

        $totalEstimasi = 0;
        $totalDibayarkan = 0;
        $totalKekurangan = 0;
        $no = 1;

        foreach ($tagihans as $item) {
            $estimasi = $item->jumlah_tagihan_siswa ?? 0;
            $dibayarkan = $item->total_bayar ?? 0;
            $kekurangan = $estimasi - $dibayarkan;

            $totalEstimasi += $estimasi;
            $totalDibayarkan += $dibayarkan;
            $totalKekurangan += $kekurangan;

            $tanggalJatuhTempo = $item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo ?? null;
            $jatuhTempo = $tanggalJatuhTempo
                ? \App\Http\Controllers\HelperController::formatTanggalIndonesia($tanggalJatuhTempo, 'd F Y')
                : '-';

            $html .= '
                <tr>
                    <td width="4%" align="center">' . $no . '</td>
                    <td width="18%">' . htmlspecialchars($item->ms_penempatan_siswa->ms_siswa->nama_siswa ?? '-') . '</td>
                    <td width="12%">' . htmlspecialchars($item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-') . '</td>
                    <td width="10%" align="center">' . htmlspecialchars($item->ms_jenis_tagihan_siswa->cicilan_status ?? '-') . '</td>
                    <td width="12%" align="center">Rp' . number_format($estimasi, 0, ',', '.') . '</td>
                    <td width="12%" align="center">Rp' . number_format($dibayarkan, 0, ',', '.') . '</td>
                    <td width="12%" align="center">Rp' . number_format($kekurangan, 0, ',', '.') . '</td>
                    <td width="10%" align="center">' . htmlspecialchars($jatuhTempo) . '</td>
                    <td width="10%" align="center">' . htmlspecialchars($item->status ?? '-') . '</td>
                </tr>
            ';

            $no++;
        }

        $html .= '
                <tr style="background-color:#f0f0f0;">
                    <td colspan="4" align="right"><b>TOTAL</b></td>
                    <td align="center"><b>Rp' . number_format($totalEstimasi, 0, ',', '.') . '</b></td>
                    <td align="center"><b>Rp' . number_format($totalDibayarkan, 0, ',', '.') . '</b></td>
                    <td align="center"><b>Rp' . number_format($totalKekurangan, 0, ',', '.') . '</b></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
        ';

        $pdf::writeHTML($html, true, false, true, false, '');

        $namaFile = 'detail_tagihan_jenis_' .
            str_replace(' ', '_', strtolower($jenisTagihan->nama_jenis_tagihan_siswa ?? 'tagihan')) .
            '.pdf';

        $pdf::Output($namaFile, 'I');
    }

}
