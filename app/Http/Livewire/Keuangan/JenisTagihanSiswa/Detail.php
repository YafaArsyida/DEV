<?php

namespace App\Http\Livewire\Keuangan\JenisTagihanSiswa;

use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\JenisTagihanSiswa as JenisTagihanSiswaModel;
use App\Models\TagihanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $selectedJenisTagihan = null;

    protected $listeners = [
        'loadDetailJenisTagihan' => 'loadDetailJenisTagihan',
    ];

    public function loadDetailJenisTagihan($jenisTagihanId)
    {
        $this->selectedJenisTagihan = $jenisTagihanId;
    }

    public function render()
    {
        $jenisTagihan = $this->selectedJenisTagihan
            ? JenisTagihanSiswaModel::with(['ms_kategori_tagihan_siswa', 'ms_jenjang', 'ms_tahun_ajar'])
                ->find($this->selectedJenisTagihan)
            : null;

        $totalTagihan = 0;
        $totalDibayarkan = 0;
        $jumlahSiswa = 0;
        $jumlahItemTagihan = 0;
        $jumlahBelumBayar = 0;
        $jumlahSebagian = 0;
        $jumlahLunas = 0;

        if ($jenisTagihan) {
            $tagihanQuery = TagihanSiswa::query()
                ->where('ms_jenis_tagihan_siswa_id', $jenisTagihan->ms_jenis_tagihan_siswa_id);

            $ringkasanTagihan = (clone $tagihanQuery)
                ->selectRaw("COUNT(*) as jumlah_item_tagihan,
                    SUM(CASE WHEN status = 'Belum Dibayar' THEN 1 ELSE 0 END) as jumlah_belum_bayar,
                    SUM(CASE WHEN status = 'Masih Dicicil' THEN 1 ELSE 0 END) as jumlah_sebagian,
                    SUM(CASE WHEN status = 'Lunas' THEN 1 ELSE 0 END) as jumlah_lunas")
                ->first();

            $jumlahItemTagihan = (int) ($ringkasanTagihan->jumlah_item_tagihan ?? 0);
            $jumlahBelumBayar = (int) ($ringkasanTagihan->jumlah_belum_bayar ?? 0);
            $jumlahSebagian = (int) ($ringkasanTagihan->jumlah_sebagian ?? 0);
            $jumlahLunas = (int) ($ringkasanTagihan->jumlah_lunas ?? 0);
            $totalTagihan = (clone $tagihanQuery)->sum('jumlah_tagihan_siswa');
            $jumlahSiswa = (clone $tagihanQuery)
                ->join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
                ->whereNull('ms_penempatan_siswa.deleted_at')
                ->distinct()
                ->count('ms_penempatan_siswa.ms_siswa_id');

            $totalDibayarkan = DetailTransaksiTagihanSiswa::query()
                ->where('status_transaksi', '!=', 'dibatalkan')
                ->whereHas('ms_transaksi_tagihan_siswa', function ($query) {
                    $query->where('status_transaksi', '!=', 'dibatalkan');
                })
                ->whereHas('ms_tagihan_siswa', function ($query) use ($jenisTagihan) {
                    $query->where(
                        'ms_jenis_tagihan_siswa_id',
                        $jenisTagihan->ms_jenis_tagihan_siswa_id
                    );
                })
                ->sum('jumlah_bayar');
        }

        $totalKekurangan = $totalTagihan - $totalDibayarkan;
        $persentaseLunas = $totalTagihan > 0
            ? min(100, round(($totalDibayarkan / $totalTagihan) * 100))
            : 0;

        return view('livewire.keuangan.jenis-tagihan-siswa.detail', [
            'jenisTagihan' => $jenisTagihan,
            'totalTagihan' => $totalTagihan,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
            'jumlahSiswa' => $jumlahSiswa,
            'persentaseLunas' => $persentaseLunas,
            'jumlahItemTagihan' => $jumlahItemTagihan,
            'jumlahBelumBayar' => $jumlahBelumBayar,
            'jumlahSebagian' => $jumlahSebagian,
            'jumlahLunas' => $jumlahLunas,
        ]);
    }
}
