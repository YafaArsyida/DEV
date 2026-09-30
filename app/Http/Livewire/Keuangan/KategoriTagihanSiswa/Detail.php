<?php

namespace App\Http\Livewire\Keuangan\KategoriTagihanSiswa;

use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $selectedKategori = null;

    protected $listeners = [
        'loadDetailKategoriTagihan' => 'loadDetailKategoriTagihan',
    ];

    public function loadDetailKategoriTagihan($kategoriId)
    {
        $this->selectedKategori = $kategoriId;
    }

    public function render()
    {
        $kategori = $this->selectedKategori
            ? KategoriTagihanSiswa::with(['ms_jenjang', 'ms_tahun_ajar'])->find($this->selectedKategori)
            : null;

        $totalTagihan = 0;
        $totalDibayarkan = 0;
        $jenisTagihan = collect();

        if ($kategori) {
            $jenisTagihan = JenisTagihanSiswa::query()
                ->where('ms_kategori_tagihan_siswa_id', $kategori->ms_kategori_tagihan_siswa_id)
                // ->orderBy('nama_jenis_tagihan_siswa')
                ->get();
            $jenisTagihanIds = $jenisTagihan->pluck('ms_jenis_tagihan_siswa_id');

            $tagihanQuery = TagihanSiswa::query()
                ->whereHas('ms_jenis_tagihan_siswa', function ($query) use ($kategori) {
                    $query->where(
                        'ms_kategori_tagihan_siswa_id',
                        $kategori->ms_kategori_tagihan_siswa_id
                    );
                });

            $totalTagihan = (clone $tagihanQuery)->sum('jumlah_tagihan_siswa');

            $ringkasanPerJenis = TagihanSiswa::query()
                ->whereIn('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $jenisTagihanIds)
                ->selectRaw('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id, SUM(ms_tagihan_siswa.jumlah_tagihan_siswa) as total_tagihan')
                ->groupBy('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id')
                ->get()
                ->keyBy('ms_jenis_tagihan_siswa_id');

            $pembayaranPerJenis = DetailTransaksiTagihanSiswa::query()
                ->join('ms_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_tagihan_siswa_id', '=', 'ms_tagihan_siswa.ms_tagihan_siswa_id')
                ->join('ms_transaksi_tagihan_siswa', 'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id', '=', 'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id')
                ->whereIn('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $jenisTagihanIds)
                ->where('dt_transaksi_tagihan_siswa.status_transaksi', '!=', 'dibatalkan')
                ->where('ms_transaksi_tagihan_siswa.status_transaksi', '!=', 'dibatalkan')
                ->whereNull('ms_tagihan_siswa.deleted_at')
                ->whereNull('ms_transaksi_tagihan_siswa.deleted_at')
                ->selectRaw('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id, SUM(dt_transaksi_tagihan_siswa.jumlah_bayar) as total_dibayarkan')
                ->groupBy('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id')
                ->get()
                ->keyBy('ms_jenis_tagihan_siswa_id');

            $jenisTagihan = $jenisTagihan->map(function ($jenis) use ($ringkasanPerJenis, $pembayaranPerJenis) {
                $ringkasan = $ringkasanPerJenis->get($jenis->ms_jenis_tagihan_siswa_id);
                $jenis->total_tagihan = (float) ($ringkasan->total_tagihan ?? 0);
                $jenis->total_dibayarkan = (float) ($pembayaranPerJenis->get($jenis->ms_jenis_tagihan_siswa_id)->total_dibayarkan ?? 0);

                return $jenis;
            });

            $totalDibayarkan = DetailTransaksiTagihanSiswa::query()
                ->where('status_transaksi', '!=', 'dibatalkan')
                ->whereHas('ms_transaksi_tagihan_siswa', function ($query) {
                    $query->where('status_transaksi', '!=', 'dibatalkan');
                })
                ->whereHas('ms_tagihan_siswa', function ($query) use ($kategori) {
                    $query->whereHas('ms_jenis_tagihan_siswa', function ($subQuery) use ($kategori) {
                        $subQuery->where(
                            'ms_kategori_tagihan_siswa_id',
                            $kategori->ms_kategori_tagihan_siswa_id
                        );
                    });
                })
                ->sum('jumlah_bayar');
        }

        $persentaseLunas = $totalTagihan > 0
            ? min(100, round(($totalDibayarkan / $totalTagihan) * 100))
            : 0;
        $totalKekurangan = $totalTagihan - $totalDibayarkan;

        return view('livewire.keuangan.kategori-tagihan-siswa.detail', [
            'kategori' => $kategori,
            'totalTagihan' => $totalTagihan,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
            'persentaseLunas' => $persentaseLunas,
            'jenisTagihan' => $jenisTagihan,
        ]);
    }
}