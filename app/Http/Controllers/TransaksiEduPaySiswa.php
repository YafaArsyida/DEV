<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KuitansiTransaksiEduPay;
use App\Models\TransaksiEduPay;
use Elibyy\TCPDF\Facades\TCPDF;
use Carbon\Carbon;

class TransaksiEduPaySiswa extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.edupay-siswa.v_index');
    }
    public function kuitansiPDF($eduPayId)
    {
        $ms_jenjang_id = request()->query('selectedJenjang');
        $user_id = request()->query('userId');

        // Ambil transaksi target
        $targetTransaksi = TransaksiEduPay::with(['ms_siswa', 'ms_penempatan_siswa.ms_kelas', 'ms_pengguna'])
            ->where('ms_transaksi_edupay_id', $eduPayId)
            ->where('user_id', $user_id)
            ->first();

        if (!$targetTransaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        // Ambil semua transaksi sebelum & sampai target (urut ASC)
        $edupayTransaksi = TransaksiEduPay::where('user_id', $user_id)
            ->where(function ($q) use ($targetTransaksi) {
                $q->where('tanggal', '<', $targetTransaksi->tanggal)
                    ->orWhere(function ($sub) use ($targetTransaksi) {
                        $sub->where('tanggal', $targetTransaksi->tanggal)
                            ->where('ms_transaksi_edupay_id', '<', $targetTransaksi->ms_transaksi_edupay_id);
                    });
            })
            ->orderBy('tanggal', 'ASC')
            ->orderBy('ms_transaksi_edupay_id', 'ASC')
            ->get();

        // ============================
        // 1️⃣ Saldo sebelum transaksi
        // ============================
        $saldoSebelum = 0;
        foreach ($edupayTransaksi as $trx) {
            if (in_array($trx->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
                $saldoSebelum += $trx->nominal;
            } elseif (in_array($trx->jenis_transaksi, ['penarikan', 'pembayaran', 'kantin'])) {
                $saldoSebelum -= $trx->nominal;
            }
        }

        // ============================
        // 2️⃣ Saldo setelah transaksi
        // ============================
        if (in_array($targetTransaksi->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
            $saldoSetelah = $saldoSebelum + $targetTransaksi->nominal;
        } else {
            $saldoSetelah = $saldoSebelum - $targetTransaksi->nominal;
        }

        // dd($eduPayId);
        $kuitansi = KuitansiTransaksiEduPay::where('ms_jenjang_id', $ms_jenjang_id)->first();

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
        $pdf::Cell(0, 5, strtoupper($targetTransaksi->jenis_transaksi) . ' EDUPAY', 0, 1, 'C');
        $pdf::Ln(4);
        $pdf::SetFont('times', 'B', 14);
        $pdf::Cell(0, 5, 'Rp' . number_format($targetTransaksi->nominal, 0, ',', '.'), 0, 1, 'C');
        if ($targetTransaksi->deskripsi) {
            $pdf::Ln(1);
            $pdf::SetFont('times', 'I', 8);
            $pdf::MultiCell(0, 5, $targetTransaksi->deskripsi, 0, 'C');
        }
        $pdf::Ln(4);
        // Salam Pmbuka
        // $pdf::SetFont('times', 'I', 8);
        // $pdf::Cell(0, 5, 'Assalamu’alaikum Wr. Wb.', 0, 1, 'L');

        // Informasi Pembayaran
        $pdf::SetFont('times', '', 10);
        $pdf::Cell(0, 5, 'Siswa : ' . $targetTransaksi->ms_siswa->nama_siswa, 0, 1, 'L');
        if ($targetTransaksi->ms_penempatan_siswa_id) {
            $pdf::Cell(0, 5, 'Kelas : ' . $targetTransaksi->ms_penempatan_siswa->ms_kelas->nama_kelas, 0, 1, 'L');
        }
        $pdf::SetFont('times', 'B', 10);
        $pdf::Cell(0, 5, 'Saldo Sebelum Transaksi : Rp ' . number_format($saldoSebelum, 0, ',', '.'), 0, 1);
        $pdf::Cell(0, 5, 'Nominal Transaksi      : Rp ' . number_format($targetTransaksi->nominal, 0, ',', '.'), 0, 1);
        $pdf::Cell(0, 5, 'Saldo Setelah Transaksi: Rp ' . number_format($saldoSetelah, 0, ',', '.'), 0, 1);



        $pdf::Ln(2);
        // Footer
        $pdf::SetFont('times', '', 9);
        $pdf::MultiCell(0, 5, $kuitansi->pesan, 0, 'C');
        $pdf::Ln(2);
        $pdf::Cell(0, 5, $kuitansi->tempat . ', ' .  HelperController::formatTanggalIndonesia($targetTransaksi->tanggal, 'd F Y'), 0, 1, 'C');
        $pdf::Ln(5);
        $pdf::Cell(0, 5, $targetTransaksi->ms_pengguna->nama, 0, 1, 'C');
        $pdf::Ln(10);

        // Penutup
        // $pdf::SetFont('times', 'I', 8);
        // $pdf::Cell(0, 5, 'Wassalamu’alaikum Wr. Wb.', 0, 1, 'L');
        // Menampilkan PDF langsung ke browser
        $pdf::Output('kuitansi_transaksi_edupay.pdf', 'I');
    }
}
