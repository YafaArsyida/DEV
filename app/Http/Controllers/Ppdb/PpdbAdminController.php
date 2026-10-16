<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PPDBGelombang;
use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
use Carbon\Carbon;

class PpdbAdminController extends Controller
{
    public function index()
    {
        // Periode PPDB aktif
        $periode = PPDBPeriode::query()
            ->where('status', 'aktif')
            ->first();

        // Nilai default jika belum ada periode aktif
        $kuota = 0;

        $totalPendaftar = 0;
        $menungguVerifikasi = 0;
        $perluPerbaikan = 0;
        $diterima = 0;

        $statusPeriode = 'Belum Ada Periode';

        if ($periode) {
            // Total kuota dari seluruh jalur dalam periode
            $kuota = PPDBJalur::query()
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->where('status', 'aktif')
                ->sum('kuota');

            // Query dasar pendaftaran pada periode aktif
            $query = PPDBPendaftaran::query()
                ->where(
                    'ppdb_periode_id',
                    $periode->ppdb_periode_id
                );

            // Statistik pendaftaran
            $totalPendaftar = (clone $query)->count();

            $menungguVerifikasi = (clone $query)
                ->whereIn('status', [
                    'submitted',
                    'menunggu_verifikasi',
                ])
                ->count();

            $perluPerbaikan = (clone $query)
                ->where('status', 'perbaikan')
                ->count();

            $diterima = (clone $query)
                ->where('status', 'diterima')
                ->count();

            // Status periode berdasarkan tanggal
            $hariIni = Carbon::today();

            $tanggalMulai = $periode->tanggal_mulai
                ? Carbon::parse($periode->tanggal_mulai)->startOfDay()
                : null;

            $tanggalSelesai = $periode->tanggal_selesai
                ? Carbon::parse($periode->tanggal_selesai)->endOfDay()
                : null;

            if (
                $tanggalMulai &&
                $hariIni->lt($tanggalMulai)
            ) {
                $statusPeriode = 'Akan Datang';
            } elseif (
                $tanggalSelesai &&
                $hariIni->gt($tanggalSelesai)
            ) {
                $statusPeriode = 'Pendaftaran Ditutup';
            } else {
                $statusPeriode = 'Periode Berlangsung';
            }
        }

        // Data statistik untuk kartu dashboard
        $statistik = [
            [
                'label' => 'Total Pendaftar',
                'value' => number_format($totalPendaftar, 0, ',', '.'),
                'description' => 'Seluruh pendaftar pada periode ini',
                'color' => 'primary',
                'icon' => 'mdi-account-group-outline',
            ],
            [
                'label' => 'Menunggu Verifikasi',
                'value' => number_format($menungguVerifikasi, 0, ',', '.'),
                'description' => 'Pendaftaran perlu diperiksa',
                'color' => 'info',
                'icon' => 'mdi-clipboard-text-clock-outline',
            ],
            [
                'label' => 'Perlu Perbaikan',
                'value' => number_format($perluPerbaikan, 0, ',', '.'),
                'description' => 'Menunggu perbaikan dari orang tua',
                'color' => 'warning',
                'icon' => 'mdi-file-edit-outline',
            ],
            [
                'label' => 'Diterima',
                'value' => number_format($diterima, 0, ',', '.'),
                'description' => 'Calon siswa dinyatakan diterima',
                'color' => 'success',
                'icon' => 'mdi-check-circle-outline',
            ],
        ];

        $jalurPendaftaran = collect();

        if ($periode) {
            $totalPendaftar = PPDBPendaftaran::query()
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->count();

            $jalurPendaftaran = PPDBJalur::query()
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->where('status', 'aktif')
                ->withCount([
                    'ppdb_pendaftaran as jumlah_pendaftar',
                ])
                ->get()
                ->map(function ($jalur) use ($totalPendaftar) {
                    $jumlah = (int) $jalur->jumlah_pendaftar;

                    return [
                        'nama' => $jalur->nama_jalur,
                        'jumlah' => $jumlah,
                        'persentase' => $totalPendaftar > 0
                            ? round(($jumlah / $totalPendaftar) * 100, 1)
                            : 0,
                        'color' => 'primary',
                    ];
                });
        }
        
        $pendaftarTerbaru = collect();

        if ($periode) {
            $pendaftarTerbaru = PPDBPendaftaran::query()
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->with([
                    'ppdb_jalur',
                    'ppdb_calon_siswa',
                ])
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(function ($pendaftar) {
                    $status = $pendaftar->status;

                    $statusMap = [
                        'draft' => [
                            'label' => 'Belum Dikirim',
                            'badge' => 'secondary',
                        ],
                        'submitted' => [
                            'label' => 'Menunggu Verifikasi',
                            'badge' => 'info',
                        ],
                        'menunggu_verifikasi' => [
                            'label' => 'Menunggu Verifikasi',
                            'badge' => 'info',
                        ],
                        'perbaikan' => [
                            'label' => 'Perlu Perbaikan',
                            'badge' => 'warning',
                        ],
                        'diterima' => [
                            'label' => 'Diterima',
                            'badge' => 'success',
                        ],
                        'ditolak' => [
                            'label' => 'Ditolak',
                            'badge' => 'danger',
                        ],
                    ];

                    $infoStatus = $statusMap[$status] ?? [
                        'label' => ucfirst(str_replace('_', ' ', $status ?? '')),
                        'badge' => 'secondary',
                    ];

                    return [
                        'id' => $pendaftar->ppdb_pendaftaran_id,
                        'nama' => $pendaftar->ppdb_calon_siswa->nama_siswa ?? 'Belum diisi',
                        'nomor' => $pendaftar->nomor_pendaftaran ?? '-',
                        'jalur' => $pendaftar->ppdb_jalur->nama_jalur ?? '-',
                        'sumber' =>  $pendaftar->ppdb_pendidikan->asal_sekolah ?? '-',
                        'tanggal' => $pendaftar->created_at
                            ? $pendaftar->created_at->format('d M Y')
                            : '-',
                        'status' => $infoStatus['label'],
                        'badge' => $infoStatus['badge'],
                    ];
                });
        }
        return view('ppdb.admin.index',compact(
            'periode',
            'kuota',
            'statusPeriode',
            'totalPendaftar',
            'statistik',
            'jalurPendaftaran',
            'pendaftarTerbaru'
        ));
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
        $totalPeriode = PPDBPeriode::count();
        $totalGelombang = PPDBGelombang::count();
        $totalJalur = PPDBJalur::count();

        
        return view('ppdb.admin.pengaturan', compact(
            'totalPeriode',
            'totalGelombang',
            'totalJalur'
        ));
    }
}
