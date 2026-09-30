<?php

namespace App\Http\Livewire\Keuangan\PenempatanSiswa\Kelas;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $selectedKelas = null;

    protected $listeners = [
        'loadDetailKelas' => 'loadDetailKelas',
    ];

    public function loadDetailKelas($kelasId)
    {
        $this->selectedKelas = $kelasId;
    }

    public function render()
    {
        $kelas = $this->selectedKelas
            ? Kelas::with(['ms_jenjang', 'ms_tahun_ajar'])
                ->withCount('ms_penempatan_siswa')
                ->find($this->selectedKelas)
            : null;

        $totalTagihan = 0;
        $totalDibayarkan = 0;
        $jumlahItemTagihan = 0;
        $jumlahLunas = 0;
        $jumlahSebagian = 0;
        $jumlahBelumBayar = 0;

        if ($kelas) {
            $ringkasanTagihan = TagihanSiswa::query()
                ->whereHas('ms_penempatan_siswa', function ($query) use ($kelas) {
                    $query->where('ms_kelas_id', $kelas->ms_kelas_id);
                })
                ->selectRaw("COUNT(*) as jumlah_item_tagihan,
                    SUM(CASE WHEN status = 'Belum Dibayar' THEN 1 ELSE 0 END) as jumlah_belum_bayar,
                    SUM(CASE WHEN status = 'Masih Dicicil' THEN 1 ELSE 0 END) as jumlah_sebagian,
                    SUM(CASE WHEN status = 'Lunas' THEN 1 ELSE 0 END) as jumlah_lunas")
                ->first();

            $jumlahItemTagihan = (int) ($ringkasanTagihan->jumlah_item_tagihan ?? 0);
            $jumlahBelumBayar = (int) ($ringkasanTagihan->jumlah_belum_bayar ?? 0);
            $jumlahSebagian = (int) ($ringkasanTagihan->jumlah_sebagian ?? 0);
            $jumlahLunas = (int) ($ringkasanTagihan->jumlah_lunas ?? 0);

            $ringkasanKeuangan = PenempatanSiswa::query()
                ->where('ms_kelas_id', $kelas->ms_kelas_id)
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
                ], 'jumlah_bayar')
                ->get(['ms_penempatan_siswa_id']);

            $totalTagihan = $ringkasanKeuangan->sum('total_tagihan');
            $totalDibayarkan = $ringkasanKeuangan->sum('total_bayar');
        }

        $totalKekurangan = $totalTagihan - $totalDibayarkan;
        $persentaseLunas = $totalTagihan > 0
            ? round(($totalDibayarkan / $totalTagihan) * 100)
            : 0;

        return view('livewire.keuangan.penempatan-siswa.kelas.detail', [
            'kelas' => $kelas,
            'totalTagihan' => $totalTagihan,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
            'persentaseLunas' => $persentaseLunas,
            'jumlahItemTagihan' => $jumlahItemTagihan,
            'jumlahLunas' => $jumlahLunas,
            'jumlahSebagian' => $jumlahSebagian,
            'jumlahBelumBayar' => $jumlahBelumBayar,
        ]);
    }
}
