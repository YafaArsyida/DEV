<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\EduCard;
use Livewire\Component;

class ScanSmartCard extends Component
{
    public $user_type;
    public $user_id;
    public $nama;
    public $educard;
    public $saldo_edupay;

    protected $listeners = [
        'openScanModal'
    ];

    // Reset saat modal dibuka
    public function openScanModal()
    {
        $this->reset([
            'user_type',
            'user_id',
            'nama',
            'educard',
            'saldo_edupay',
        ]);
    }

    // Jalankan ketika kode kartu diinputkan
    public function updatedEduCard($value)
    {
        $card = EduCard::with(['ms_siswa', 'ms_pegawai'])
            ->where('kode_kartu', $value)
            ->first();

        if ($card) {
            if ($card->ms_siswa) {
                // Jika pemilik kartu adalah siswa
                $this->user_type    = 'siswa';
                $this->user_id      = $card->ms_siswa->ms_siswa_id;
                $this->nama         = $card->ms_siswa->nama_siswa;
                $this->educard      = $card->kode_kartu;
                $this->saldo_edupay = $card->ms_siswa->saldo_edupay_siswa();
            } elseif ($card->ms_pegawai) {
                // Jika pemilik kartu adalah pegawai
                $this->user_type    = 'pegawai';
                $this->user_id      = $card->ms_pegawai->ms_pegawai_id;
                $this->nama         = $card->ms_pegawai->nama_pegawai;
                $this->educard      = $card->kode_kartu;
                $this->saldo_edupay = 1234;
                // $this->saldo_edupay = $card->ms_pegawai->saldo_edupay_pegawai();
            }

            // Emit ke parent / komponen lain kalau perlu
            $this->emit('scanSuccess', [
                'user_type'     => $this->user_type,
                'user_id'       => $this->user_id,
                'nama'          => $this->nama,
                'educard'       => $this->educard,
                'saldo_edupay'  => $this->saldo_edupay,
            ]);

            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalScanRFID']);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kartu sukses.']);
        } else {
            $this->emit('scanFailed', 'Kartu tidak dikenali');
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak Terdaftar.']);
        }
    }

    public function render()
    {
        return view('livewire.smart-canteen.transaksi-produk.scan-smart-card');
    }
}
