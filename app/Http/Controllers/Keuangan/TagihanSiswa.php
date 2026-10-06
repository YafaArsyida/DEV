<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\TagihanSiswaDetailReportService;
use App\Services\Reports\TagihanSiswaReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TagihanSiswa extends Controller
{
    protected TagihanSiswaReportService $reportService;
    protected TagihanSiswaDetailReportService $detailReportService;

    public function __construct(
        TagihanSiswaReportService $reportService,
        TagihanSiswaDetailReportService $detailReportService
    ) {
        $this->reportService = $reportService;
        $this->detailReportService = $detailReportService;
    }

    public function index()
    {
        return view('keuangan.tagihan.tagihan-siswa');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json(['error' => 'Filter jenjang dan tahun ajar wajib diisi'], 400);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView('reports.keuangan.tagihan-siswa', $data)
            ->setPaper('a4', 'landscape')
            ->stream('administrasi_tagihan_siswa.pdf');
    }

    public function detailPDF(Request $request)
    {
        if (!$request->input('selectedSiswa')) {
            return response()->json([
                'error' => 'Data siswa wajib dipilih'
            ], 400);
        }

        $data = $this->detailReportService->getData($request);

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 404);
        }

        return Pdf::loadView('reports.keuangan.tagihan-siswa-detail', $data)
            ->setPaper('a4', 'landscape')
            ->stream('detail_tagihan_siswa.pdf');
    }
}
