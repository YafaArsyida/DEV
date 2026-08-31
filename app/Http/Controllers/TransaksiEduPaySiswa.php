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

        // 1. AMBIL TRANSAKSI TARGET
        $targetTransaksi = TransaksiEduPay::with([
            'ms_siswa',
            'ms_penempatan_siswa.ms_kelas',
            'ms_pengguna',
        ])
            ->where('ms_transaksi_edupay_id', $eduPayId)
            ->where('user_type', 'siswa')
            ->where('user_id', $user_id)
            ->first();

        if (!$targetTransaksi) {
            return response()->json([
                'error' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        // 2. AMBIL SEMUA TRANSAKSI SEBELUM TARGET
        // =========================================================
        $edupayTransaksi = TransaksiEduPay::where('user_type', 'siswa')
            ->where('user_id', $user_id)
            ->where('status_transaksi', '!=', 'dibatalkan')
            ->where(function ($q) use ($targetTransaksi) {
                $q->where('tanggal', '<', $targetTransaksi->tanggal)
                    ->orWhere(function ($sub) use ($targetTransaksi) {
                        $sub->where('tanggal', $targetTransaksi->tanggal)
                            ->where(
                                'ms_transaksi_edupay_id',
                                '<',
                                $targetTransaksi->ms_transaksi_edupay_id
                            );
                    });
            })
            ->orderBy('tanggal', 'ASC')
            ->orderBy('ms_transaksi_edupay_id', 'ASC')
            ->get();

        // 3. HITUNG SALDO SEBELUM TRANSAKSI
        $saldoSebelum = 0;

        foreach ($edupayTransaksi as $trx) {

            if (in_array($trx->jenis_transaksi, [
                'topup tunai',
                'topup online',
                'pengembalian dana',
            ])) {
                $saldoSebelum += $trx->nominal;
            }

            if (in_array($trx->jenis_transaksi, [
                'penarikan',
                'pembayaran',
                'kantin',
            ])) {
                $saldoSebelum -= $trx->nominal;
            }
        }

        // 4. HITUNG SALDO SETELAH TRANSAKSI
        if ($targetTransaksi->status_transaksi === 'dibatalkan') {

            // Transaksi dibatalkan tidak pernah memengaruhi saldo.
            $saldoSetelah = $saldoSebelum;

        } else {

            if (in_array($targetTransaksi->jenis_transaksi, [
                'topup tunai',
                'topup online',
                'pengembalian dana',
            ])) {
                $saldoSetelah = $saldoSebelum + $targetTransaksi->nominal;
            } elseif (in_array($targetTransaksi->jenis_transaksi, [
                'penarikan',
                'pembayaran',
                'kantin',
            ])) {
                $saldoSetelah = $saldoSebelum - $targetTransaksi->nominal;
            } else {
                // Jika ada jenis transaksi baru yang belum dimapping,
                // jangan mengubah saldo.
                $saldoSetelah = $saldoSebelum;
            }
        }

        // 5. TEMPLATE KUITANSI
        $kuitansi = KuitansiTransaksiEduPay::where(
            'ms_jenjang_id', $ms_jenjang_id
        )->first();

        if (!$kuitansi) {
            return response()->json([
                'error' => 'Template kuitansi tidak ditemukan'
            ], 404);
        }

        // 6. INISIALISASI TCPDF
        $pdf = new TCPDF();

        $pdf::SetTitle('Kuitansi Transaksi EduPay');
        $pdf::AddPage('P', [100, 300]);
        $pdf::SetFont('times', '', 12);

        // 7. LOGO
        $logoPath = storage_path('app/public/' . $kuitansi->logo);

        if (!file_exists($logoPath)) {
            return response()->json([
                'error' => 'Logo tidak ditemukan di path ' . $logoPath
            ], 404);
        }

        $logoBase64 =
            'data:image/' .
            pathinfo($logoPath, PATHINFO_EXTENSION) .
            ';base64,' .
            base64_encode(file_get_contents($logoPath));

        // 8. HEADER
        $htmlHeader = '
            <table border="0" cellpadding="0" cellspacing="0"
                style="width:98%; text-align:center;">
                <tr>
                    <td>
                        <img src="' . $logoBase64 . '" height="30px" />
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px; padding-top:2px;">
                        ' . $kuitansi->nama_institusi . '
                    </td>
                </tr>
                <tr>
                    <td style="font-size:8px; padding-top:2px; line-height:1.2;">
                        ' . $kuitansi->alamat . '<br>
                        ' . $kuitansi->kontak . '
                    </td>
                </tr>
            </table>
        ';

        $pdf::writeHTML($htmlHeader,true,false,true,false,'');

        // 9. JUDUL
        $pdf::SetFont('times', 'B', 12);
        $pdf::Cell(0, 5, $kuitansi->judul, 0, 1, 'C');

        $pdf::SetFont('times', 'B', 10);

        $judulTransaksi = strtoupper($targetTransaksi->jenis_transaksi). ' EDUPAY';

        // Tandai jika transaksi dibatalkan
        if ($targetTransaksi->status_transaksi === 'dibatalkan') {
            $judulTransaksi .= ' - DIBATALKAN';
        }

        $pdf::Cell(0, 5, $judulTransaksi, 0, 1, 'C');

        $pdf::Ln(4);

        // 10. NOMINAL
        $pdf::SetFont('times', 'B', 14);
        $pdf::Cell(0, 5, 'Rp' . number_format($targetTransaksi->nominal, 0, ', ', '.'), 0, 1, 'C');

        // 11. DESKRIPSI
        if ($targetTransaksi->deskripsi) {
            $pdf::Ln(1);

            $pdf::SetFont('times', 'I', 8);
            $pdf::MultiCell(0, 5, $targetTransaksi->deskripsi, 0, 'C');
        }

        $pdf::Ln(4);

        // 12. INFORMASI SISWA
        $pdf::SetFont('times', '', 10);

        $pdf::Cell(0, 5, 'Siswa : ' . $targetTransaksi->ms_siswa->nama_siswa, 0, 1, 'L');

        if ($targetTransaksi->ms_penempatan_siswa_id) {
            $kelas = $targetTransaksi->ms_penempatan_siswa?->ms_kelas?->nama_kelas;

            if ($kelas) {
                $pdf::Cell(0, 5, 'Kelas : ' . $kelas, 0, 1, 'L');
            }
        }

        // 13. INFORMASI SALDO
        $pdf::SetFont('times', 'B', 10);

        $pdf::Cell(0, 5, 'Saldo Sebelum Transaksi : Rp ' . number_format($saldoSebelum, 0, ',', '.'), 0, 1);

        $pdf::Cell(0, 5, 'Nominal Transaksi        : Rp ' . number_format($targetTransaksi->nominal, 0, ',', '.'), 0, 1);

        $pdf::Cell(0, 5, 'Saldo Setelah Transaksi : Rp ' . number_format($saldoSetelah, 0, ',', '.'), 0, 1);

        // 14. KETERANGAN STATUS
        if ($targetTransaksi->status_transaksi === 'dibatalkan') {
            $pdf::Ln(2);

            $pdf::SetFont('times', 'B', 9);

            $pdf::MultiCell(0, 5, 'TRANSAKSI INI TELAH DIBATALKAN DAN TIDAK MEMENGARUHI SALDO EDUPAY.', 0, 'C');
        }

        $pdf::Ln(2);

        // 15. FOOTER
        $pdf::SetFont('times', '', 9);

        $pdf::MultiCell(0, 5, $kuitansi->pesan, 0, 'C');

        $pdf::Ln(2);

        $pdf::Cell(0, 5, $kuitansi->tempat . ', ' . HelperController::formatTanggalIndonesia($targetTransaksi->tanggal, 'd F Y'), 0, 1, 'C');

        $pdf::Ln(10);

        $pdf::Cell(0, 5, $targetTransaksi->ms_pengguna?->nama ?? '-', 0, 1, 'C');

        $pdf::Ln(10);

        // 16. OUTPUT PDF
        $pdf::Output(
            'kuitansi_transaksi_edupay.pdf',
            'I'
        );
    }
}
