<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBBerkas;
use App\Models\PPDBPendaftaran;
use App\Services\PPDB\PPDBFormulirPendaftaranService;
use App\Services\PPDB\PPDBKartuAljabarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PPDBBerkasController extends Controller
{
    public function preview(Request $request, $berkasId)
    {
        $berkas = PPDBBerkas::with('ppdb_pendaftaran')
            ->findOrFail($berkasId);

        $pendaftaran = $berkas->ppdb_pendaftaran;

        abort_unless($pendaftaran, 404);

        $user = $request->user();

        $peran = strtoupper(trim($user->peran ?? ''));

        $isAdmin = in_array($peran, [
            'SUPERADMIN',
            'ADMIN',
            'KEUANGAN',
            'PANITIA_PPDB',
        ], true);

        $isPemilik = (string) $pendaftaran->ms_pengguna_id
            === (string) $user->getAuthIdentifier();

        // Hanya pemilik pendaftaran atau petugas PPDB.
        abort_unless($isPemilik || $isAdmin, 403);

        abort_unless(in_array($berkas->mime_type, [
            'image/webp',
            'image/jpeg',
            'image/png',
            'application/pdf',
        ], true), 415);

        $disk = Storage::disk('local');
        $path = $berkas->path_file;

        abort_unless($path && $disk->exists($path), 404);

        return response()->file($disk->path($path), [
            'Content-Type' => $berkas->mime_type,
            'Content-Disposition' =>
                'inline; filename="' .
                basename($berkas->nama_file) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    
    
    public function cetak(
        $pendaftaranId,
        PPDBKartuAljabarService $kartuService
    ) {
        $pendaftaran = PPDBPendaftaran::query()
            ->with([
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_jalur',
                'ppdb_periode',
            ])
            ->findOrFail($pendaftaranId);

        $user = Auth::user();

        $peran = strtoupper($user->peran ?? '');

        $peranAdmin = [
            'SUPERADMIN',
            'ADMIN',
            'PANITIA_PPDB',
        ];

        $isAdmin = in_array($peran, $peranAdmin, true);

        $isPemilik = (string) $pendaftaran->ms_pengguna_id
            === (string) $user->getAuthIdentifier();

        abort_unless($isPemilik || $isAdmin, 403);

        // Kartu hanya tersedia bagi calon siswa yang diterima.
        abort_unless($pendaftaran->status !== 'draft', 403);

        // Ambil data berdasarkan pendaftaran yang dipilih.
        $data = $kartuService->getData($pendaftaran);

        $pdf = Pdf::loadView(
            'reports.ppdb.kartu-aljabar',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->stream(
            'kartu-aljabar-' . $pendaftaran->getKey() . '.pdf'
        );
    }

    public function cetakFormulir(
        $pendaftaranId,
        PPDBFormulirPendaftaranService $formulirService
    ) {
        $pendaftaran = PPDBPendaftaran::query()
            ->with([
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_jalur',
                'ppdb_periode',
            ])
            ->findOrFail($pendaftaranId);

        $user = Auth::user();

        $peran = strtoupper($user->peran ?? '');

        $peranAdmin = [
            'SUPERADMIN',
            'ADMIN',
            'PANITIA_PPDB',
        ];

        $isAdmin = in_array($peran, $peranAdmin, true);

        $isPemilik = (string) $pendaftaran->ms_pengguna_id
            === (string) $user->getAuthIdentifier();

        abort_unless($isPemilik || $isAdmin, 403);

        // Formulir laporan ini hanya tersedia untuk siswa diterima.
        // abort_unless($pendaftaran->status === 'diterima', 403);

        $data = $formulirService->getData($pendaftaran);

        $pdf = Pdf::loadView('reports.ppdb.formulir-pendaftaran',
            $data
        )->setPaper('a4', 'portrait');

        return $pdf->stream(
            'formulir-pendaftaran-' . $pendaftaran->getKey() . '.pdf'
        );
    }

}
