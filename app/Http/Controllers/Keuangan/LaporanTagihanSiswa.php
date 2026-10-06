<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\LaporanTagihanSiswaReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanTagihanSiswa extends Controller
{
    protected LaporanTagihanSiswaReportService $reportService;

    public function __construct(LaporanTagihanSiswaReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.laporan.tagihan-siswa');
    }

    public function generatePDF($msPenempatanSiswaId, Request $request)
    {
        $data = $this->reportService->getData($request, $msPenempatanSiswaId);

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 404);
        }

        return Pdf::loadView('reports.keuangan.laporan-tagihan-siswa', $data)
            ->setPaper('a4', 'portrait')
            ->stream('Surat_Tagihan_' . $data['namaSiswa'] . '.pdf');
    }

    public function generatePDFByClass($ms_kelas_id, Request $request)
    {
        $data = $this->reportService->getDataByClass($request, $ms_kelas_id);

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 404);
        }

        return Pdf::loadView('reports.keuangan.laporan-tagihan-siswa-kelas', $data)
            ->setPaper('a4', 'portrait')
            ->stream('Surat_Tagihan_Kelas_' . ($data['kelasNama'] ?? $ms_kelas_id) . '.pdf');
    }

    public function cetakSurat($msPenempatanSiswaId, Request $request)
    {
        return $this->generatePDF($msPenempatanSiswaId, $request);
    }
}