<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\PenempatanSiswa;
use Livewire\Component;

class DataSiswa extends Component
{
    public $nama_jenjang = null, $nama_tahun_ajar = null, $ms_penempatan_siswa_id = null, $nama_siswa = null, $nama_kelas = null, $telepon = null, $educard = null, $deskripsi = null;

    protected $listeners = [
        'refreshSiswas',
        'siswaSelected'
    ];

    public function refreshSiswas()
    {
        // Jika siswa sedang dipilih, perbarui data siswa
        if ($this->ms_penempatan_siswa_id) {
            $this->siswaSelected($this->ms_penempatan_siswa_id);
        }

        // Emit refresh untuk memperbarui komponen lainnya
        $this->emitSelf('$refresh');
    }

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        // Mengambil detail siswa berdasarkan ms_penempatan_siswa_id
        $siswa = PenempatanSiswa::with('ms_siswa', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($ms_penempatan_siswa_id);

        // Mengisi properti dengan data yang ditemukan
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;
        $this->nama_siswa = $siswa->ms_siswa->nama_siswa;
        $this->nama_jenjang = $siswa->ms_jenjang->nama_jenjang;
        $this->nama_tahun_ajar = $siswa->ms_tahun_ajar->nama_tahun_ajar;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas;
        $this->telepon = $siswa->ms_siswa->telepon;
        $this->educard = $siswa->ms_siswa->ms_educard?->kode_kartu;
        $this->deskripsi = $siswa->ms_siswa->deskripsi;
    }
    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.data-siswa');
    }
}
