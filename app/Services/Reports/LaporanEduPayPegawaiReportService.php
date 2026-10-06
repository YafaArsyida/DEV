<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\Pegawai;
use App\Models\SaldoEduPay;
use App\Models\TahunAjar;
use App\Models\TransaksiEduPay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanEduPayPegawaiReportService
{
    private const PEMASUKAN_JENIS = ['topup tunai', 'topup online', 'pengembalian dana'];

    private const PENGELUARAN_JENIS = ['penarikan', 'pembayaran', 'kantin'];

    public function getTransactionData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $selectedJabatan = $request->input('jabatan');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenisTransaksi = (array) $request->input('jenis_transaksi', []);
        $petugas = (array) $request->input('petugas', []);
        $search = trim((string) $request->input('search', ''));

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $query = TransaksiEduPay::with(['ms_pegawai.ms_jabatan', 'ms_pengguna'])
            ->where('status_transaksi', '!=', 'dibatalkan')
            ->where('user_type', 'pegawai')
            ->whereHas('ms_pegawai', function ($query) use ($selectedJenjang, $selectedJabatan) {
                $query->where('ms_jenjang_id', $selectedJenjang);

                if ($selectedJabatan) {
                    $query->where('ms_jabatan_id', $selectedJabatan);
                }
            })
            ->orderBy('tanggal', 'ASC');

        if ($petugas) {
            $query->whereIn('ms_pengguna_id', $petugas);
        }

        if ($search !== '') {
            $query->whereHas('ms_pegawai', function ($query) use ($search) {
                $query->where('nama_pegawai', 'like', '%' . $search . '%');
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [
                Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay(),
            ]);
        }

        if ($jenisTransaksi) {
            $query->whereIn('jenis_transaksi', $jenisTransaksi);
        }

        $laporan = $query->get();
        $totalPemasukan = $laporan
            ->whereIn('jenis_transaksi', self::PEMASUKAN_JENIS)
            ->sum('nominal');
        $totalPengeluaran = $laporan
            ->whereIn('jenis_transaksi', self::PENGELUARAN_JENIS)
            ->sum('nominal');

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'tanggal' => HelperController::formatTanggalIndonesia($item->tanggal),
                'pegawai' => ucfirst($item->ms_pegawai->nama_pegawai ?? '-'),
                'jabatan' => $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '-',
                'jenisTransaksi' => ucfirst($item->jenis_transaksi),
                'petugas' => $item->ms_pengguna->nama ?? '-',
                'pemasukan' => in_array($item->jenis_transaksi, self::PEMASUKAN_JENIS, true)
                    ? $this->formatRupiah($item->nominal)
                    : '-',
                'pengeluaran' => in_array($item->jenis_transaksi, self::PENGELUARAN_JENIS, true)
                    ? $this->formatRupiah($item->nominal)
                    : '-',
            ];
        });

        $periode = $startDate && $endDate
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Transaksi EduPay Pegawai',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'jabatan' => $selectedJabatan
                ? (Jabatan::find($selectedJabatan)->nama_jabatan ?? '-')
                : 'Semua Jabatan',
            'periode' => $periode,
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahTransaksi' => $laporan->count(),
            'totalPemasukan' => $this->formatRupiah($totalPemasukan),
            'totalPengeluaran' => $this->formatRupiah($totalPengeluaran),
            'totalSaldo' => $this->formatRupiah($totalPemasukan - $totalPengeluaran),
            'data' => $data,
        ];
    }

    public function getSaldoData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $selectedJabatan = $request->input('jabatan');
        $search = trim((string) $request->input('search', ''));

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $query = Pegawai::with(['ms_jabatan', 'ms_saldo_edupay', 'ms_educard'])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->whereHas('ms_saldo_edupay', function ($query) {
                $query->where('saldo_edupay', '!=', 0);
            });

        if ($selectedJabatan) {
            $query->where('ms_jabatan_id', $selectedJabatan);
        }

        if ($search !== '') {
            $query->where('nama_pegawai', 'like', '%' . $search . '%');
        }

        $query->orderByDesc(
            SaldoEduPay::select('saldo_edupay')
                ->whereColumn('user_id', 'ms_pegawai.ms_pegawai_id')
                ->where('user_type', 'pegawai')
                ->limit(1)
        );

        $laporan = $query->get();
        $totalSaldo = $laporan->sum(function ($item) {
            return $item->ms_saldo_edupay->saldo_edupay ?? 0;
        });

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'pegawai' => $item->nama_pegawai,
                'jabatan' => $item->ms_jabatan->nama_jabatan ?? '-',
                'kartu' => $item->ms_educard->kode_kartu ?? 'Belum memiliki kartu',
                'saldo' => $this->formatRupiah($item->ms_saldo_edupay->saldo_edupay ?? 0),
            ];
        });

        return [
            'judul' => 'Laporan Saldo EduPay Pegawai',
            'yayasan' => 'Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'jabatan' => $selectedJabatan
                ? (Jabatan::find($selectedJabatan)->nama_jabatan ?? '-')
                : 'Semua Jabatan',
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahPegawai' => $laporan->count(),
            'totalSaldo' => $this->formatRupiah($totalSaldo),
            'data' => $data,
        ];
    }

    private function formatRupiah($nominal): string
    {
        return number_format($nominal, 0, ',', '.');
    }
}
