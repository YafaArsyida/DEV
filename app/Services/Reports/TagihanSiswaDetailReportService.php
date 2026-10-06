<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\TagihanSiswa as ModelsTagihanSiswa;
use Illuminate\Http\Request;

class TagihanSiswaDetailReportService
{
    public function getData(Request $request): array
    {
        $selectedSiswa = $request->input('selectedSiswa');
        $selectedKategori = $request->input('selectedKategori');

        if (!$selectedSiswa) {
            return ['error' => 'Data siswa wajib dipilih'];
        }

        $query = ModelsTagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas',
            ])
            ->where('ms_penempatan_siswa_id', $selectedSiswa)
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where(
                        'dt_transaksi_tagihan_siswa.status_transaksi',
                        '!=',
                        'dibatalkan'
                    )->whereHas('ms_transaksi_tagihan_siswa', function ($transactionQuery) {
                        $transactionQuery->where(
                            'ms_transaksi_tagihan_siswa.status_transaksi',
                            '!=',
                            'dibatalkan'
                        );
                    });
                }
            ], 'jumlah_bayar');

        if ($selectedKategori) {
            $query->whereHas('ms_jenis_tagihan_siswa', function ($query) use ($selectedKategori) {
                $query->where('ms_kategori_tagihan_siswa_id', $selectedKategori);
            });
        }

        $tagihans = $query->get();

        if ($tagihans->isEmpty()) {
            return ['error' => 'Data tagihan siswa tidak ditemukan'];
        }

        $penempatanSiswa = $tagihans->first()->ms_penempatan_siswa;
        $siswa = $penempatanSiswa->ms_siswa ?? null;
        $kelas = $penempatanSiswa->ms_kelas ?? null;

        $jenjang = $penempatanSiswa->ms_jenjang;
        $tahunAjar = $penempatanSiswa->ms_tahun_ajar;

        $kategori = null;
        if ($selectedKategori) {
            $kategori = $tagihans->first()->ms_jenis_tagihan_siswa?->ms_kategori_tagihan_siswa ?? null;
        }

        $totalEstimasi = $tagihans->sum(fn ($item) => $item->jumlah_tagihan_siswa ?? 0);
        $totalDibayarkan = $tagihans->sum(fn ($item) => $item->total_bayar ?? 0);
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

        $data = $tagihans->map(function ($item, $index) {
            $jenisTagihan = $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? '-';
            $namaKategori = $item->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-';
            $cicilan = $item->ms_jenis_tagihan_siswa->cicilan_status ?? '-';
            $estimasi = (float) ($item->jumlah_tagihan_siswa ?? 0);
            $dibayarkan = (float) ($item->total_bayar ?? 0);
            $kekurangan = $estimasi - $dibayarkan;
            $tanggalJatuhTempo = $item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo ?? null;
            $jatuhTempo = $tanggalJatuhTempo
                ? HelperController::formatTanggalIndonesia($tanggalJatuhTempo, 'd F Y')
                : '-';
            $status = $item->status ?? '-';

            return [
                'nomor' => $index + 1,
                'jenis_tagihan' => $jenisTagihan,
                'kategori' => $namaKategori,
                'cicilan' => $cicilan,
                'estimasi' => $estimasi,
                'dibayarkan' => $dibayarkan,
                'kekurangan' => $kekurangan,
                'jatuh_tempo' => $jatuhTempo,
                'status' => $status,
            ];
        })->values();

        $judul = 'ADMINISTRASI TAGIHAN SISWA';
        $subjudul = ($jenjang->nama_jenjang ?? '-') .
            ' Tahun Ajaran ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        return [
            'judul' => $judul,
            'subjudul' => $subjudul,
            'nama_siswa' => $siswa->nama_siswa ?? '-',
            'kelas' => $kelas->nama_kelas ?? '-',
            'kategori' => $kategori ? $kategori->nama_kategori_tagihan_siswa : 'Semua Kategori',
            'jumlah_tagihan' => $tagihans->count(),
            'data' => $data,
            'total_estimasi' => $totalEstimasi,
            'total_dibayarkan' => $totalDibayarkan,
            'total_kekurangan' => $totalKekurangan,
        ];
    }
}
