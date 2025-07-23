<?php

namespace App\Http\Livewire\Pengguna;

use App\Models\User;
use Livewire\Component;

class Detail extends Component
{
    public $penggunaDetail;

    public $nama, $email, $peran, $telepon, $alamat, $created_at;
    public $aksesJenjang = [];

    protected $listeners = ['detailPengguna'];

    public function detailPengguna($ms_pengguna_id)
    {
        // Temukan pengguna berdasarkan ID
        $pengguna = User::findOrFail($ms_pengguna_id);

        // Simpan detail pengguna dalam variabel
        $this->penggunaDetail = $pengguna;

        // Isi properti dengan data pengguna
        $this->nama = $pengguna->nama;
        $this->email = $pengguna->email;
        $this->peran = $pengguna->peran;
        $this->created_at = $pengguna->created_at->format('d F Y H:i');
        // Ambil jenjang yang dapat diakses pengguna
        $this->aksesJenjang = $pengguna->ms_jenjang->pluck('nama_jenjang')->toArray();
    }
    public function render()
    {
        return view('livewire.pengguna.detail');
    }
}
