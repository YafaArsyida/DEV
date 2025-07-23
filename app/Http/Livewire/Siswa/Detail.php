<?php

namespace App\Http\Livewire\Siswa;

use App\Http\Controllers\HelperController;
use Livewire\Component;
use App\Models\PenempatanSiswa as PenempatanSiswaModel;


class Detail extends Component
{
    public $siswaDetail;

    public $nama_siswa, $nisn, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $alamat, $nama_ayah, $nama_ibu, $telepon, $deskripsi;

    public $educard, $edupay;

    public $nama_petugas, $nama_jenjang, $nama_tahun_ajar, $nama_kelas;
    public $ms_penempatan_siswa_id, $ms_siswa_id, $ms_kelas_id, $ms_jenjang_id, $ms_tahun_ajar_id, $ms_pengguna_id, $created_at;

    protected $listeners = ['showDetailSiswa'];

    public function showDetailSiswa($ms_penempatan_siswa_id)
    {
        // Menemukan entitas PenempatanSiswa berdasarkan ID dan memuat relasi yang dibutuhkan
        $siswa = PenempatanSiswaModel::with('ms_siswa.ms_educard', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($ms_penempatan_siswa_id);

        // Menyimpan detail siswa dalam variabel
        $this->siswaDetail = $siswa;

        // Mengisi properti dengan data yang ditemukan
        $this->ms_penempatan_siswa_id = $siswa->ms_penempatan_siswa_id;
        $this->ms_jenjang_id = $siswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id;
        $this->ms_kelas_id = $siswa->ms_kelas_id;
        $this->ms_siswa_id = $siswa->ms_siswa_id;
        $this->ms_pengguna_id = $siswa->ms_pengguna_id;

        $this->nama_petugas = $siswa->ms_pengguna->nama;
        $this->nama_jenjang = $siswa->ms_jenjang->nama_jenjang;
        $this->nama_tahun_ajar = $siswa->ms_tahun_ajar->nama_tahun_ajar;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas;

        $this->nama_siswa = $siswa->ms_siswa->nama_siswa;
        $this->nisn = $siswa->ms_siswa->nisn;
        $this->tempat_lahir = $siswa->ms_siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->ms_siswa->tanggal_lahir;
        $this->jenis_kelamin = $siswa->ms_siswa->jenis_kelamin;
        $this->alamat = $siswa->ms_siswa->alamat;
        $this->nama_ayah = $siswa->ms_siswa->nama_ayah;
        $this->nama_ibu = $siswa->ms_siswa->nama_ibu;
        $this->telepon = $siswa->ms_siswa->telepon;
        $this->deskripsi = $siswa->ms_siswa->deskripsi;
        $this->created_at = HelperController::formatTanggalIndonesia($siswa->ms_siswa->created_at, 'd F Y H:i');

        $this->educard = $siswa->ms_siswa->ms_educard ? $siswa->ms_siswa->ms_educard->kode_kartu : null;
        $this->edupay = $siswa->ms_siswa->saldo_edupay_siswa();
    }

    public function render()
    {
        return view('livewire.siswa.detail');
    }
}
