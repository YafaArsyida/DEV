<?php

namespace App\Http\Livewire\PPDB\Admin\Pengaturan;

use Livewire\Component;
use App\Models\PPDBPeriode;
use App\Models\PPDBGelombang;
use App\Models\PPDBJalur;

class StatistikPengaturan extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    /**
     * Menerima perubahan parameter jenjang dan tahun ajar.
     */
    public function updateParameters($jenjangId = null, $tahunAjarId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;
    }

    public function render()
    {
        $totalPeriode = 0;
        $totalGelombang = 0;
        $totalJalur = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $periodeQuery = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            $periodeIds = (clone $periodeQuery)
                ->select('ppdb_periode_id');

            $totalPeriode = (clone $periodeQuery)->count();

            $totalGelombang = PPDBGelombang::query()
                ->whereIn('ppdb_periode_id', $periodeIds)
                ->count();

            $totalJalur = PPDBJalur::query()
                ->whereIn('ppdb_periode_id', $periodeIds)
                ->count();
        }

        return view('livewire.p-p-d-b.admin.pengaturan.statistik-pengaturan',
            compact(
                'totalPeriode',
                'totalGelombang',
                'totalJalur'
            )
        );
    }
}
