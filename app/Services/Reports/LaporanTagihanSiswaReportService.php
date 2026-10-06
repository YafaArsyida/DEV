<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\PenempatanSiswa;
use App\Models\SuratTagihanSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanTagihanSiswaReportService
{
    public function getData(Request $request, $msPenempatanSiswaId): array
    {
        $selectedJenjang = $request->query('selectedJenjang');
        $selectedJenisTagihan = $this->parseArrayParam($request, 'selectedJenisTagihan');
        $selectedKategoriTagihan = $this->parseArrayParam($request, 'selectedKategoriTagihan');
        $endDate = $this->parseEndDate($request);

        $penempatanSiswa = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa' => function ($query) use ($selectedJenisTagihan, $selectedKategoriTagihan, $endDate) {
                $query->with(['ms_jenis_tagihan_siswa'])
                    ->withSum([
                        'dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar' => function ($query) {
                            $query->where(
                                'dt_transaksi_tagihan_siswa.status_transaksi',
                                '!=',
                                'dibatalkan'
                            );
                        }
                    ], 'jumlah_bayar')
                    ->where('status', '!=', 'Lunas')
                    ->whereHas('ms_jenis_tagihan_siswa', function ($query) use ($selectedKategoriTagihan, $endDate) {
                        $query->where('tanggal_jatuh_tempo', '<=', $endDate);

                        if (!empty($selectedKategoriTagihan)) {
                            $query->whereIn('ms_kategori_tagihan_siswa_id', $selectedKategoriTagihan);
                        }
                    });

                if (!empty($selectedJenisTagihan)) {
                    $query->whereIn('ms_jenis_tagihan_siswa_id', $selectedJenisTagihan);
                }
            },
        ])->find($msPenempatanSiswaId);

        if (!$penempatanSiswa) {
            return ['error' => 'Penempatan Siswa tidak ditemukan'];
        }

        return $this->buildStudentReportData($penempatanSiswa, $selectedJenjang, $selectedJenisTagihan, $selectedKategoriTagihan, $endDate);
    }

    public function getDataByClass(Request $request, $msKelasId): array
    {
        $selectedJenjang = $request->query('selectedJenjang');
        $selectedJenisTagihan = $this->parseArrayParam($request, 'selectedJenisTagihan');
        $selectedKategoriTagihan = $this->parseArrayParam($request, 'selectedKategoriTagihan');
        $endDate = $this->parseEndDate($request);
        $selectedTahunAjar = $request->query('selectedTahunAjar');

        $penempatanSiswaList = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa' => function ($query) use ($selectedJenisTagihan, $selectedKategoriTagihan, $endDate) {
                $query->with(['ms_jenis_tagihan_siswa'])
                    ->withSum([
                        'dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar' => function ($query) {
                            $query->where(
                                'dt_transaksi_tagihan_siswa.status_transaksi',
                                '!=',
                                'dibatalkan'
                            );
                        }
                    ], 'jumlah_bayar')
                    ->where('status', '!=', 'Lunas')
                    ->whereHas('ms_jenis_tagihan_siswa', function ($query) use ($selectedKategoriTagihan, $endDate) {
                        $query->where('tanggal_jatuh_tempo', '<=', $endDate);

                        if (!empty($selectedKategoriTagihan)) {
                            $query->whereIn('ms_kategori_tagihan_siswa_id', $selectedKategoriTagihan);
                        }
                    });

                if (!empty($selectedJenisTagihan)) {
                    $query->whereIn('ms_jenis_tagihan_siswa_id', $selectedJenisTagihan);
                }
            },
        ])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_kelas_id', $msKelasId)
            ->when($selectedTahunAjar, function ($query, $value) {
                $query->where('ms_tahun_ajar_id', $value);
            })
            ->orderBy('ms_siswa_id')
            ->get();

        if ($penempatanSiswaList->isEmpty()) {
            return ['error' => 'Tidak ada siswa pada kelas yang dipilih.'];
        }

        $reports = [];

        foreach ($penempatanSiswaList as $penempatanSiswa) {
            $report = $this->buildStudentReportData($penempatanSiswa, $selectedJenjang, $selectedJenisTagihan, $selectedKategoriTagihan, $endDate);

            if (isset($report['error'])) {
                continue;
            }

            if ($report['totalTagihan'] <= 0) {
                continue;
            }

            $reports[] = $report;
        }

        if (empty($reports)) {
            return ['error' => 'Tidak ada tagihan yang perlu dicetak untuk siswa pada kelas ini.'];
        }

        return [
            'reports' => $reports,
            'kelasNama' => $penempatanSiswaList->first()->ms_kelas->nama_kelas ?? 'Kelas',
            'totalSiswa' => count($reports),
        ];
    }

    protected function buildStudentReportData(
        PenempatanSiswa $penempatanSiswa,
        $selectedJenjang,
        array $selectedJenisTagihan,
        array $selectedKategoriTagihan,
        Carbon $endDate
    ): array {
        $surat = SuratTagihanSiswa::where('ms_jenjang_id', $selectedJenjang)->first();

        if (!$surat) {
            return ['error' => 'Template surat tidak ditemukan'];
        }

        $tagihanRincian = $penempatanSiswa->ms_tagihan_siswa
            ->filter(function ($tagihan) {
                return (float) ($tagihan->jumlah_tagihan_siswa ?? 0) > 0;
            })
            ->map(function ($tagihan) {
                $dibayar = (float) ($tagihan->jumlah_sudah_dibayar ?? 0);
                $estimasi = (float) ($tagihan->jumlah_tagihan_siswa ?? 0);
                $kekurangan = max(0, $estimasi - $dibayar);

                if ($kekurangan <= 0) {
                    return null;
                }

                return [
                    'nama' => strtoupper($tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tidak Ditemukan'),
                    'estimasi' => $estimasi,
                    'dibayarkan' => $dibayar,
                    'kekurangan' => $kekurangan,
                    'jatuh_tempo' => $tagihan->tanggal_jatuh_tempo
                        ? HelperController::formatTanggalIndonesia($tagihan->tanggal_jatuh_tempo, 'd F Y')
                        : 'Tidak Ditentukan',
                ];
            })
            ->filter()
            ->values();

        $totalTagihan = $tagihanRincian->sum('kekurangan');

        $namaSiswa = $penempatanSiswa->ms_siswa->nama_siswa ?? 'N/A';
        $namaKelas = $penempatanSiswa->ms_kelas->nama_kelas ?? 'N/A';

        $kopPath = storage_path('app/public/' . $surat->foto_kop);
        $tandaTanganPath = storage_path('app/public/' . $surat->tanda_tangan);

        if (!file_exists($kopPath)) {
            return ['error' => 'Kop surat tidak ditemukan di path ' . $kopPath];
        }

        if (!file_exists($tandaTanganPath)) {
            return ['error' => 'Kop surat tidak ditemukan di path ' . $tandaTanganPath];
        }

        return [
            'surat' => $surat,
            'penempatanSiswa' => $penempatanSiswa,
            'tagihanRincian' => $tagihanRincian,
            'totalTagihan' => $totalTagihan,
            'namaSiswa' => $namaSiswa,
            'namaKelas' => $namaKelas,
            'kopBase64' => 'data:image/' . pathinfo($kopPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($kopPath)),
            'tandaTanganBase64' => 'data:image/' . pathinfo($tandaTanganPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($tandaTanganPath)),
            'endDate' => $endDate,
        ];
    }

    protected function parseArrayParam(Request $request, string $key): array
    {
        $value = $request->query($key);

        if (empty($value)) {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [$decoded];
    }

    protected function parseEndDate(Request $request): Carbon
    {
        $endDate = $request->query('endDate');

        if (!empty($endDate) && Carbon::hasFormat($endDate, 'Y-m-d')) {
            return Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        }

        return Carbon::now()->endOfMonth();
    }
}
