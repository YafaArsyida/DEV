<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EduPaySiswa;
use App\Models\KuitansiEduPaySiswa;
use Elibyy\TCPDF\Facades\TCPDF;

class TransaksiEduPaySiswa extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.edupay-siswa.v_index');
    }
    public function kuitansiPDF($eduPayId)
    {
        $ms_jenjang_id = request()->query('selectedJenjang');
        $ms_siswa_id = request()->query('selectedSiswa');

        // Ambil data transaksi EduPay berdasarkan ID siswa
        $edupayTransaksi = EduPaySiswa::where('ms_siswa_id', $ms_siswa_id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('ms_edupay_siswa_id', 'asc')
            ->get();

        $actualTransaction = EduPaySiswa::where('ms_edupay_siswa_id', $eduPayId)->first();

        // Pastikan data transaksi ditemukan
        if ($edupayTransaksi->isEmpty()) {
            return response()->json(['error' => 'Transaksi tidak ditemukan ini'], 404);
        }

        // Cari transaksi spesifik berdasarkan ID
        $targetTransaksi = $edupayTransaksi->where('ms_edupay_siswa_id', $eduPayId)->first();

        if (!$targetTransaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        // Hitung saldo berdasarkan urutan transaksi
        $saldo = 0;
        foreach ($edupayTransaksi as $transaksi) {
            // Periksa jenis transaksi dan update saldo sesuai dengan jenisnya
            switch ($transaksi->jenis_transaksi) {
                case 'topup':
                case 'pengembalian dana':
                case 'topup online': // Topup online juga dianggap menambah saldo
                    $saldo += $transaksi->nominal;
                    break;
                case 'penarikan':
                case 'pembayaran':
                    $saldo -= $transaksi->nominal;
                    break;
            }

            // Simpan saldo saat mencapai transaksi yang diminta
            if ($transaksi->ms_edupay_siswa_id === $targetTransaksi->ms_edupay_siswa_id) {
                break;
            }
        }

        // dd($eduPayId);
        $kuitansi = KuitansiEduPaySiswa::where('ms_jenjang_id', $ms_jenjang_id)->first();

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
        if (!file_exists($logoPath)) {
            return response()->json(['error' => 'Logo tidak ditemukan di path ' . $logoPath], 404);
        }

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
        $pdf::Cell(0, 5, strtoupper($actualTransaction->jenis_transaksi) . ' EDUPAY', 0, 1, 'C');
        $pdf::Ln(4);
        $pdf::SetFont('times', 'B', 14);
        $pdf::Cell(0, 5, 'Rp' . number_format($actualTransaction->nominal, 0, ',', '.'), 0, 1, 'C');
        $pdf::Ln(4);
        // Salam Pmbuka
        // $pdf::SetFont('times', 'I', 8);
        // $pdf::Cell(0, 5, 'Assalamu’alaikum Wr. Wb.', 0, 1, 'L');

        // Informasi Pembayaran
        $pdf::SetFont('times', '', 10);
        $pdf::Cell(0, 5, 'Siswa : ' . $transaksi->ms_siswa->nama_siswa, 0, 1, 'L');
        $pdf::Cell(0, 5, 'Kelas : ' . $transaksi->ms_penempatan_siswa->ms_kelas->nama_kelas, 0, 1, 'L');
        if ($actualTransaction->deskripsi) {
            $pdf::MultiCell(0, 5, 'Keterangan : ' .  $actualTransaction->deskripsi, 0, 'L');
        }
        $pdf::SetFont('times', 'B', 10);
        $pdf::Cell(0, 5, 'Saldo : Rp ' . number_format($saldo, 0, ',', '.'), 0, 1, 'L');

        $pdf::Ln(4);
        // Footer
        $pdf::SetFont('times', '', 9);
        $pdf::MultiCell(0, 5, $kuitansi->pesan, 0, 'C');
        $pdf::Ln(2);
        $pdf::Cell(0, 5, $kuitansi->tempat . ', ' .  HelperController::formatTanggalIndonesia($transaksi->tanggal, 'd F Y'), 0, 1, 'C');
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
