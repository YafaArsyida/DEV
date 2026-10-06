<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\AkuntansiLaporanPengeluaranReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AkuntansiLaporanPengeluaran extends Controller
{
    protected AkuntansiLaporanPengeluaranReportService $reportService;

    public function __construct(AkuntansiLaporanPengeluaranReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.akuntansi.laporan-pengeluaran');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang')) {
            return response()->json(['error' => 'Jenjang wajib dipilih'], 400);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView('reports.keuangan.akuntansi-laporan-pengeluaran', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_pengeluaran.pdf');
    }
}
