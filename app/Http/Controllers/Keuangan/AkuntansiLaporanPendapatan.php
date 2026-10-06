<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\AkuntansiLaporanPendapatanReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AkuntansiLaporanPendapatan extends Controller
{
    protected AkuntansiLaporanPendapatanReportService $reportService;

    public function __construct(AkuntansiLaporanPendapatanReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.akuntansi.laporan-pendapatan');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang')) {
            return response()->json(['error' => 'Jenjang wajib dipilih'], 400);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView('reports.keuangan.akuntansi-laporan-pendapatan', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_pendapatan.pdf');
    }
}
