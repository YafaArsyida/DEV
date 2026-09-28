<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnal;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Elibyy\TCPDF\Facades\TCPDF;

class AkuntansiLaporanJurnalUmum extends Controller
{
    public function index()
    {
        return view('keuangan.akuntansi.laporan-jurnal-umum');
    }
    
    public function cetakPDF(Request $request)
    {
        $selectedJenjang = $request->jenjang;
        $startDate       = $request->start_date;
        $endDate         = $request->end_date;
        $search          = trim($request->search ?? '');

        if (!$selectedJenjang) {
            return response()->json([
                'error' => 'Jenjang wajib dipilih'
            ], 400);
        }

        $jenjang = Jenjang::find($selectedJenjang);

        if (!$jenjang) {
            return response()->json([
                'error' => 'Jenjang tidak ditemukan'
            ], 404);
        }

        $data = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
            'ms_pengguna',
        ])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')

            // FILTER PERIODE
            ->when(
                $startDate && $endDate,
                function ($query) use ($startDate, $endDate) {

                    $start = Carbon::createFromFormat(
                        'Y-m-d',
                        $startDate
                    )->startOfDay();

                    $end = Carbon::createFromFormat(
                        'Y-m-d',
                        $endDate
                    )->endOfDay();

                    $query->whereBetween(
                        'tanggal_transaksi',
                        [$start, $end]
                    );
                }
            )

            // SEARCH
            // Sama dengan halaman index
            // ======================================
            ->when(
                $search,
                function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        // Deskripsi jurnal
                        $query->where(
                            'deskripsi',
                            'like',
                            "%{$search}%"
                        )
                        // Nomor jurnal
                        ->orWhere(
                            'nomor_jurnal',
                            'like',
                            "%{$search}%"
                        )

                        // Kode rekening
                        ->orWhereHas(
                            'akuntansi_jurnal_detail',
                            function ($query) use ($search) {
                                $query->where(
                                    'kode_rekening',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        );
                    });
                }
            )

            // URUTAN JURNAL
            ->orderBy('tanggal_transaksi', 'asc')
            ->orderBy('akuntansi_jurnal_id', 'asc')

            ->get();

        // ==========================================
        // JUDUL
        // ==========================================
        $judul = 'Laporan Jurnal Keuangan';

        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        // PERIODE
        // ==========================================
        if ($startDate && $endDate) {
            $periode =
                'Periode ' .
                \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                    $startDate,
                    'd F Y'
                ) .
                ' sampai ' .
                \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                    $endDate,
                    'd F Y'
                );

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

        $html = '<table border="0.5" cellspacing="0" style="width:100%;">
            <thead>
                <tr style="background-color: #f5f5f5;">
                    <th width="3%">No</th>
                    <th width="10%">Tanggal</th>
                    <th width="37%">Deskripsi Transaksi</th>
                    <th width="10%">Petugas</th>
                    <th width="15%">Akun Debit</th>
                    <th width="15%">Akun Kredit</th>
                    <th width="10%" align="left">Nominal</th>
                </tr>
            </thead>
            <tbody>';
            
        $no = 1;
        foreach ($data as $jurnal) {
            $debit = $jurnal->akuntansi_jurnal_detail->where('posisi', 'debit');
            $kredit = $jurnal->akuntansi_jurnal_detail->where('posisi', 'kredit');

            $akunDebit = $debit->map(
                fn($detail) => $detail->kode_rekening . ' - ' .
                    ($detail->akuntansi_rekening->nama_rekening ?? '-')
            )->implode('<br>') ?: '-';

            $akunKredit = $kredit->map(
                fn($detail) => $detail->kode_rekening . ' - ' .
                    ($detail->akuntansi_rekening->nama_rekening ?? '-')
            )->implode('<br>') ?: '-';

            $tanggal = $jurnal->tanggal_transaksi ? HelperController::formatTanggalIndonesia($jurnal->tanggal_transaksi, 'd F Y') : '-';

            $deskripsi = htmlspecialchars(
                $jurnal->deskripsi ?? '-',
                ENT_QUOTES,
                'UTF-8'
            );

            $petugas = htmlspecialchars(
                $jurnal->ms_pengguna->nama ?? '-',
                ENT_QUOTES,
                'UTF-8'
            );

            $nominal = number_format($debit->sum('nominal'), 0, ',', '.');

           $html .= '
            <tr>
                <td width="3%" align="center">' . $no++ . '.</td>
                <td width="10%">' . $tanggal . '</td>
                <td width="37%">' . $deskripsi . '</td>
                <td width="10%">' . $petugas . '</td>
                <td width="15%">' . $akunDebit . '</td>
                <td width="15%">' . $akunKredit . '</td>
                <td width="10%" align="right">Rp' . $nominal . '</td>
            </tr>';
        }

        $html .= '
            </tbody>
        </table>';
        

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan_jurnal_keuangan.pdf', 'I');
    }
}
