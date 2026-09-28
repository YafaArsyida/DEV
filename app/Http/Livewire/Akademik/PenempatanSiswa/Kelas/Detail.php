<?php

namespace App\Http\Livewire\Akademik\PenempatanSiswa\Kelas;

use App\Models\PenempatanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $selectedKelasDetail = null;
    public $namaKelasCurrent = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $searchSiswaDetail = '';

    protected $listeners = ['loadDetailKelas' => 'loadDetail'];

    public function loadDetail($params)
    {
        $this->selectedKelasDetail = $params['kelasId'] ?? null;
        $this->namaKelasCurrent = $params['namaKelas'] ?? '';
        $this->selectedJenjang = $params['jenjang'] ?? null;
        $this->selectedTahunAjar = $params['tahunAjar'] ?? null;
        $this->searchSiswaDetail = '';
    }
    public function render()
    {
        $siswaDetailList = [];

        if ($this->selectedKelasDetail) {
            $query = PenempatanSiswa::where('ms_kelas_id', $this->selectedKelasDetail)
                ->with(['ms_siswa', 'ms_kelas']);

            if ($this->searchSiswaDetail) {
                $query->whereHas('ms_siswa', function ($q) {
                    $q->where('nama_siswa', 'like', '%' . $this->searchSiswaDetail . '%');
                });
            }

            $siswaDetailList = $query->orderBy('ms_siswa_id')->get();
        }

        return view('livewire.akademik.penempatan-siswa.kelas.detail',[
            'siswaDetailList' => $siswaDetailList,
        ]);
    }
}
