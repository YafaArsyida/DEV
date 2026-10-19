<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use Livewire\Component;
use App\Models\PPDBPeriode;
use App\Models\PPDBPendaftaran;

class StatistikPendaftar extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjangId = null, $tahunAjarId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;
    }

    public function render()
    {
        $totalPendaftar = 0;
        $menungguVerifikasi = 0;
        $terverifikasi = 0;
        $perluPerbaikan = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $periode = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->where('status', 'aktif')
                ->first();

            if ($periode) {
                $query = PPDBPendaftaran::query()
                    ->where('ppdb_periode_id', $periode->ppdb_periode_id);

                $totalPendaftar = (clone $query)->count();

                $menungguVerifikasi = (clone $query)
                    ->where('status', 'pengecekan')
                    ->count();

                $terverifikasi = (clone $query)
                    ->where('status', 'diterima')
                    ->count();

                $perluPerbaikan = (clone $query)
                    ->where('status', 'perbaikan')
                    ->count();
            }
        }

        return view('livewire.p-p-d-b.admin.pendaftar.statistik-pendaftar',
            compact(
                'totalPendaftar',
                'menungguVerifikasi',
                'terverifikasi',
                'perluPerbaikan'
            )
        );
    }
}
