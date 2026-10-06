<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use App\Services\Reports\AkuntansiLaporanArusKasReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AkuntansiLaporanArusKas extends Controller
{
    protected AkuntansiLaporanArusKasReportService $reportService;

    public function __construct(AkuntansiLaporanArusKasReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.akuntansi.laporan-arus-kas');
    }

    public function cetakPDF(Request $request)
    {
        $validated = $request->validate([
            'jenjang' => ['required', 'integer'],
            'rekening' => ['nullable', 'in:11001,11002'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);

        if (!Jenjang::find($validated['jenjang'])) {
            return response()->json(['error' => 'Data jenjang tidak ditemukan.'], 404);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView('reports.keuangan.akuntansi-laporan-arus-kas', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_arus_kas.pdf');
    }
}
