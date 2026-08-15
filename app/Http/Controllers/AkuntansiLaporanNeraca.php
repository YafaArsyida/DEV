<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkuntansiLaporanNeraca extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-neraca.v_index');
    }

    public function cetakPDF(Request $request)
    {
        $jenjangId = $request->jenjang;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $jenjang = Jenjang::find($jenjangId);

        if (!$jenjangId) {
            return response()->json(['error' => 'Jenjang wajib dipilih'], 400);
        }

        $endDateValue = $endDate ? Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay() : null;
        $startDateValue = $startDate ? Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay() : ($endDateValue ? Carbon::parse($endDateValue)->startOfYear()->startOfDay() : null);

        $akunSaldo = AkuntansiJurnalDetail::query()
            ->join('akuntansi_rekening', 'akuntansi_jurnal_detail.kode_rekening', '=', 'akuntansi_rekening.kode_rekening')
            ->join('akuntansi_jurnal', 'akuntansi_jurnal_detail.akuntansi_jurnal_id', '=', 'akuntansi_jurnal.akuntansi_jurnal_id')
            ->where('akuntansi_jurnal.ms_jenjang_id', $jenjangId)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
            ->whereRaw("LEFT(akuntansi_jurnal_detail.kode_rekening, 1) IN ('1', '2', '3')")
            ->when($startDateValue && $endDateValue, function ($query) use ($startDateValue, $endDateValue) {
                $query->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$startDateValue, $endDateValue]);
            })
            ->when($endDateValue && !$startDateValue, function ($query) use ($endDateValue) {
                $query->where('akuntansi_jurnal.tanggal_transaksi', '<=', $endDateValue);
            })
            ->select(
                'akuntansi_jurnal_detail.kode_rekening',
                'akuntansi_rekening.nama_rekening',
                'akuntansi_rekening.posisi_normal',
                DB::raw('SUM(CASE WHEN akuntansi_jurnal_detail.posisi = "debit" THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN akuntansi_jurnal_detail.posisi = "kredit" THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_kredit')
            )
            ->groupBy(
                'akuntansi_jurnal_detail.kode_rekening',
                'akuntansi_rekening.nama_rekening',
                'akuntansi_rekening.posisi_normal'
            )
            ->orderBy('akuntansi_jurnal_detail.kode_rekening')
            ->get();

        $kelompok = [
            'aset' => [],
            'kewajiban' => [],
            'ekuitas' => [],
        ];

        foreach ($akunSaldo as $item) {
            $saldo = $item->posisi_normal === 'debit'
                ? ((float) $item->total_debit - (float) $item->total_kredit)
                : ((float) $item->total_kredit - (float) $item->total_debit);

            $data = [
                'kode' => $item->kode_rekening,
                'nama' => $item->nama_rekening,
                'saldo' => $saldo,
            ];

            $kodeAwal = substr((string) $item->kode_rekening, 0, 1);

            if ($kodeAwal === '1') {
                $kelompok['aset'][] = $data;
            } elseif ($kodeAwal === '2') {
                $kelompok['kewajiban'][] = $data;
            } elseif ($kodeAwal === '3') {
                $kelompok['ekuitas'][] = $data;
            }
        }

        $pendapatan = AkuntansiJurnalDetail::query()
            ->join('akuntansi_jurnal', 'akuntansi_jurnal_detail.akuntansi_jurnal_id', '=', 'akuntansi_jurnal.akuntansi_jurnal_id')
            ->join('akuntansi_rekening', 'akuntansi_jurnal_detail.kode_rekening', '=', 'akuntansi_rekening.kode_rekening')
            ->where('akuntansi_jurnal.ms_jenjang_id', $jenjangId)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
            ->where('akuntansi_jurnal_detail.posisi', 'kredit')
            ->where('akuntansi_rekening.kode_rekening', 'like', '4%')
            ->when($startDateValue && $endDateValue, function ($query) use ($startDateValue, $endDateValue) {
                $query->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$startDateValue, $endDateValue]);
            })
            ->when($endDateValue && !$startDateValue, function ($query) use ($endDateValue) {
                $query->where('akuntansi_jurnal.tanggal_transaksi', '<=', $endDateValue);
            })
            ->sum('akuntansi_jurnal_detail.nominal');

        $beban = AkuntansiJurnalDetail::query()
            ->join('akuntansi_jurnal', 'akuntansi_jurnal_detail.akuntansi_jurnal_id', '=', 'akuntansi_jurnal.akuntansi_jurnal_id')
            ->join('akuntansi_rekening', 'akuntansi_jurnal_detail.kode_rekening', '=', 'akuntansi_rekening.kode_rekening')
            ->where('akuntansi_jurnal.ms_jenjang_id', $jenjangId)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
            ->where('akuntansi_jurnal_detail.posisi', 'debit')
            ->where('akuntansi_rekening.kode_rekening', 'like', '5%')
            ->when($startDateValue && $endDateValue, function ($query) use ($startDateValue, $endDateValue) {
                $query->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$startDateValue, $endDateValue]);
            })
            ->when($endDateValue && !$startDateValue, function ($query) use ($endDateValue) {
                $query->where('akuntansi_jurnal.tanggal_transaksi', '<=', $endDateValue);
            })
            ->sum('akuntansi_jurnal_detail.nominal');

        $labaRugi = (float) $pendapatan - (float) $beban;

        $totalAset = collect($kelompok['aset'])->sum('saldo');
        $totalKewajiban = collect($kelompok['kewajiban'])->sum('saldo');
        $totalEkuitasAkun = collect($kelompok['ekuitas'])->sum('saldo');
        $totalEkuitas = $totalEkuitasAkun + $labaRugi;
        $totalPassiva = $totalKewajiban + $totalEkuitas;
        $selisih = $totalAset - $totalPassiva;

        $judul = 'Laporan Neraca';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-');

        if ($request->start_date && $request->end_date) {
            $periode = 'Periode ' . HelperController::formatTanggalIndonesia($request->start_date, 'd F Y') . ' sampai ' . HelperController::formatTanggalIndonesia($request->end_date, 'd F Y');
        } elseif ($request->end_date) {
            $periode = 'Sampai Periode ' . HelperController::formatTanggalIndonesia($request->end_date, 'd F Y');
        } else {
            $periode = 'Semua Periode';
        }

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage();

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $yayasan, 0, 1, 'C');
        $pdf::SetFont('times', '', 10);
        $pdf::MultiCell(0, 6, ($jenjang->deskripsi ?? '-'), 0, 'C');
        $pdf::Cell(0, 5, $periode, 0, 1, 'C');
        $pdf::Ln(3);

        $html = '<table border="0.5" cellpadding="4" cellspacing="0" width="100%"><thead><tr style="background-color:#f2f2f2;"><th width="50%">Nama Akun</th><th width="50%" align="right">Saldo</th></tr></thead><tbody>';

        $html .= '<tr><td colspan="2"><b>ASET</b></td></tr>';
        foreach ($kelompok['aset'] as $akun) {
            $html .= '<tr><td>' . htmlspecialchars($akun['nama']) . '</td><td align="right">Rp' . number_format($akun['saldo'], 0, ',', '.') . '</td></tr>';
        }
        $html .= '<tr style="background-color:#d1d1d1;"><td><b>TOTAL ASET</b></td><td align="right"><b>Rp' . number_format($totalAset, 0, ',', '.') . '</b></td></tr>';

        $html .= '<tr><td colspan="2"><b>KEWAJIBAN</b></td></tr>';
        foreach ($kelompok['kewajiban'] as $akun) {
            $html .= '<tr><td>' . htmlspecialchars($akun['nama']) . '</td><td align="right">Rp' . number_format($akun['saldo'], 0, ',', '.') . '</td></tr>';
        }
        $html .= '<tr><td><b>Total Kewajiban</b></td><td align="right"><b>Rp' . number_format($totalKewajiban, 0, ',', '.') . '</b></td></tr>';

        $html .= '<tr><td colspan="2"><b>EKUITAS</b></td></tr>';
        $html .= '<tr><td>Surplus/Defisit Tahun Berjalan</td><td align="right">Rp' . number_format($labaRugi, 0, ',', '.') . '</td></tr>';
        foreach ($kelompok['ekuitas'] as $akun) {
            $html .= '<tr><td>' . htmlspecialchars($akun['nama']) . '</td><td align="right">Rp' . number_format($akun['saldo'], 0, ',', '.') . '</td></tr>';
        }
        $html .= '<tr><td><b>Total Ekuitas</b></td><td align="right"><b>Rp' . number_format($totalEkuitas, 0, ',', '.') . '</b></td></tr>';

        $html .= '<tr style="background-color:#d1d1d1;"><td><b>TOTAL KEWAJIBAN + EKUITAS</b></td><td align="right"><b>Rp' . number_format($totalPassiva, 0, ',', '.') . '</b></td></tr>';

        if ($selisih != 0) {
            $html .= '<tr><td colspan="2"><b>Validasi Neraca:</b> Selisih ' . number_format(abs($selisih), 0, ',', '.') . '</td></tr>';
        }

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan-neraca.pdf', 'I');
    }
}
