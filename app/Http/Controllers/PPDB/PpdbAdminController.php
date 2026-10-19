<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBBerkas;
use App\Models\PPDBGelombang;
use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PpdbAdminController extends Controller
{
    public function index()
    {
        return view('ppdb.admin.index');
    }

    public function showBerkas(Request $request, $berkasId)
    {
        $berkas = PPDBBerkas::with('ppdb_pendaftaran')
            ->findOrFail($berkasId);

        abort_unless($berkas->ppdb_pendaftaran, 404);

        // Otorisasi petugas PPDB.
        $peran = strtoupper($request->user()->peran ?? '');

        abort_unless(
            in_array(
                $peran,
                ['SUPERADMIN', 'ADMIN', 'KEUANGAN', 'PANITIA_PPDB'],
                true
            ),
            403
        );

        // Batasi format yang dapat ditampilkan.
        abort_unless(
            in_array($berkas->mime_type, [
                'image/webp',
                'image/jpeg',
                'image/png',
                'application/pdf',
            ], true),
            415
        );

        $disk = Storage::disk('local');
        $path = $berkas->path_file;

        abort_unless(
            $path && $disk->exists($path),
            404
        );

        return response()->file(
            $disk->path($path),
            [
                'Content-Type' => $berkas->mime_type,
                'Content-Disposition' =>
                    'inline; filename="' .
                    basename($berkas->nama_file) . '"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    public function pendaftar()
    {
        return view('ppdb.admin.pendaftar');
    }
    public function bantuPendaftaran()
    {
        return view('ppdb.admin.bantu-pendaftaran');
    }

    public function verifikasi()
    {
        return view('ppdb.admin.verifikasi');
    }

    public function seleksi()
    {
        return view('ppdb.admin.seleksi');
    }

    public function pengumuman()
    {
        return view('ppdb.admin.pengumuman');
    }

    public function daftarUlang()
    {
        return view('ppdb.admin.daftar-ulang');
    }

    public function laporan()
    {
        return view('ppdb.admin.laporan');
    }

    public function pengaturan()
    {
        return view('ppdb.admin.pengaturan');
    }
}
