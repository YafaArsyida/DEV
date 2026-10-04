<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanPembayaranTagihanSiswaReportService
{
    public function getData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $selectedKelas = $request->input('kelas', []);
        $selectedKategori = $request->input('kategori', []);
        $selectedJenis = $request->input('jenis', []);
        $selectedMetode = $request->input('metode', []);
        $selectedPetugas = $request->input('petugas', []);
        $search = $request->input('search');
        $start = $request->input('start');
        $end = $request->input('end');

        $startDate = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $query = DetailTransaksiTagihanSiswa::with([
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa',
            'ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas',
            'ms_transaksi_tagihan_siswa.ms_pengguna',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
        ])
            ->join(
                'ms_transaksi_tagihan_siswa',
                'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id',
                '=',
                'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id'
            )
            ->where('dt_transaksi_tagihan_siswa.status_transaksi', '!=', 'dibatalkan')
            ->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa', function ($query) use ($selectedTahunAjar, $selectedJenjang) {
                $query->where('ms_tahun_ajar_id', $selectedTahunAjar)
                    ->where('ms_jenjang_id', $selectedJenjang);
            });

        if ($search) {
            $query->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_siswa', function ($query) use ($search) {
                $query->where('nama_siswa', 'like', '%' . $search . '%');
            });
        }

        if ($selectedKelas) {
            $query->whereHas('ms_transaksi_tagihan_siswa.ms_penempatan_siswa.ms_kelas', function ($query) use ($selectedKelas) {
                $query->whereIn('ms_kelas_id', $selectedKelas);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            });
        }

        if ($selectedPetugas) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($query) use ($selectedPetugas) {
                $query->whereIn('ms_pengguna_id', $selectedPetugas);
            });
        }

        if ($selectedKategori) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($query) use ($selectedKategori) {
                $query->whereIn('ms_kategori_tagihan_siswa_id', $selectedKategori);
            });
        }

        if ($selectedJenis) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($query) use ($selectedJenis) {
                $query->whereIn('ms_jenis_tagihan_siswa_id', $selectedJenis);
            });
        }

        if ($selectedMetode) {
            $query->whereHas('ms_transaksi_tagihan_siswa', function ($query) use ($selectedMetode) {
                $query->whereIn('metode_pembayaran', $selectedMetode);
            });
        }

        $laporans = $query
            ->orderBy('ms_transaksi_tagihan_siswa.tanggal_transaksi', 'ASC')
            ->get();

        $total = $laporans->sum('jumlah_bayar');
        $data = $laporans->map(function ($item, $index) {
            $transaksi = $item->ms_transaksi_tagihan_siswa;
            $penempatan = $transaksi->ms_penempatan_siswa;
            $jenisTagihan = $item->ms_tagihan_siswa?->ms_jenis_tagihan_siswa;

            return [
                'nomor' => $index + 1,
                'tanggal' => HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi, 'd F Y'),
                'siswa' => $penempatan->ms_siswa->nama_siswa ?? '-',
                'kelas' => $penempatan->ms_kelas->nama_kelas ?? '-',
                'tagihan' => $jenisTagihan->nama_jenis_tagihan_siswa ?? '-',
                'petugas' => $transaksi->ms_pengguna->nama ?? '-',
                'metode' => $transaksi->metode_pembayaran ?? '-',
                'jumlahBayar' => number_format($item->jumlah_bayar, 0, ',', '.'),
            ];
        });

        $periode = $start && $end
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($start, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($end, 'd F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Pembayaran Siswa',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'deskripsiJenjang' => $jenjang->deskripsi ?? '-',
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'periode' => $periode,
            'pencarian' => $search ?: 'Semua siswa',
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahTransaksi' => $laporans->count(),
            'total' => number_format($total, 0, ',', '.'),
            'data' => $data,
        ];
    }
}
