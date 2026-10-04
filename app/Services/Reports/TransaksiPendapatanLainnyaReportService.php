<?php

namespace App\Services\Reports;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiRekening;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use App\Models\TransaksiPendapatanLainnya;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiPendapatanLainnyaReportService
{
    public function getData(Request $request): array
    {
        $selectedJenjang = $request->input('jenjang');
        $selectedTahunAjar = $request->input('tahun');
        $selectedRekening = $request->input('rekening');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $jenjang = Jenjang::find($selectedJenjang);
        $tahunAjar = $selectedTahunAjar ? TahunAjar::find($selectedTahunAjar) : null;

        $query = TransaksiPendapatanLainnya::with(['akuntansi_rekening', 'ms_pengguna'])
            ->where('ms_jenjang_id', $selectedJenjang)
            ->when($selectedTahunAjar, function ($query) use ($selectedTahunAjar) {
                $query->where('ms_tahun_ajar_id', $selectedTahunAjar);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
                $query->whereBetween('tanggal', [$start, $end]);
            })
            ->orderBy('tanggal', 'ASC');

        $saldoAwal = 0;
        if ($startDate) {
            $saldoAwalQuery = TransaksiPendapatanLainnya::query()
                ->where('ms_jenjang_id', $selectedJenjang)
                ->when($selectedTahunAjar, function ($query) use ($selectedTahunAjar) {
                    $query->where('ms_tahun_ajar_id', $selectedTahunAjar);
                })
                ->where('status_transaksi', '!=', 'dibatalkan')
                ->whereDate('tanggal', '<', $startDate);

            if (!empty($selectedRekening)) {
                $saldoAwalQuery->where('kode_rekening', $selectedRekening);
            }

            $saldoAwal = $saldoAwalQuery->sum('nominal');
        }

        if (!empty($selectedRekening)) {
            $query->where('kode_rekening', $selectedRekening);
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('deskripsi', 'like', '%' . $search . '%')
                    ->orWhereHas('akuntansi_rekening', function ($rekeningQuery) use ($search) {
                        $rekeningQuery->where('nama_rekening', 'like', '%' . $search . '%');
                    });
            });
        }

        $transaksi = $query->get();
        $total = $transaksi
            ->filter(fn ($item) => $item->status_transaksi !== 'dibatalkan')
            ->sum('nominal');
        $jumlahDibatalkan = $transaksi
            ->where('status_transaksi', 'dibatalkan')
            ->count();
        $saldoAkhir = $saldoAwal + $total;

        $saldoBerjalan = $saldoAwal;
        $data = $transaksi->map(function ($item, $index) use (&$saldoBerjalan) {
            $dibatalkan = $item->status_transaksi === 'dibatalkan';

            if (!$dibatalkan) {
                $saldoBerjalan += $item->nominal;
            }

            return [
                'nomor' => $index + 1,
                'tanggal' => HelperController::formatTanggalIndonesia($item->tanggal, 'd F Y'),
                'rekening' => $item->akuntansi_rekening->nama_rekening ?? '-',
                'deskripsi' => $item->deskripsi ?? '-',
                'petugas' => $item->ms_pengguna->nama ?? '-',
                'metode' => $item->metode_pembayaran ?? '-',
                'nominal' => number_format($item->nominal, 0, ',', '.'),
                'saldo' => number_format($saldoBerjalan, 0, ',', '.'),
                'dibatalkan' => $dibatalkan,
            ];
        });

        $rekening = $selectedRekening
            ? AkuntansiRekening::where('kode_rekening', $selectedRekening)->first()
            : null;

        $periode = $startDate && $endDate
            ? 'Periode '
                . HelperController::formatTanggalIndonesia($startDate, 'd F Y')
                . ' sampai '
                . HelperController::formatTanggalIndonesia($endDate, 'd F Y')
            : 'Semua Periode';

        return [
            'judul' => 'Laporan Transaksi Pendapatan Lainnya',
            'yayasan' => 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-')
                . ($tahunAjar ? ' Tahun Ajaran ' . $tahunAjar->nama_tahun_ajar : ''),
            'deskripsiJenjang' => $jenjang->deskripsi ?? '-',
            'periode' => $periode,
            'rekening' => $rekening->nama_rekening ?? 'Semua rekening',
            'pencarian' => $search ?: 'Semua transaksi',
            'dicetakPada' => now()->format('d/m/Y H:i'),
            'saldoAwal' => number_format($saldoAwal, 0, ',', '.'),
            'total' => number_format($total, 0, ',', '.'),
            'saldoAkhir' => number_format($saldoAkhir, 0, ',', '.'),
            'jumlahTransaksi' => $transaksi->count(),
            'jumlahDibatalkan' => $jumlahDibatalkan,
            'data' => $data,
        ];
    }
}
