<?php

namespace App\Services\Reports;

use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanSiswaReportService
{
    public function getData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $selectedKelas = $request->input('kelas');
        $search = $request->input('search');

        $query = PenempatanSiswa::with(['ms_siswa', 'ms_kelas'])
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $selectedTahunAjar)
            ->withCount([
                'ms_tagihan_siswa as jumlah_item' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT ms_jenis_tagihan_siswa_id)'));
                },
            ])
            ->withSum('ms_tagihan_siswa as total_tagihan', 'jumlah_tagihan_siswa')
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where(
                        'dt_transaksi_tagihan_siswa.status_transaksi',
                        '!=',
                        'dibatalkan'
                    )->where(
                        'ms_transaksi_tagihan_siswa.status_transaksi',
                        '!=',
                        'dibatalkan'
                    );
                },
            ], 'jumlah_bayar');

        if ($selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $selectedKelas);
        }

        if ($search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $search . '%');
        }

        $tagihans = $query
            ->orderBy('ms_penempatan_siswa.ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')
            ->get();

        $totalTagihan = 0;
        $totalDibayarkan = 0;
        $totalKekurangan = 0;
        $jumlahItem = 0;

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);
        $kelas = $selectedKelas ? Kelas::find($selectedKelas) : null;

        $data = $tagihans->map(function ($item, $index) use (&$totalTagihan, &$totalDibayarkan, &$totalKekurangan, &$jumlahItem) {
            $nama = $item->ms_siswa->nama_siswa ?? '-';
            $namaKelas = $item->ms_kelas->nama_kelas ?? '-';
            $jumlah = $item->jumlah_item ?? 0;
            $tagihan = (float) ($item->total_tagihan ?? 0);
            $dibayar = (float) ($item->total_bayar ?? 0);
            $kekurangan = $tagihan - $dibayar;
            $persen = $tagihan > 0 ? round(($dibayar / $tagihan) * 100, 2) : 0;

            $totalTagihan += $tagihan;
            $totalDibayarkan += $dibayar;
            $totalKekurangan += $kekurangan;
            $jumlahItem += $jumlah;

            return [
                'nomor' => $index + 1,
                'nama_siswa' => $nama,
                'kelas' => $namaKelas,
                'jumlah_tagihan' => $jumlah,
                'estimasi' => $tagihan,
                'dibayarkan' => $dibayar,
                'kekurangan' => $kekurangan,
                'persen' => $persen,
            ];
        })->values();

        $totalPersen = $totalTagihan > 0 ? round(($totalDibayarkan / $totalTagihan) * 100, 2) : 0;

        $judul = 'ADMINISTRASI TAGIHAN SISWA';
        $subjudul = ($jenjang->nama_jenjang ?? '-') .
            ' Tahun Ajaran ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        if ($kelas) {
            $subjudul .= ' | Kelas: ' . $kelas->nama_kelas;
        }

        return [
            'judul' => $judul,
            'subjudul' => $subjudul,
            'data' => $data,
            'totalTagihan' => $totalTagihan,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
            'jumlahItem' => $jumlahItem,
            'totalPersen' => $totalPersen,
        ];
    }
}
