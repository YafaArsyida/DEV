<?php

namespace App\Http\Livewire\SiswaEkstrakurikuler;

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
        // Temukan pengguna berdasarkan ID
        $penempatan = PenempatanSiswa::with([
            'ms_siswa.ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
            'ms_kelas'
        ])->findOrFail($ms_penempatan_siswa_id);

        if (!$penempatan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Siswa tidak ditemukan']);
            return;
        }

        $siswa = $penempatan->ms_siswa;

        $this->siswaDetail = $siswa;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->telepon = $siswa->telepon;
        $this->created_at = $siswa->created_at->format('d F Y H:i');

        $this->nama_kelas = $penempatan->ms_kelas->nama_kelas ?? '-';

        $this->ekstrakurikulerSiswa = $siswa->ms_penempatan_ekstrakurikuler;
    }
    public function render()
    {
        return view('livewire.siswa-ekstrakurikuler.detail');
    }
}
