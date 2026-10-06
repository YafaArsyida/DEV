<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use App\Services\Reports\AkuntansiLaporanLabaRugiReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AkuntansiLaporanLabaRugi extends Controller
{
    protected AkuntansiLaporanLabaRugiReportService $reportService;

    public function __construct(AkuntansiLaporanLabaRugiReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.akuntansi.laporan-laba-rugi');
    }

    public function cetakPDF(Request $request)
    {
        $validated = $request->validate([
            'jenjang' => ['required', 'integer'],
            'start_date' => ['nullable', 'required_with:end_date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'required_with:start_date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);

        if (!Jenjang::find($validated['jenjang'])) {
            return response()->json(['error' => 'Jenjang tidak ditemukan'], 404);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView('reports.keuangan.akuntansi-laporan-laba-rugi', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_laba_rugi.pdf');
    }
}
