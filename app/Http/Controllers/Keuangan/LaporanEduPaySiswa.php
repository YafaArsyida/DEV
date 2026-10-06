<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\LaporanEduPaySiswaReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanEduPaySiswa extends Controller
{
    protected LaporanEduPaySiswaReportService $reportService;

    public function __construct(LaporanEduPaySiswaReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.laporan.edupay-siswa');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $data = $this->reportService->getTransactionData($request);

        return Pdf::loadView('reports.keuangan.laporan-edupay-siswa', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_edupay_siswa.pdf');
    }

    public function cetakSaldoPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $data = $this->reportService->getSaldoData($request);

        return Pdf::loadView('reports.keuangan.laporan-saldo-edupay-siswa', $data)
            ->setPaper('a4', 'portrait')
            ->stream('laporan_saldo_edupay_siswa.pdf');
    }
}
