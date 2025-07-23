<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;

use App\Models\DetailTransaksiTagihanSiswa;

class LaporanPembayaranTagihanSiswa extends Controller
{
    public function index()
    {
        return view('LAPORAN.pembayaran-tagihan-siswa.v_index');
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
        $startDate = $request->start ? Carbon::parse($request->start)->startOfDay() : null;
        $endDate = $request->end ? Carbon::parse($request->end)->endOfDay() : null;

        $query = DetailTransaksiTagihanSiswa::with([
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa',
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas',
            'ms_transaksi_tagihan_siswa.ms_pengguna',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
        ])
            ->join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
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
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Laporan Pembayaran Siswa');
        $pdf::AddPage();
        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 1, 'Laporan Pembayaran Tagihan Siswa', 0, 1, 'C');
        $pdf::Ln(2);

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
