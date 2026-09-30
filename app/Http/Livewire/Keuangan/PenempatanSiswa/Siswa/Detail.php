<?php

namespace App\Http\Livewire\Keuangan\PenempatanSiswa\Siswa;

use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $selectedPenempatanSiswaId = null;

    protected $listeners = [
        'showDetailSiswa' => 'showDetailSiswa',
    ];

    public function showDetailSiswa($penempatanSiswaId)
    {
        $this->selectedPenempatanSiswaId = $penempatanSiswaId;
    }

    public function render()
    {
        $penempatanSiswa = $this->selectedPenempatanSiswaId
            ? PenempatanSiswa::with([
                'ms_siswa',
                'ms_kelas',
                'ms_tahun_ajar',
            ])->find($this->selectedPenempatanSiswaId)
            : null;

        $tagihans = collect();
        $totalTagihan = 0;
        $totalDibayarkan = 0;

        if ($penempatanSiswa) {
            $tagihans = TagihanSiswa::query()
                ->with('ms_jenis_tagihan_siswa')
                ->where('ms_penempatan_siswa_id', $penempatanSiswa->ms_penempatan_siswa_id)
                ->withSum([
                    'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                        $query->where(
                            'dt_transaksi_tagihan_siswa.status_transaksi',
                            '!=',
                            'dibatalkan'
                        )->whereHas('ms_transaksi_tagihan_siswa', function ($transaksi) {
                            $transaksi->where('status_transaksi', '!=', 'dibatalkan');
                        });
                    },
                ], 'jumlah_bayar')
                ->orderBy('ms_jenis_tagihan_siswa_id')
                ->get();

            $ringkasanJenisTagihan = $tagihans
                ->groupBy('ms_jenis_tagihan_siswa_id')
                ->map(function ($items) {
                    return [
                        'nama_tagihan' => $items->first()->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tagihan',
                        'total_tagihan' => $items->sum('jumlah_tagihan_siswa'),
                        'total_bayar' => $items->sum('total_bayar'),
                    ];
                })
                ->values();

            $totalTagihan = $ringkasanJenisTagihan->sum('total_tagihan');
            $totalDibayarkan = $ringkasanJenisTagihan->sum('total_bayar');
        } else {
            $ringkasanJenisTagihan = collect();
        }

        $sisaTagihan = max(0, $totalTagihan - $totalDibayarkan);
        $persentaseLunas = $totalTagihan > 0
            ? round(($totalDibayarkan / $totalTagihan) * 100)
            : 0;
        $progressLunas = min(max($persentaseLunas, 0), 100);

        return view('livewire.keuangan.penempatan-siswa.siswa.detail', [
            'penempatanSiswa' => $penempatanSiswa,
            'ringkasanJenisTagihan' => $ringkasanJenisTagihan,
            'totalTagihan' => $totalTagihan,
            'totalDibayarkan' => $totalDibayarkan,
            'sisaTagihan' => $sisaTagihan,
            'persentaseLunas' => $persentaseLunas,
            'progressLunas' => $progressLunas,
        ]);
    }
}
