<?php

namespace App\Http\Livewire\Akademik\Kelas;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $selectedKelasDetail = null;

    protected $listeners = [
        'loadDetailKelas' => 'loadDetail',
    ];

    public function loadDetail($kelasId)
    {
        $this->selectedKelasDetail = $kelasId;
    }


    public function render()
    {
        $kelasDetail = null;
        $jumlahSiswa = 0;

        if ($this->selectedKelasDetail) {
            $kelasDetail = Kelas::with([
                'ms_jenjang',
                'ms_tahun_ajar',
            ])->find($this->selectedKelasDetail);

            $jumlahSiswa = PenempatanSiswa::where(
                'ms_kelas_id',
                $this->selectedKelasDetail
            )->count();
        }

        return view('livewire.akademik.kelas.detail',[
            'kelasDetail' => $kelasDetail,
            'jumlahSiswa' => $jumlahSiswa,
        ]);
    }
}
