<?php

namespace App\Http\Livewire\Akademik\PenempatanEkstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\PenempatanEkstrakurikuler;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use Livewire\Component;

class Detail extends Component
{
    public $siswaDetail;

    public $nama_siswa, $nama_kelas, $telepon, $created_at;
    public $ekstrakurikulerSiswa = [];

    protected $listeners = ['detailSiswaEkstrakurikuler'];

    public function detailSiswaEkstrakurikuler($ms_penempatan_siswa_id)
    {
        $penempatan = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
        ])->findOrFail($ms_penempatan_siswa_id);

        $this->siswaDetail = $penempatan->ms_siswa;
        $this->nama_siswa = $penempatan->ms_siswa->nama_siswa;
        $this->telepon = $penempatan->ms_siswa->telepon;
        $this->created_at = $penempatan->ms_siswa->created_at->format('d F Y H:i');

        $this->nama_kelas = $penempatan->ms_kelas->nama_kelas ?? '-';

        $this->ekstrakurikulerSiswa = $penempatan->ms_penempatan_ekstrakurikuler;
    }
    public function render()
    {
        return view('livewire.keuangan.siswa-ekstrakurikuler.detail');
    }
}
