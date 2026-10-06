<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\LaporanEduPayPegawaiReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanEduPayPegawai extends Controller
{
    protected LaporanEduPayPegawaiReportService $reportService;

    public function __construct(LaporanEduPayPegawaiReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.laporan.edupay-pegawai');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $data = $this->reportService->getTransactionData($request);

        return Pdf::loadView('reports.keuangan.laporan-edupay-pegawai', $data)
            ->setPaper('a4', 'landscape')
            ->stream('laporan_edupay_pegawai.pdf');
    }

    public function cetakSaldoPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('tahun')) {
            return response()->json(['error' => 'Jenjang dan Tahun Ajar wajib dipilih'], 400);
        }

        $data = $this->reportService->getSaldoData($request);

        return Pdf::loadView('reports.keuangan.laporan-saldo-edupay-pegawai', $data)
            ->setPaper('a4', 'portrait')
            ->stream('laporan_saldo_edupay_pegawai.pdf');
    }
}
