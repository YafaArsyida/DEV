<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\Pegawai;
use App\Models\SaldoTabungan;
use App\Models\TahunAjar;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanTabunganPegawaiReportService
{
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

        $query = TransaksiTabungan::with(['ms_pengguna', 'ms_pegawai.ms_jabatan'])
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
        $totalKredit = (clone $query)
            ->where('jenis_transaksi', 'setoran')
            ->sum('nominal');
        $totalDebit = (clone $query)
            ->where('jenis_transaksi', 'penarikan')
            ->sum('nominal');

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'tanggal' => HelperController::formatTanggalIndonesia($item->tanggal, 'd F Y'),
                'pegawai' => $item->ms_pegawai->nama_pegawai ?? '-',
                'jabatan' => $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '-',
                'jenisTransaksi' => ucfirst($item->jenis_transaksi),
                'deskripsi' => $item->deskripsi ?? '-',
                'petugas' => $item->ms_pengguna->nama ?? '-',
                'kredit' => $item->jenis_transaksi === 'setoran' ? $this->formatRupiah($item->nominal) : '-',
                'debit' => $item->jenis_transaksi === 'penarikan' ? $this->formatRupiah($item->nominal) : '-',
            ];
        });

        $periode = $startDate && $endDate
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Transaksi Tabungan Pegawai',
            'yayasan' => 'Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'jabatan' => $selectedJabatan
                ? (Jabatan::find($selectedJabatan)->nama_jabatan ?? '-')
                : 'Semua Jabatan',
            'periode' => $periode,
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahTransaksi' => $laporan->count(),
            'totalKredit' => $this->formatRupiah($totalKredit),
            'totalDebit' => $this->formatRupiah($totalDebit),
            'totalSaldo' => $this->formatRupiah($totalKredit - $totalDebit),
            'data' => $data,
        ];
    }

    public function getSaldoData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $selectedJabatan = $request->input('jabatan');

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = TahunAjar::find($selectedTahunAjar);

        $query = Pegawai::with(['ms_jabatan', 'ms_saldo_tabungan'])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->whereHas('ms_saldo_tabungan', function ($query) {
                $query->where('saldo_tabungan', '!=', 0);
            });

        if ($selectedJabatan) {
            $query->where('ms_jabatan_id', $selectedJabatan);
        }

        $query->orderByDesc(
            SaldoTabungan::select('saldo_tabungan')
                ->whereColumn('user_id', 'ms_pegawai.ms_pegawai_id')
                ->where('user_type', 'pegawai')
                ->limit(1)
        );

        $laporan = $query->get();
        $totalSaldo = $laporan->sum(function ($item) {
            return $item->ms_saldo_tabungan->saldo_tabungan ?? 0;
        });

        $data = $laporan->map(function ($item, $index) {
            return [
                'nomor' => $index + 1,
                'pegawai' => $item->nama_pegawai,
                'jabatan' => $item->ms_jabatan->nama_jabatan ?? '-',
                'saldo' => number_format($item->ms_saldo_tabungan->saldo_tabungan ?? 0, 0, ',', '.'),
            ];
        });

        return [
            'judul' => 'Laporan Saldo Tabungan Pegawai',
            'yayasan' => 'Unit ' . ($jenjang->nama_jenjang ?? '-'),
            'tahunAjar' => $tahunAjar->nama_tahun_ajar ?? '-',
            'jabatan' => $selectedJabatan
                ? (Jabatan::find($selectedJabatan)->nama_jabatan ?? '-')
                : 'Semua Jabatan',
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'jumlahPegawai' => $laporan->count(),
            'totalSaldo' => number_format($totalSaldo, 0, ',', '.'),
            'data' => $data,
        ];
    }

    private function formatRupiah($nominal): string
    {
        return number_format($nominal, 0, ',', '.');
    }
}
