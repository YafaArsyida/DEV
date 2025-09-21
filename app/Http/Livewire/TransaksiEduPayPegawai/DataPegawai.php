<?php

namespace App\Http\Livewire\TransaksiEduPayPegawai;

use App\Models\Pegawai;
use Livewire\Component;

class DataPegawai extends Component
{
    public $ms_pegawai_id = null;
    public $nama_pegawai = null;
    public $educard_pegawai = null;
    public $jabatan = null;
    public $telepon_pegawai = null;
    public $alamat_pegawai = null;

    // saldo edupay pegawai
    public $saldo_edupay_pegawai;
    public $total_pemasukan_pegawai;
    public $total_pengeluaran_pegawai;

    protected $listeners = [
        'pegawaiSelected',
        'refreshEduPays'
    ];

    public function refreshEduPays()
    {
        if ($this->ms_pegawai_id) {
            $this->pegawaiSelected($this->ms_pegawai_id);
        }
    }

    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard')
            ->findOrFail($ms_pegawai_id);

        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->nama_pegawai = $pegawai->nama_pegawai;
        $this->educard_pegawai = $pegawai->ms_educard->kode_kartu ?? null;
        $this->jabatan = $pegawai->ms_jabatan->nama_jabatan ?? null;
        $this->telepon_pegawai = $pegawai->telepon;
        $this->alamat_pegawai = $pegawai->alamat;

        // hitung saldo
        $this->saldo_edupay_pegawai = '123';
        $this->total_pemasukan_pegawai = '123';
        $this->total_pengeluaran_pegawai = '123';
    }

    public function render()
    {
        return view('livewire.transaksi-edu-pay-pegawai.data-pegawai');
    }
}
