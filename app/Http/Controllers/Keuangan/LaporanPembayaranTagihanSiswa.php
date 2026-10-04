<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\LaporanPembayaranTagihanSiswaReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanPembayaranTagihanSiswa extends Controller
{
    protected LaporanPembayaranTagihanSiswaReportService $reportService;

    public function __construct(LaporanPembayaranTagihanSiswaReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.laporan.pembayaran-tagihan-siswa');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json([
                'error' => 'Jenjang dan Tahun Ajar wajib dipilih',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Tanggal awal dan akhir wajib diisi dengan format yang valid',
            ], 400);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView(
            'reports.keuangan.laporan-pembayaran-tagihan-siswa',
            $data
        )
            ->setPaper('a4', 'landscape')
            ->stream('laporan_pembayaran_siswa.pdf');
    }
}
