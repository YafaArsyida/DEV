<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use Livewire\Component;
use App\Models\PPDBPeriode;
use App\Models\PPDBPendaftaran;

class StatistikVerifikasi extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'pendaftaranDiperbarui' => '$refresh',
    ];

    /**
     * Menerima perubahan parameter periode PPDB.
     */
    public function updateParameters($jenjangId = null, $tahunAjarId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;
    }

    public function render()
    {
        $totalAntrian = 0;
        $menungguPemeriksaan = 0;
        $totalPerbaikan = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $periode = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->where('status', 'aktif')
                ->first();

            if ($periode) {
                $query = PPDBPendaftaran::query()
                    ->where('ppdb_periode_id', $periode->ppdb_periode_id);

                $totalAntrian = (clone $query)
                    ->whereIn('status', ['pengecekan', 'perbaikan'])
                    ->count();

                $menungguPemeriksaan = (clone $query)
                    ->where('status', 'pengecekan')
                    ->count();

                $totalPerbaikan = (clone $query)
                    ->where('status', 'perbaikan')
                    ->count();
            }
        }

        return view('livewire.p-p-d-b.admin.verifikasi.statistik-verifikasi',
            compact(
                'totalAntrian',
                'menungguPemeriksaan',
                'totalPerbaikan'
            )
        );
    }
}
