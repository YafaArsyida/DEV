<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Elibyy\TCPDF\Facades\TCPDF;

class LaporanPembayaranTagihanSiswa extends Controller
{
    public function index()
    {
        return view('keuangan.laporan.pembayaran-tagihan-siswa');
    }
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $selectedTahunAjar = $request->tahun;
        $selectedKelas = $request->kelas ?? [];
        $selectedKategori = $request->kategori ?? [];
        $selectedJenis = $request->jenis ?? [];
        $selectedMetode = $request->metode ?? [];
        $selectedPetugas = $request->petugas ?? [];
        $search = $request->search;

        $startDate = $request->start;
        $endDate = $request->end;

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate   = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $query = DetailTransaksiTagihanSiswa::with([
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa',
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas',
            'ms_transaksi_tagihan_siswa.ms_pengguna',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
        ])
            ->join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
            ->where(
                'dt_transaksi_tagihan_siswa.status_transaksi',
                '!=',
                'dibatalkan'
            )
            ->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa', function ($q) use ($selectedTahunAjar, $selectedJenjang) {
                $q->where('ms_tahun_ajar_id', $selectedTahunAjar)
                    ->where('ms_jenjang_id', $selectedJenjang);
            });

        if ($search) {
            $query->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', '%' . $search . '%');
            });
        }

        if ($selectedKelas) {
            $query->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas', function ($q) use ($selectedKelas) {
                $q->whereIn('ms_kelas_id', $selectedKelas);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            });
        }

        if ($selectedPetugas) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) use ($selectedPetugas) {
                $q->whereIn('ms_pengguna_id', $selectedPetugas);
            });
        }

        if ($selectedKategori) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) use ($selectedKategori) {
                $q->whereIn('ms_kategori_tagihan_siswa_id', $selectedKategori);
            });
        }

        if ($selectedJenis) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) use ($selectedJenis) {
                $q->whereIn('ms_jenis_tagihan_siswa_id', $selectedJenis);
            });
        }

        if ($selectedMetode) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($q) use ($selectedMetode) {
                $q->whereIn('metode_pembayaran', $selectedMetode);
            });
        }

        $laporans = $query->orderBy('ms_transaksi_tagihan_siswa.tanggal_transaksi', 'ASC')->get();
        $total = $laporans->sum('jumlah_bayar');

        // PDF
        $judul = 'Laporan Pembayaran Siswa';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start && $request->end) {
            $periode = 'Periode ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->start, 'd F Y') .
                ' sampai ' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($request->end, 'd F Y');
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
        $pdf::SetFont('times', '', 10);
        $pdf::MultiCell(0, 6, ($jenjang->deskripsi ?? '-'), 0, 'C');
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Ln(3);

        $pdf::SetFont('times', '', 9);
        $pdf::setCellHeightRatio(1.2);

        $html = '
        <table border="0.5" cellpadding="1" cellspacing="0" style="width:100%;">
            <thead>
                <tr style="background-color: #f5f5f5;">
                    <th align="center" width="3%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="20%">Siswa</th>
                    <th width="12%">Kelas</th>
                    <th width="15%">Tagihan</th>
                    <th width="10%">Petugas</th>
                    <th width="20%">Metode</th>
                    <th width="10%" align="right">Dibayarkan</th>
                </tr>
            </thead>
        <tbody>';

        $no = 1;
        foreach ($laporans as $item) {
            $html .= '
                <tr>
                    <td align="center" width="3%">' . $no++ . '</td>
                    <td width="10%">' . \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->ms_transaksi_tagihan_siswa->tanggal_transaksi, 'd F Y') . '</td>
                    <td width="20%">' . htmlspecialchars($item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_siswa->nama_siswa) . '</td>
                    <td width="12%">' . ($item->ms_transaksi_tagihan_siswa->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-') . '</td>
                    <td width="15%">' . htmlspecialchars($item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa) . '</td>
                    <td width="10%">' . ($item->ms_transaksi_tagihan_siswa->ms_pengguna->nama ?? '-') . '</td>
                    <td width="20%">' . $item->ms_transaksi_tagihan_siswa->metode_pembayaran . '</td>
                    <td width="10%" align="right">Rp' . number_format($item->jumlah_bayar, 0, ',', '.') . '</td>
                </tr>';
        }

        $html .= '
                <tr style="font-weight:bold; background-color:#f0f0f0;">
                    <td colspan="7" align="center">TOTAL</td>
                    <td align="right">Rp' . number_format($total, 0, ',', '.') . '</td>
                </tr>
                </tbody>
            </table>';

        $pdf::SetFont('times', '', 8);
        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_pembayaran_siswa.pdf', 'I');
    }
}
