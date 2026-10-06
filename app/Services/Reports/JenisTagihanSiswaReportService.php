<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\JenisTagihanSiswa as ModelsJenisTagihanSiswa;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\TagihanSiswa;
use App\Models\TahunAjar;
use Illuminate\Http\Request;

class JenisTagihanSiswaReportService
{
    public function getListData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $selectedKategoriTagihan = $request->input('kategori');
        $search = $request->input('search');

        if (!$selectedJenjang || !$selectedTahunAjar) {
            return ['error' => 'Filter Jenjang dan Tahun Ajar wajib diisi'];
        }

        $query = ModelsJenisTagihanSiswa::with('ms_kategori_tagihan_siswa')
            ->withCount(['ms_tagihan_siswa as jumlah_item'])
            ->withSum(['ms_tagihan_siswa as total_tagihan'], 'jumlah_tagihan_siswa')
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where(
                        'dt_transaksi_tagihan_siswa.status_transaksi',
                        '!=',
                        'dibatalkan'
                    );
                }
            ], 'jumlah_bayar')
            ->where('ms_jenjang_id', $selectedJenjang)
            ->where('ms_tahun_ajar_id', $selectedTahunAjar);

        if ($selectedKategoriTagihan) {
            $query->where('ms_kategori_tagihan_siswa_id', $selectedKategoriTagihan);
        }

        if ($search) {
            $query->where('nama_jenis_tagihan_siswa', 'like', '%' . $search . '%');
        }

        $tagihans = $query->orderBy('ms_kategori_tagihan_siswa_id')->get();

        $totalSiswa = 0;
        $totalEstimasi = 0;
        $totalDibayarkan = 0;
        $totalKekurangan = 0;

        $data = $tagihans->map(function ($item, $index) use (&$totalSiswa, &$totalEstimasi, &$totalDibayarkan, &$totalKekurangan) {
            $jumlah = $item->jumlah_item ?? 0;
            $estimasi = (float) ($item->total_tagihan ?? 0);
            $dibayar = (float) ($item->total_bayar ?? 0);
            $kekurangan = $estimasi - $dibayar;
            $persen = $estimasi > 0 ? round(($dibayar / $estimasi) * 100, 2) : 0;

            $totalSiswa += $jumlah;
            $totalEstimasi += $estimasi;
            $totalDibayarkan += $dibayar;
            $totalKekurangan += $kekurangan;

            return [
                'nomor' => $index + 1,
                'jenis_tagihan' => $item->nama_jenis_tagihan_siswa,
                'kategori' => $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-',
                'jumlah_tagihan' => $jumlah,
                'estimasi' => $estimasi,
                'dibayarkan' => $dibayar,
                'kekurangan' => $kekurangan,
                'persen' => $persen,
            ];
        })->values();

        $totalPersen = $totalEstimasi > 0 ? round(($totalDibayarkan / $totalEstimasi) * 100, 2) : 0;

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        return [
            'judul' => 'Administrasi Jenis Tagihan Siswa',
            'subjudul' => 'Jenjang: ' . ($jenjang->nama_jenjang ?? '-') .
                ' | Tahun Ajar: ' . ($tahunAjar->nama_tahun_ajar ?? '-'),
            'data' => $data,
            'totalSiswa' => $totalSiswa,
            'totalEstimasi' => $totalEstimasi,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
            'totalPersen' => $totalPersen,
        ];
    }

    public function getDetailData(Request $request): array
    {
        $selectedJenisTagihan = $request->input('selectedJenisTagihan');
        $selectedKelas = $request->input('selectedKelas');
        $search = $request->input('search');

        if (!$selectedJenisTagihan) {
            return ['error' => 'Data jenis tagihan wajib dipilih'];
        }

        $query = TagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas',
            ])
            ->select('ms_tagihan_siswa.*')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tagihan_siswa.ms_penempatan_siswa_id')
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_penempatan_siswa.ms_siswa_id')
            ->join('ms_kelas', 'ms_kelas.ms_kelas_id', '=', 'ms_penempatan_siswa.ms_kelas_id')
            ->where('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $selectedJenisTagihan)
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where(
                        'dt_transaksi_tagihan_siswa.status_transaksi',
                        '!=',
                        'dibatalkan'
                    );
                }
            ], 'jumlah_bayar');

        if ($selectedKelas) {
            $query->where('ms_kelas.ms_kelas_id', $selectedKelas);
        }

        if ($search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $search . '%');
        }

        $tagihans = $query
            ->orderBy('ms_kelas.nama_kelas')
            ->orderBy('ms_siswa.nama_siswa')
            ->get();

        if ($tagihans->isEmpty()) {
            return ['error' => 'Data tagihan jenis tidak ditemukan'];
        }

        $jenisTagihan = ModelsJenisTagihanSiswa::find($selectedJenisTagihan);
        $jenjang = Jenjang::find($jenisTagihan->ms_jenjang_id ?? null);
        $tahunAjar = TahunAjar::find($jenisTagihan->ms_tahun_ajar_id ?? null);
        $kelasFilter = $selectedKelas ? Kelas::find($selectedKelas) : null;

        $totalEstimasi = 0;
        $totalDibayarkan = 0;
        $totalKekurangan = 0;

        $data = $tagihans->map(function ($item, $index) use (&$totalEstimasi, &$totalDibayarkan, &$totalKekurangan) {
            $estimasi = (float) ($item->jumlah_tagihan_siswa ?? 0);
            $dibayarkan = (float) ($item->total_bayar ?? 0);
            $kekurangan = $estimasi - $dibayarkan;

            $totalEstimasi += $estimasi;
            $totalDibayarkan += $dibayarkan;
            $totalKekurangan += $kekurangan;

            $tanggalJatuhTempo = $item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo ?? null;
            $jatuhTempo = $tanggalJatuhTempo
                ? HelperController::formatTanggalIndonesia($tanggalJatuhTempo, 'd F Y')
                : '-';

            return [
                'nomor' => $index + 1,
                'siswa' => $item->ms_penempatan_siswa->ms_siswa->nama_siswa ?? '-',
                'kelas' => $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '-',
                'cicilan' => $item->ms_jenis_tagihan_siswa->cicilan_status ?? '-',
                'estimasi' => $estimasi,
                'dibayarkan' => $dibayarkan,
                'kekurangan' => $kekurangan,
                'jatuh_tempo' => $jatuhTempo,
                'status' => $item->status ?? '-',
            ];
        })->values();

        return [
            'judul' => 'DETAIL TAGIHAN JENIS',
            'subjudul' => 'Jenis Tagihan: ' . ($jenisTagihan->nama_jenis_tagihan_siswa ?? '-') .
                ' | Jenjang: ' . ($jenjang->nama_jenjang ?? '-') .
                ' | Tahun Ajar: ' . ($tahunAjar->nama_tahun_ajar ?? '-') .
                ($kelasFilter ? ' | Kelas: ' . $kelasFilter->nama_kelas : ''),
            'jenis_tagihan' => $jenisTagihan->nama_jenis_tagihan_siswa ?? '-',
            'kategori' => $jenisTagihan->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-',
            'jenjang' => $jenjang->nama_jenjang ?? '-',
            'tahun_ajar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'filter_kelas' => $kelasFilter->nama_kelas ?? 'Semua Kelas',
            'jumlah_tagihan' => $tagihans->count(),
            'data' => $data,
            'total_estimasi' => $totalEstimasi,
            'total_dibayarkan' => $totalDibayarkan,
            'total_kekurangan' => $totalKekurangan,
        ];
    }
}
