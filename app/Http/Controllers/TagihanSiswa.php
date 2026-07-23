<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa as ModelsTagihanSiswa;
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
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $selectedTahunAjar);

        if ($selectedKelas) {
            $query->where('ms_kelas_id', $selectedKelas);
        }

        if ($search) {
            $query->whereHas('ms_siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', '%' . $search . '%');
            });
        }

        $tagihans = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')->get();

        // Inisialisasi total
        $totalTagihan = 0;
        $totalDibayarkan = 0;
        $totalKekurangan = 0;
        $jumlahItem = 0;

        // Ambil nama-nama
        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);
        $kelas = $selectedKelas ? Kelas::find($selectedKelas) : null;

        $judul = 'ADMINISTRASI TAGIHAN SISWA';
        $subjudul = ($jenjang->nama_jenjang ?? '-') .
            ' Tahun Ajaran ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        if ($kelas) {
            $subjudul .= ' | Kelas: ' . $kelas->nama_kelas;
        }

        // Inisialisasi TCPDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape
        $pdf::SetTitle('Administrasi Tagihan Siswa');
        $pdf::SetAuthor('TemanSekolah');
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
                    <td colspan="3" align="right">TOTAL</td>
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

    public function detailPDF(Request $request){
        $selectedSiswa = $request->selectedSiswa;
        $selectedKategori = $request->selectedKategori;

        // Validasi siswa wajib dipilih
        if (!$selectedSiswa) {
            return response()->json([
                'error' => 'Data siswa wajib dipilih'
            ], 400);
        }
        // Query tagihan siswa
        $query = ModelsTagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas'
            ])
            ->where('ms_penempatan_siswa_id', $selectedSiswa)
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar');

        // FILTER KATEGORI
        if ($selectedKategori) {
            $query->whereRelation(
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_kategori_tagihan_siswa_id',
                $selectedKategori
            );
        }

        // Ambil data
        $tagihans = $query->orderBy('ms_jenis_tagihan_siswa_id')->get();

        // Jika data tidak ditemukan
        if ($tagihans->isEmpty()) {
            return response()->json([
                'error' => 'Data tagihan siswa tidak ditemukan'
            ], 404);
        }

         // Ambil 
        $penempatanSiswa = PenempatanSiswa::find($selectedSiswa);

        $jenjang = Jenjang::find($penempatanSiswa->ms_jenjang_id);
        $tahunAjar = TahunAjar::find($penempatanSiswa->ms_tahun_ajar_id);
        
        $judul = 'ADMINISTRASI TAGIHAN SISWA';
        $subjudul = ($jenjang->nama_jenjang ?? '-') .
            ' Tahun Ajaran ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        // Ambil identitas siswa
        $siswa = $tagihans->first()->ms_penempatan_siswa->ms_siswa ?? null;
        $kelas = $tagihans->first()->ms_penempatan_siswa->ms_kelas ?? null;

        // Ambil kategori
        $kategori = $selectedKategori
            ? $tagihans->first()->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa ?? null
            : null;

        // Total
        $totalEstimasi = $tagihans->sum(
            fn($item) => $item->jumlah_tagihan_siswa ?? 0
        );

        $totalDibayarkan = $tagihans->sum(
            fn($item) => $item->total_bayar ?? 0
        );

        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

        // Inisialisasi TCPDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Detail Tagihan Siswa');
        $pdf::SetAuthor('TemanSekolah');
        $pdf::AddPage('L');

         // Judul Laporan
        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 1, $judul, 0, 1, 'C');

        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 1, $subjudul, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 8);

        $identitas = '
            <table cellpadding="1">
                <tr>
                    <td width="15%"><b>Nama Siswa</b></td>
                    <td width="35%">: ' . htmlspecialchars($siswa->nama_siswa ?? '-') . '</td>
                    <td width="15%"><b>Kelas</b></td>
                    <td width="35%">: ' . htmlspecialchars($kelas->nama_kelas ?? '-') . '</td>
                </tr>
                <tr>
                    <td width="15%"><b>Kategori</b></td>
                    <td width="35%">: ' . htmlspecialchars($kategori->nama_kategori_tagihan_siswa ?? 'Semua Kategori') . '</td>
                    <td width="15%"><b>Jumlah Tagihan</b></td>
                    <td width="35%">: ' . $tagihans->count() . ' item</td>
                </tr>
            </table>
        ';

        $pdf::writeHTML($identitas, true, false, true, false, '');


        // ==============================
        // TABEL DATA
        // ==============================

        $pdf::SetFont('times', '', 8);

        $html = '
            <table border="0.5" cellpadding="2" cellspacing="0" style="width:100%;">
                <thead>
                    <tr style="background-color:#f0f0f0;">
                        <th width="4%" align="center"><b>NO</b></th>
                        <th width="17%"><b>JENIS TAGIHAN</b></th>
                        <th width="13%"><b>KATEGORI</b></th>
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

        $no = 1;

        foreach ($tagihans as $item) {

            $jenisTagihan =
                $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa
                ?? '-';

            $namaKategori =
                $item->ms_jenis_tagihan_siswa
                    ->ms_kategori_tagihan_siswa
                    ->nama_kategori_tagihan_siswa ?? '-';

            $cicilan = $item->ms_jenis_tagihan_siswa->cicilan_status ?? '-';

            $estimasi = $item->jumlah_tagihan_siswa ?? 0;

            $dibayarkan = $item->total_bayar ?? 0;

            $kekurangan = $estimasi - $dibayarkan;

            $tanggalJatuhTempo = $item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo ?? null;

            // Format tanggal Indonesia
            $jatuhTempo = $tanggalJatuhTempo
                ? \App\Http\Controllers\HelperController::formatTanggalIndonesia($tanggalJatuhTempo, 'd F Y')
                : '-';

            $status = $item->status ?? '-';

            $html .= '
                <tr>
                    <td width="4%" align="center">
                        ' . $no . '
                    </td>

                    <td width="17%">
                        ' . htmlspecialchars($jenisTagihan) . '
                    </td>

                    <td width="13%">
                        ' . htmlspecialchars($namaKategori) . '
                    </td>

                    <td width="10%" align="center">
                        ' . htmlspecialchars($cicilan) . '
                    </td>

                    <td width="12%" align="center">
                        Rp' . number_format($estimasi, 0, ',', '.') . '
                    </td>

                    <td width="12%" align="center">
                        Rp' . number_format($dibayarkan, 0, ',', '.') . '
                    </td>

                    <td width="12%" align="center">
                        Rp' . number_format($kekurangan, 0, ',', '.') . '
                    </td>

                    <td width="10%" align="center">
                        ' . htmlspecialchars($jatuhTempo) . '
                    </td>

                    <td width="10%" align="center">
                        ' . htmlspecialchars($status) . '
                    </td>
                </tr>
            ';

            $no++;
        }

        // ==============================
        // TOTAL
        // ==============================
        $html .= '
                <tr style="background-color:#f0f0f0;">
                    <td colspan="4" align="right">
                        <b>TOTAL</b>
                    </td>

                    <td align="center">
                        <b>Rp' . number_format($totalEstimasi, 0, ',','.') . '</b>
                    </td>

                    <td align="center">
                        <b>Rp' . number_format($totalDibayarkan,0,',','.') . '</b>
                    </td>

                    <td align="center">
                        <b>Rp' . number_format($totalKekurangan,0,',','.') . '</b>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
        ';

        // Tulis tabel ke PDF
        $pdf::writeHTML($html, true, false, true, false, '');

        // ==============================
        // OUTPUT PDF
        // ==============================

        $namaFile = 'detail_tagihan_' .
            str_replace(
                ' ',
                '_',
                strtolower($siswa->nama_siswa ?? 'siswa')
            ) .
            '.pdf';

        $pdf::Output($namaFile, 'I');
    }
}
