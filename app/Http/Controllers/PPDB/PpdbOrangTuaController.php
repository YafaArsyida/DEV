<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBAlamat;
use App\Models\PPDBBerkas;
use App\Models\PPDBCalonSiswa;
use App\Models\PPDBJawaban;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPendidikan;
use App\Models\PPDBPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PpdbOrangTuaController extends Controller
{
    public function dashboard()
    {
        // Periode PPDB aktif
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        // Pendaftaran milik orang tua yang sedang login
        $pendaftaran = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->with([
                    'ppdb_periode',
                    'ppdb_gelombang',
                    'ppdb_jalur',
                    'ppdb_calon_siswa',
                ])
                ->where('ms_penguna_id', Auth::id())
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->first();
        }

        // Progres pendaftaran
        $totalStep = 8;

        $currentStep = $pendaftaran
            ? min(max((int) $pendaftaran->current_step, 1), $totalStep)
            : 0;

        $progressPersen = $currentStep > 0
            ? round(($currentStep / $totalStep) * 100)
            : 0;

        // Status pendaftaran
        $status = $pendaftaran?->status;

        // Apakah orang tua perlu melakukan tindakan?
        $perluPerbaikan = $status === 'perbaikan';

        return view('ppdb.orang-tua.dashboard',[
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'currentStep' => $currentStep,
            'totalStep' => $totalStep,
            'progressPersen' => $progressPersen,
            'status' => $status,
            'perluPerbaikan' => $perluPerbaikan,
        ]);
    }

    public function show(Request $request, $berkasId)
    {
        $berkas = PPDBBerkas::with('ppdb_pendaftaran')
            ->findOrFail($berkasId);

        $pendaftaran = $berkas->ppdb_pendaftaran;

        abort_unless($pendaftaran, 404);

        // Pastikan berkas milik orang tua yang sedang login.
        abort_unless(
            (string) $pendaftaran->ms_penguna_id ===
            (string) $request->user()->getAuthIdentifier(),
            403
        );

        // Batasi format yang boleh ditampilkan.
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

        // Pastikan file tersedia.
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

    public function jalur()
    {
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_penguna_id', Auth::id())
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->first();
        }
            
        return view('ppdb.orang-tua.jalur',[
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'stepAktif' => 1,
        ]);
    }

    public function dataSiswa()
    {
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $calonSiswa = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_penguna_id', Auth::id())
                ->where(
                    'ppdb_periode_id',
                    $periode->ppdb_periode_id
                )
                ->first();

            if ($pendaftaran) {
                $calonSiswa = PPDBCalonSiswa::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id)
                    ->first();
            }
        }

        return view('ppdb.orang-tua.data-siswa', [
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'calonSiswa' => $calonSiswa,
            'stepAktif' => 2,
        ]);
    }

    public function orangTua()
    {
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $calonSiswa = null;
        $orangTua = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_penguna_id', Auth::id())
                ->where(
                    'ppdb_periode_id',
                    $periode->ppdb_periode_id
                )
                ->first();

            if ($pendaftaran) {
                // Ambil data calon siswa
                $calonSiswa = PPDBCalonSiswa::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->first();

                // Ambil data orang tua
                $orangTua = PPDBOrangtua::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->first();
            }
        }

        return view('ppdb.orang-tua.orang-tua',[
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'calonSiswa' => $calonSiswa,
            'orangTua' => $orangTua,
            'stepAktif' => 3,
        ]);
    }

    public function alamat()
    {
        // Periode PPDB aktif
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $orangTua = null;
        $alamat = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_penguna_id', Auth::id())
                ->where(
                    'ppdb_periode_id',
                    $periode->ppdb_periode_id
                )
                ->first();

            if ($pendaftaran) {
                // Ambil data orang tua
                $orangTua = PPDBOrangtua::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->first();

                //Ambil data alamat
                $alamat = PPDBAlamat::query()
                ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->first();
            }
        }

        return view('ppdb.orang-tua.alamat', [
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'orangTua' => $orangTua,
            'alamat' => $alamat,
            'stepAktif' => 4,
        ]);
    }

    public function pendidikan()
    {
        // Periode PPDB aktif
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $alamat = null;
        $pendidikan = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_penguna_id', Auth::id())
                ->where(
                    'ppdb_periode_id',
                    $periode->ppdb_periode_id
                )
                ->first();

            if ($pendaftaran) {
                //Ambil data alamat
                $alamat = PPDBAlamat::query()
                ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->first();

                // Ambil pendidikan
                $pendidikan = PPDBPendidikan::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->first();
            }
        }

        return view('ppdb.orang-tua.pendidikan',[
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'alamat' => $alamat,
            'pendidikan' => $pendidikan,
            'stepAktif' => 5,
        ]);
    }

    public function berkas()
    {
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $pendidikan = null;
        $berkas = collect();

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_penguna_id', Auth::id())
                ->where(
                    'ppdb_periode_id', $periode->ppdb_periode_id
                )
                ->first();

            if ($pendaftaran) {
                $pendidikan = PPDBPendidikan::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->first();

                $berkas = PPDBBerkas::query()
                    ->where(
                        'ppdb_pendaftaran_id',
                        $pendaftaran->ppdb_pendaftaran_id
                    )
                    ->latest('ppdb_berkas_id')
                    ->get();
            }
        }

        return view('ppdb.orang-tua.berkas', [
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'pendidikan' => $pendidikan,
            'berkas' => $berkas,
            'stepAktif' => 6,
        ]);
    }

    public function review()
    {
        $pendaftaran = PPDBPendaftaran::query()
            ->with([
                'ppdb_periode',
                'ppdb_gelombang',
                'ppdb_jalur',
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_alamat',
                'ppdb_pendidikan',
                'ppdb_berkas',
            ])
            ->where('ms_penguna_id', Auth::id())
            ->latest('ppdb_pendaftaran_id')
            ->firstOrFail();

        $jawabanTambahan = PPDBJawaban::query()
            ->with('ppdb_pertanyaan')
            ->where('ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id)
            ->where('ppdb_periode_id', $pendaftaran->ppdb_periode_id)
            ->whereHas('ppdb_pertanyaan', function ($query) {
                $query->where('status', 'aktif');
            })
            ->get()
            ->sortBy(function ($item) {
                return $item->ppdb_pertanyaan->urutan ?? 0;
            })
            ->values();
        
            $stepAktif = 7;

        return view('ppdb.orang-tua.review', compact(
            'pendaftaran',
            'stepAktif',
            'jawabanTambahan'
        ));
    }

    public function status()
    {
        $pendaftaran = PPDBPendaftaran::query()
            ->with([
                'ppdb_periode',
                'ppdb_gelombang',
                'ppdb_jalur',
                'ppdb_calon_siswa',
            ])
            ->where('ms_penguna_id', auth()->id())
            ->latest('created_at')
            ->first();

        $stepAktif = 8;
        
        if (!$pendaftaran) {
            return redirect()->route('ppdb.pendaftaran.jalur')
                ->with('error', 'Anda belum memiliki data pendaftaran.');
        }
        return view('ppdb.orang-tua.status', compact(
            'pendaftaran',
            'stepAktif',
        ));
    }

    public function kirimUlang($ppdb_pendaftaran_id)
    {
        $pendaftaran = PPDBPendaftaran::query()
            ->where('ppdb_pendaftaran_id', $ppdb_pendaftaran_id)
            ->where('ms_penguna_id', Auth::id())
            ->where('status', 'perbaikan')
            ->firstOrFail();

        $pendaftaran->update([
            'status' => 'pengecekan',
            'catatan_perbaikan' => null,
        ]);

        return redirect()
            ->route('ppdb.pendaftaran.status')
            ->with('success', 'Perbaikan data berhasil dikirim. Pendaftaran Anda akan diperiksa kembali oleh panitia.');
    }

    public function buktiPendaftaran()
    {
        return view('ppdb.orang-tua.bukti-pendaftaran');
    }
}
