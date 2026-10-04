<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Services\Reports\TransaksiPengeluaranReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransaksiPengeluaran extends Controller
{
    protected TransaksiPengeluaranReportService $reportService;

    public function __construct(TransaksiPengeluaranReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.transaksi.pengeluaran');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang')) {
            return response()->json(['error' => 'Jenjang wajib dipilih'], 400);
        }

        $data = $this->reportService->getData($request);

        return Pdf::loadView(
            'reports.keuangan.transaksi-pengeluaran',
            $data
        )
            ->setPaper('a4', 'landscape')
            ->stream('laporan_pengeluaran.pdf');
    }
}
