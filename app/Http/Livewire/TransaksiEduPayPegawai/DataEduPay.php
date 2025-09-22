<?php

namespace App\Http\Livewire\TransaksiEduPayPegawai;

use App\Models\Pegawai;
use App\Models\TransaksiEduPay;
use Livewire\Component;

class DataEduPay extends Component
{
    public $ms_pegawai_id;

    public $selectedJenjang = null;

    protected $listeners = [
        'refreshEduPays',
        'pegawaiSelected'
    ];

    public function refreshEduPays()
    {
        $this->emitSelf('$refresh');
    }

    public function pegawaiSelected($ms_pegawai_id){
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard')
            ->findOrFail($ms_pegawai_id);

        if (!$pegawai) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pegawai tidak ditemukan.']);
            return;
        }

        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->selectedJenjang = $pegawai->ms_jenjang_id;

        // Emit refresh agar data di render diperbaruip
        $this->emitSelf('$refresh');
    }
    public function render()
    {
        $saldo = 0; // Inisialisasi di luar closure
        /// Query data tabungan siswa jika siswa dipilih
        $transaksiEduPay = $this->ms_pegawai_id
            ? TransaksiEduPay::where('user_type', 'pegawai')
            ->where('user_id', $this->ms_pegawai_id)
            // ->orderBy('tanggal', 'ASC')
            ->get()
            ->map(function ($item) use (&$saldo) {
                switch ($item->jenis_transaksi) {
                    case 'topup tunai':
                    case 'topup online':
                    case 'pengembalian dana':
                        $saldo += $item->nominal;
                        break;

                    case 'penarikan':
                    case 'pembayaran':
                    case 'kantin': // 👈 transaksi kantin kurangi saldo
                        $saldo -= $item->nominal;
                        break;
                }

                $item->saldo = $saldo;
                return $item;
            })
            : collect();

        return view('livewire.transaksi-edu-pay-pegawai.data-edu-pay',[
            'transaksiEduPay' => $transaksiEduPay,
        ]);
    }
}
