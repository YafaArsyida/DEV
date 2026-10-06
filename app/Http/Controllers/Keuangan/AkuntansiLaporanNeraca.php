<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use App\Services\Reports\AkuntansiLaporanNeracaReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AkuntansiLaporanNeraca extends Controller
{
    protected AkuntansiLaporanNeracaReportService $reportService;

    public function __construct(AkuntansiLaporanNeracaReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.akuntansi.laporan-neraca');
    }

    public function cetakPDF(Request $request)
    {
        $validated = $request->validate([
            'jenjang' => ['required', 'integer'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        if (!empty($validated['start_date']) && empty($validated['end_date'])) {
            return response()->json([
                'error' => 'Tanggal akhir wajib diisi jika tanggal mulai dipilih.',
            ], 400);
        }

        if (!empty($validated['start_date']) && $validated['start_date'] > $validated['end_date']) {
            return response()->json([
                'error' => 'Tanggal mulai tidak boleh setelah tanggal akhir.',
            ], 400);
        }

        if (!Jenjang::find($validated['jenjang'])) {
            return response()->json(['error' => 'Jenjang tidak ditemukan'], 404);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView('reports.keuangan.akuntansi-laporan-neraca', $data)
            ->setPaper('a4', 'portrait')
            ->stream('laporan_neraca.pdf');
    }
}
