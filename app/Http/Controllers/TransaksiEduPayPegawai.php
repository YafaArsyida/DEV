<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KuitansiTransaksiEduPay;
use App\Models\TransaksiEduPay;
use Elibyy\TCPDF\Facades\TCPDF;

class TransaksiEduPayPegawai extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.edupay-pegawai.v_index');
    }
    public function kuitansiPDF($eduPayId)
    {
        $ms_jenjang_id = request()->query('selectedJenjang');
        $user_id = request()->query('userId');

        // Ambil data transaksi EduPay berdasarkan ID
        $edupayTransaksi = TransaksiEduPay::with(['ms_pegawai', 'ms_pegawai.ms_jabatan', 'ms_pengguna'])->where('user_id', $user_id)
            ->get();

        $actualTransaction = TransaksiEduPay::where('ms_transaksi_edupay_id', $eduPayId)->first();

        // Pastikan data transaksi ditemukan
        if ($edupayTransaksi->isEmpty()) {
            return response()->json(['error' => 'Transaksi tidak ditemukan ini'], 404);
        }

        // Cari transaksi spesifik berdasarkan ID
        $targetTransaksi = $edupayTransaksi->where('ms_transaksi_edupay_id', $eduPayId)->first();

        if (!$targetTransaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        // Hitung saldo berdasarkan urutan transaksi
        $saldo = 0;
        foreach ($edupayTransaksi as $transaksi) {
            // Periksa jenis transaksi dan update saldo sesuai dengan jenisnya
            switch ($transaksi->jenis_transaksi) {
                case 'topup tunai':
                case 'topup online':
                case 'pengembalian dana':
                    $saldo += $transaksi->nominal;
                    break;
                case 'penarikan':
                case 'pembayaran':
                case 'kantin':
                    $saldo -= $transaksi->nominal;
                    break;
            }

            if ($transaksi->ms_transaksi_edupay_id === $eduPayId) {
                break;
            }
        }

        // dd($eduPayId);
        $kuitansi = KuitansiTransaksiEduPay::where('ms_jenjang_id', $ms_jenjang_id)->first();

        // Pastikan transaksi ditemukan
        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }
        if (!$kuitansi) {
            return response()->json(['error' => 'Template kuitansi tidak ditemukan'], 404);
        }

        // Inisialisasi TCPDF
        $pdf = new TCPDF();

        $pdf::SetTitle('Kuitansi Transaksi Edupay');
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
        if ($actualTransaction->deskripsi) {
            $pdf::Ln(1);
            $pdf::SetFont('times', 'I', 8);
            $pdf::MultiCell(0, 5, $actualTransaction->deskripsi, 0, 'C');
        }
        $pdf::Ln(4);
        // Salam Pmbuka
        // $pdf::SetFont('times', 'I', 8);
        // $pdf::Cell(0, 5, 'Assalamu’alaikum Wr. Wb.', 0, 1, 'L');

        // Informasi Pembayaran
        $pdf::SetFont('times', '', 10);
        $pdf::Cell(0, 5, 'Pegawai : ' . $actualTransaction->ms_pegawai->nama_pegawai, 0, 1, 'L');
        if ($actualTransaction->user_id) {
            $pdf::Cell(0, 5, 'Jabatan : ' . $actualTransaction->ms_pegawai->ms_jabatan->nama_jabatan, 0, 1, 'L');
        }
        $pdf::SetFont('times', 'B', 10);
        $pdf::Cell(0, 5, 'Saldo : Rp ' . number_format($saldo, 0, ',', '.'), 0, 1, 'L');

        $pdf::Ln(2);
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
        $pdf::Output('kuitansi_transaksi_edupay.pdf', 'I');
    }
}
