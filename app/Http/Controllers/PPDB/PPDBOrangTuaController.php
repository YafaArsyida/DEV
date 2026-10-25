<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
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

class PPDBOrangTuaController extends Controller
{
    public function dashboard($jenjang)
    {
        // Resolusi slug menjadi data master jenjang
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        // Periode PPDB aktif untuk jenjang yang dipilih
        $periode = PPDBPeriode::query()
            ->where('ms_jenjang_id', $jenjang->ms_jenjang_id)
            ->where('status', 'aktif')
            ->first();

        // Pendaftaran milik orang tua pada periode aktif jenjang ini
        $pendaftaran = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->with([
                    'ppdb_periode',
                    'ppdb_gelombang',
                    'ppdb_jalur',
                    'ppdb_calon_siswa',
                ])
                ->where('ms_pengguna_id', Auth::id())
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

        return view('ppdb.orang-tua.dashboard', [
            'jenjang' => $jenjang,
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'currentStep' => $currentStep,
            'totalStep' => $totalStep,
            'progressPersen' => $progressPersen,
            'status' => $status,
            'perluPerbaikan' => $perluPerbaikan,
        ]);
    }

    public function profil($jenjang)
    {
        $jenjang = Jenjang::where('slug', $jenjang)
        ->firstOrFail();

        return view('ppdb.orang-tua.profil', compact('jenjang'));
    }

    
    public function show(Request $request, $jenjang, $berkasId)
    {
        $berkas = PPDBBerkas::with([
            'ppdb_pendaftaran.ppdb_periode.ms_jenjang',
        ])->findOrFail($berkasId);

        $pendaftaran = $berkas->ppdb_pendaftaran;

        abort_unless($pendaftaran, 404);

        // Pastikan berkas milik orang tua yang sedang login.
        abort_unless(
            (string) $pendaftaran->ms_pengguna_id ===
            (string) $request->user()->getAuthIdentifier(),
            403
        );

        // Pastikan jenjang pada URL sesuai dengan pendaftaran.
        abort_unless(
            $pendaftaran->ppdb_periode
                && $pendaftaran->ppdb_periode->ms_jenjang
                && $pendaftaran->ppdb_periode->ms_jenjang->slug === $jenjang,
            404
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


    public function jalur($jenjang)
    {
        // Resolusi slug jenjang
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        // Periode PPDB aktif untuk jenjang ini
        $periode = PPDBPeriode::query()
            ->where('ms_jenjang_id', $jenjang->ms_jenjang_id)
            ->where('status', 'aktif')
            ->first();

        // Pendaftaran milik orang tua pada periode aktif
        $pendaftaran = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->first();
        }

        return view('ppdb.orang-tua.jalur', [
            'jenjang' => $jenjang,
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'stepAktif' => 1,
        ]);
    }

    public function dataSiswa($jenjang)
    {
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        // Periode PPDB aktif untuk jenjang ini
        $periode = PPDBPeriode::query()
            ->where('ms_jenjang_id', $jenjang->ms_jenjang_id)
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $calonSiswa = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
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
            'jenjang' => $jenjang,
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'calonSiswa' => $calonSiswa,
            'stepAktif' => 2,
        ]);
    }

    public function orangTua($jenjang)
    {
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        $periode = PPDBPeriode::query()
            ->where('ms_jenjang_id', $jenjang->ms_jenjang_id)
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $calonSiswa = null;
        $orangTua = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
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
            'jenjang' => $jenjang,
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'calonSiswa' => $calonSiswa,
            'orangTua' => $orangTua,
            'stepAktif' => 3,
        ]);
    }

    public function alamat($jenjang)
    {
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();
        
        $periode = PPDBPeriode::query()
            ->where('ms_jenjang_id', $jenjang->ms_jenjang_id)
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $orangTua = null;
        $alamat = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
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
            'jenjang' => $jenjang,
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'orangTua' => $orangTua,
            'alamat' => $alamat,
            'stepAktif' => 4,
        ]);
    }

    public function pendidikan($jenjang)
    {
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        // Periode PPDB aktif
        $periode = PPDBPeriode::query()
            ->where('ms_jenjang_id', $jenjang->ms_jenjang_id)
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $alamat = null;
        $pendidikan = null;

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
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
            'jenjang' => $jenjang,
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'alamat' => $alamat,
            'pendidikan' => $pendidikan,
            'stepAktif' => 5,
        ]);
    }

    public function berkas($jenjang)
    {
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        $periode = PPDBPeriode::query()
            ->where('ms_jenjang_id', $jenjang->ms_jenjang_id)
            ->where('status', 'aktif')
            ->first();

        $pendaftaran = null;
        $pendidikan = null;
        $berkas = collect();

        if ($periode) {
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
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
            'jenjang' => $jenjang,
            'periode' => $periode,
            'pendaftaran' => $pendaftaran,
            'pendidikan' => $pendidikan,
            'berkas' => $berkas,
            'stepAktif' => 6,
        ]);
    }

    
    public function review($jenjang)
    {
         $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        $pendaftaran = PPDBPendaftaran::query()
            ->with([
                'ppdb_periode.ms_jenjang',
                'ppdb_gelombang',
                'ppdb_jalur',
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_alamat',
                'ppdb_pendidikan',
                'ppdb_berkas',
            ])
            ->where('ms_pengguna_id', Auth::id())
            ->whereHas('ppdb_periode.ms_jenjang', function ($query) use ($jenjang) {
                $query->where('slug', $jenjang->slug);
            })
            ->latest('ppdb_pendaftaran_id')
            ->firstOrFail();

        $jawabanTambahan = PPDBJawaban::query()
            ->with('ppdb_pertanyaan')
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->where(
                'ppdb_periode_id',
                $pendaftaran->ppdb_periode_id
            )
            ->whereHas('ppdb_pertanyaan', function ($query) {
                $query->where('status', 'aktif');
            })
            ->get()
            ->sortBy(fn ($item) => $item->ppdb_pertanyaan->urutan ?? 0)
            ->values();

        $stepAktif = 7;

        return view('ppdb.orang-tua.review', compact(
            'jenjang',
            'pendaftaran',
            'stepAktif',
            'jawabanTambahan'
        ));
    }

    public function status($jenjang)
    {
        $jenjang = Jenjang::query()
            ->where('slug', $jenjang)
            ->firstOrFail();

        $pendaftaran = PPDBPendaftaran::query()
            ->with([
                'ppdb_periode.ms_jenjang',
                'ppdb_gelombang',
                'ppdb_jalur',
                'ppdb_calon_siswa',
            ])
            ->where('ms_pengguna_id', auth()->id())
            ->whereHas('ppdb_periode.ms_jenjang', function ($query) use ($jenjang) {
                $query->where('slug', $jenjang->slug);
            })
            ->latest('created_at')
            ->first();

        if (! $pendaftaran) {
            return redirect()
                ->route('ppdb.pendaftaran.jalur', [
                    'jenjang' => $jenjang,
                ])
                ->with('error', 'Anda belum memiliki data pendaftaran.');
        }

        $stepAktif = 8;

        return view('ppdb.orang-tua.status', compact(
            'jenjang',
            'pendaftaran',
            'stepAktif',
        ));
    }

    public function kirimUlang($jenjang, $ppdb_pendaftaran_id)
    {
        $pendaftaran = PPDBPendaftaran::query()
            ->with('ppdb_periode.ms_jenjang')
            ->where('ppdb_pendaftaran_id', $ppdb_pendaftaran_id)
            ->where('ms_pengguna_id', Auth::id())
            ->whereHas('ppdb_periode.ms_jenjang', function ($query) use ($jenjang) {
                $query->where('slug', $jenjang);
            })
            ->where('status', 'perbaikan')
            ->firstOrFail();

        $pendaftaran->update([
            'status' => 'pengecekan',
            'catatan_perbaikan' => null,
        ]);

        return redirect()
            ->route('ppdb.pendaftaran.status', [
                'jenjang' => $jenjang,
            ])
            ->with(
                'success',
                'Perbaikan data berhasil dikirim. Pendaftaran Anda akan diperiksa kembali oleh panitia.'
            );
    }

    public function buktiPendaftaran()
    {
        return view('ppdb.orang-tua.bukti-pendaftaran');
    }
}
