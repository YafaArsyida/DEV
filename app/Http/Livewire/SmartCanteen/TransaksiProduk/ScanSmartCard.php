<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\EduCard;
use Livewire\Component;

class ScanSmartCard extends Component
{
    public $educard;
    public $nama_siswa;
    public $ms_siswa_id;
    public $saldo_edupay;
    public $smartcard;

    protected $listeners = [
        'openScanModal'
    ];

    // Reset saat modal dibuka
    public function openScanModal()
    {
        $this->reset(['educard', 'nama_siswa', 'saldo_edupay', 'smartcard']);
    }

    // Jalankan ketika kode kartu diinputkan
    public function updatedEducard($value)
    {
        $card = EduCard::with('ms_siswa')
            ->where('kode_kartu', $value)
            ->first();

        if ($card && $card->ms_siswa) {
            $this->ms_siswa_id = $card->ms_siswa->ms_siswa_id;
            $this->nama_siswa = $card->ms_siswa->nama_siswa;
            $this->educard  = $card->kode_kartu;
            $this->saldo_edupay      = $card->ms_siswa->saldo_edupay_siswa();

            // Emit ke parent / komponen lain kalau perlu
            $this->emit('scanSuccess', [
                'ms_siswa_id'   => $this->ms_siswa_id,
                'nama_siswa'   => $this->nama_siswa,
                'educard'  => $this->educard,
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
