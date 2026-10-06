<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiRekening;
use App\Models\Jenjang;
use App\Services\Reports\AkuntansiLaporanBukuBesarReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AkuntansiLaporanBukuBesar extends Controller
{
    protected AkuntansiLaporanBukuBesarReportService $reportService;

    public function __construct(AkuntansiLaporanBukuBesarReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('keuangan.akuntansi.laporan-buku-besar');
    }

    public function cetakPDF(Request $request)
    {
        if (!$request->input('jenjang') || !$request->input('rekening')
            || !$request->input('start_date') || !$request->input('end_date')) {
            return response()->json([
                'error' => 'Rekening, jenjang, dan periode wajib dipilih'
            ], 400);
        }

        $request->validate([
            'jenjang' => ['required', 'integer'],
            'rekening' => ['required', 'string'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d'],
            'search' => ['nullable', 'string'],
        ]);

        if ($request->input('start_date') > $request->input('end_date')) {
            return response()->json([
                'error' => 'Tanggal mulai harus lebih awal atau sama dengan tanggal selesai.'
            ], 400);
        }

        if (!Jenjang::find($request->input('jenjang'))) {
            return response()->json(['error' => 'Jenjang tidak ditemukan'], 404);
        }

        if (!AkuntansiRekening::where('kode_rekening', $request->input('rekening'))->exists()) {
            return response()->json(['error' => 'Rekening tidak ditemukan'], 404);
        }

        $data = $this->reportService->getData($request);
        $filenameRekening = preg_replace(
            '/[^A-Za-z0-9_-]+/',
            '_',
            trim($data['rekening']->nama_rekening)
        );

        return Pdf::loadView('reports.keuangan.akuntansi-laporan-buku-besar', $data)
            ->setPaper('a4', 'landscape')
            ->stream('Laporan_Buku_Besar_' . $data['rekening']->kode_rekening . '_' . $filenameRekening . '.pdf');
    }
}
