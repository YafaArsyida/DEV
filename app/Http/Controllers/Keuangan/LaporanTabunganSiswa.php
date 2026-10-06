<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\LaporanTabunganSiswaReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanTabunganSiswa extends Controller
{
    protected LaporanTabunganSiswaReportService $reportService;

    public function __construct(LaporanTabunganSiswaReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.laporan.tabungan-siswa');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $data = $this->reportService->getTransactionData($request);

        return Pdf::loadView('reports.keuangan.laporan-tabungan-siswa', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_tabungan_siswa.pdf');
    }

    public function cetakSaldoPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $data = $this->reportService->getSaldoData($request);

        return Pdf::loadView('reports.keuangan.laporan-saldo-tabungan-siswa', $data)
            ->setPaper('a4', 'portrait')
            ->stream('laporan_saldo_tabungan_siswa.pdf');
    }
}
