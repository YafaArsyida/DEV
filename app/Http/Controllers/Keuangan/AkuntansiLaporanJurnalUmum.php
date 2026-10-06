<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\AkuntansiLaporanJurnalUmumReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AkuntansiLaporanJurnalUmum extends Controller
{
    protected AkuntansiLaporanJurnalUmumReportService $reportService;

    public function __construct(AkuntansiLaporanJurnalUmumReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.akuntansi.laporan-jurnal-umum');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang')) {
            return response()->json(['error' => 'Jenjang wajib dipilih'], 400);
        }

        if (!\App\Models\Jenjang::find($request->input('jenjang'))) {
            return response()->json(['error' => 'Jenjang tidak ditemukan'], 404);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView('reports.keuangan.akuntansi-laporan-jurnal-umum', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_jurnal_keuangan.pdf');
    }
}
