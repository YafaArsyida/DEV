<?php

namespace App\Http\Livewire\PPDB\Admin\Dashboard;

use Livewire\Component;
use App\Models\PPDBPeriode;
use App\Models\PPDBPendaftaran;

class StatistikJalur extends Component
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
        $perluPerbaikan = 0;
        $diterima = 0;

        // Query hanya dijalankan jika kedua parameter tersedia.
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
                    ->whereIn('status', [
                        'pengecekan',
                    ])
                    ->count();

                $perluPerbaikan = (clone $query)
                    ->where('status', 'perbaikan')
                    ->count();

                $diterima = (clone $query)
                    ->where('status', 'diterima')
                    ->count();
            }
        }

        $statistik = [
            [
                'label' => 'Total Pendaftar',
                'value' => number_format($totalPendaftar, 0, ',', '.'),
                'description' => 'Seluruh pendaftar pada periode ini',
                'color' => 'primary',
                'icon' => 'mdi-account-group-outline',
            ],
            [
                'label' => 'Menunggu Verifikasi',
                'value' => number_format($menungguVerifikasi, 0, ',', '.'),
                'description' => 'Pendaftaran perlu diperiksa',
                'color' => 'info',
                'icon' => 'mdi-clipboard-text-clock-outline',
            ],
            [
                'label' => 'Perlu Perbaikan',
                'value' => number_format($perluPerbaikan, 0, ',', '.'),
                'description' => 'Menunggu perbaikan dari orang tua',
                'color' => 'warning',
                'icon' => 'mdi-file-edit-outline',
            ],
            [
                'label' => 'Diterima',
                'value' => number_format($diterima, 0, ',', '.'),
                'description' => 'Calon siswa dinyatakan diterima',
                'color' => 'success',
                'icon' => 'mdi-check-circle-outline',
            ],
        ];

        return view('livewire.p-p-d-b.admin.dashboard.statistik-jalur',
            compact('statistik')
        );
    }
}
