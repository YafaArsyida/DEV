<?php

namespace App\Http\Livewire\SmartPass\FingerSpotPresensiPegawai;

use App\Models\FingerSpot\PresensiPegawaiLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'bootstrap'; // agar pagination bootstrap

    public function render()
    {
        $presensi = PresensiPegawaiLog::with(['ms_pegawai.ms_jabatan'])
            ->orderBy('scan_time', 'desc')
            ->paginate(10);

        return view('livewire.smart-pass.finger-spot-presensi-pegawai.index', [
            'presensi' => $presensi
        ]);
    }
}
