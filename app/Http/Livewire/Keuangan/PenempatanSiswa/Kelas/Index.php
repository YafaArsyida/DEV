<?php

namespace App\Http\Livewire\Keuangan\PenempatanSiswa\Kelas;

use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $parameterLoaded = false;

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';


    protected $listeners = [
        'refreshKelass' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $jenjangData = Jenjang::find($jenjang);
        $tahunAjarData = TahunAjar::find($tahunAjar);

        $this->namaJenjang = $jenjangData
            ? $jenjangData->nama_jenjang
            : 'Tidak Diketahui';

        $this->namaTahunAjar = $tahunAjarData
            ? $tahunAjarData->nama_tahun_ajar
            : 'Tidak Diketahui';

        $this->parameterLoaded = true;

        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $kelass = collect();

        $totalKelas = 0;
        $totalSiswa = 0;
        $totalTagihan = 0;
        $totalLunas = 0;
        $persentaseLunas = 0;

        if ($this->parameterLoaded) {

            $query = Kelas::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->when(
                    $this->search,
                    fn ($q) => $q->where(
                        'nama_kelas',
                        'like',
                        '%' . $this->search . '%'
                    )
                )
                ->withCount('ms_penempatan_siswa')
                ->withSum('ms_tagihan_siswa', 'jumlah_tagihan_siswa')
                ->selectSub(function ($query) {
                    $query->from('dt_transaksi_tagihan_siswa')
                        ->join(
                            'ms_transaksi_tagihan_siswa',
                            'dt_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id',
                            '=',
                            'ms_transaksi_tagihan_siswa.ms_transaksi_tagihan_siswa_id'
                        )
                        ->join(
                            'ms_tagihan_siswa',
                            'dt_transaksi_tagihan_siswa.ms_tagihan_siswa_id',
                            '=',
                            'ms_tagihan_siswa.ms_tagihan_siswa_id'
                        )
                        ->join(
                            'ms_penempatan_siswa',
                            'ms_tagihan_siswa.ms_penempatan_siswa_id',
                            '=',
                            'ms_penempatan_siswa.ms_penempatan_siswa_id'
                        )
                        ->whereColumn(
                            'ms_penempatan_siswa.ms_kelas_id',
                            'ms_kelas.ms_kelas_id'
                        )
                        ->where('dt_transaksi_tagihan_siswa.status_transaksi', '!=', 'dibatalkan')
                        ->where('ms_transaksi_tagihan_siswa.status_transaksi', '!=', 'dibatalkan')
                        ->selectRaw(
                            'COALESCE(SUM(dt_transaksi_tagihan_siswa.jumlah_bayar), 0)'
                        );
                }, 'total_dibayarkan')
                ->latest('ms_kelas_id');

            $kelass = $query->paginate(50);
            
        }

        $rataRataSiswa = $totalKelas > 0
            ? round($totalSiswa / $totalKelas, 1)
            : 0;

        return view('livewire.keuangan.penempatan-siswa.kelas.index',[
            'kelass' => $kelass,
        ]);
    }
}
