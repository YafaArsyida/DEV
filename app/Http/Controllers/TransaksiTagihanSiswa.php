<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KuitansiPembayaranTagihanSiswa;
use App\Models\TransaksiTagihanSiswa as ModelsTransaksiTagihanSiswa;
use Elibyy\TCPDF\Facades\TCPDF;

class TransaksiTagihanSiswa extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.tagihan-siswa.v_index');
    }
    public function kuitansiPDF($transaksiId)
    {
        $selectedJenjang = request()->query('selectedJenjang');
        // return response()->json(['transaksiId' => $transaksiId]);

        // Ambil data transaksi berdasarkan ID
        $transaksi = ModelsTransaksiTagihanSiswa::find($transaksiId);
        $kuitansi = KuitansiPembayaranTagihanSiswa::where('ms_jenjang_id', $selectedJenjang)->first();

        // Pastikan transaksi ditemukan
        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }
        if (!$kuitansi) {
            return response()->json(['error' => 'Template kuitansi tidak ditemukan'], 404);
        }

        // Inisialisasi TCPDF
        $pdf = new TCPDF();

        $pdf::SetTitle('Kuitansi Transaksi');
        $pdf::AddPage('P', [100, 300]); // 'P' untuk Portrait, ukuran dalam milimeter (100mm x 150mm)
        $pdf::SetFont('times', '', 12);

        $logoPath = storage_path('app/public/' . $kuitansi->logo);
        $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));

        $htmlHeader = '
            <table border="0" cellpadding="0" cellspacing="0" style="width:98%; text-align:center;">
                <tr>
                    <td>
                        <img src="' . $logoBase64 . '" height="30px" />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; padding-top: 2px;">
                        ' . $kuitansi->nama_institusi . '
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 8px; padding-top: 2px; line-height: 1.2;">
                        ' . $kuitansi->alamat . '<br>
                        ' . $kuitansi->kontak . '
                    </td>
                </tr>
            </table>
        ';
        // Menulis HTML ke dalam PDF
        $pdf::writeHTML($htmlHeader, true, false, true, false, '');

        // Subjudul
        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 5, $kuitansi->judul, 0, 1, 'C');
        $pdf::SetFont('times', 'B', 10);
        $pdf::Cell(0, 5, strtoupper($transaksi->metode_pembayaran), 0, 1, 'C');
        $pdf::Ln(2);
        // Salam Pmbuka
        // $pdf::SetFont('times', 'I', 8);
        // $pdf::Cell(0, 5, 'Assalamu’alaikum Wr. Wb.', 0, 1, 'L');

        // Informasi Pembayaran
        $pdf::SetFont('times', '', 10);
        $pdf::Cell(0, 5, 'Siswa : ' . $transaksi->ms_penempatan_siswa->ms_siswa->nama_siswa, 0, 1, 'L');
        $pdf::Cell(0, 5, 'Kelas : ' . $transaksi->ms_penempatan_siswa->ms_kelas->nama_kelas, 0, 1, 'L');
        $pdf::Ln(2);

        // HTML untuk tabel transaksi
        $html = '
        <table border="0" cellpadding="1" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th width="10%" style="text-align: center; border-bottom: 1px solid #000;">No</th>
                    <th width="60%" style="text-align: left; border-bottom: 1px solid #000;">Transaksi</th>
                    <th width="30%" style="text-align: right; border-bottom: 1px solid #000;">Nominal</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $total = 0;
        foreach ($transaksi->dt_transaksi_tagihan_siswa as $detail) {
            $jumlah = $detail->jumlah_bayar;
            $total += $jumlah;

            $html .= '
                <tr>
                    <td style="width:10%; text-align: center;">' . $no++ . '</td>
                    <td style="width:60%; text-align: left;">' . htmlspecialchars($detail->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa) . '</td>
                    <td style="width:30%; text-align: right;">Rp' . number_format($jumlah, 0, ',', '.') . '</td>
                </tr>';
        }

        $total += $transaksi->infaq;
        if ($transaksi->infaq > 0) {
            $html .= '
            <tr>
                <td colspan="2" style="border-top: 1px solid #000; text-align: right;">Infaq</td>
                <td style="border-top: 1px solid #000; text-align: right;">Rp' . number_format($transaksi->infaq, 0, ',', '.') . '</td>
            </tr>';
        }
        $html .= '
            <tr>
                <td colspan="2" style="border-top: 1px solid #000; font-weight: bold; text-align: right;">Total</td>
                <td style="border-top: 1px solid #000; font-weight: bold; text-align: right;">Rp' . number_format($total, 0, ',', '.') . '</td>
            </tr>';

        $html .= '
            </tbody>
        </table>';

        // Menampilkan HTML ke dalam PDF
        $pdf::writeHTML($html, true, false, true, false, '');

        // Footer
        $pdf::SetFont('times', '', 9);
        $pdf::MultiCell(0, 5, $kuitansi->pesan, 0, 'C');
        $pdf::Ln(1);
        $pdf::Cell(0, 5, $kuitansi->tempat . ', ' .  HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi, 'd F Y'), 0, 1, 'C');
        $pdf::Ln(5);
        $pdf::Cell(0, 5, $transaksi->ms_pengguna->nama, 0, 1, 'C');
        $pdf::Ln(10);

        // Penutup
        // $pdf::SetFont('times', 'I', 8);
        // $pdf::Cell(0, 5, 'Wassalamu’alaikum Wr. Wb.', 0, 1, 'L');
        // Menampilkan PDF langsung ke browser
        $pdf::Output('histori_transaksi.pdf', 'I');
    }
}
