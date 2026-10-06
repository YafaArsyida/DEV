<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\JenisTagihanSiswaReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JenisTagihanSiswa extends Controller
{
    protected JenisTagihanSiswaReportService $reportService;

    public function __construct(JenisTagihanSiswaReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.tagihan.jenis-tagihan-siswa');
    }

    public function cetakPDF(Request $request)
    {
        $data = $this->reportService->getListData($request);

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 400);
        }

        return Pdf::loadView('reports.keuangan.jenis-tagihan-siswa', $data)
            ->setPaper('a4', 'landscape')
            ->stream('administrasi_jenis_tagihan_siswa.pdf');
    }

    public function detailPDF(Request $request)
    {
        $data = $this->reportService->getDetailData($request);

        if (isset($data['error'])) {
            $statusCode = str_contains($data['error'], 'Data jenis tagihan wajib dipilih') ? 400 : 404;
            return response()->json(['error' => $data['error']], $statusCode);
        }

        return Pdf::loadView('reports.keuangan.jenis-tagihan-siswa-detail', $data)
            ->setPaper('a4', 'landscape')
            ->stream('detail_tagihan_jenis.pdf');
    }
}
