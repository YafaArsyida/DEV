<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBBerkas;
use Illuminate\Http\Request;
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
}
