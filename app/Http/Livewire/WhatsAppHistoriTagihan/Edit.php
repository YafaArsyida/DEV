<?php

namespace App\Http\Livewire\WhatsAppHistoriTagihan;

use Livewire\Component;
use App\Models\WhatsAppHistoriTagihan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public $ms_whatsapp_histori_tagihan_id;
    public $judul;
    public $salam_pembuka;
    public $kalimat_pembuka;
    public $detail_transaksi;
    public $kalimat_penutup;
    public $salam_penutup;

    protected $listeners = ['loadPesanTransaksi'];

    public function loadPesanTransaksi($ms_whatsapp_histori_tagihan_id)
    {
        $pesan = WhatsAppHistoriTagihan::findOrFail($ms_whatsapp_histori_tagihan_id);

        $this->ms_whatsapp_histori_tagihan_id = $pesan->ms_whatsapp_histori_tagihan_id;
        $this->judul = $pesan->judul;
        $this->salam_pembuka = $pesan->salam_pembuka;
        $this->kalimat_pembuka = $pesan->kalimat_pembuka;
        $this->kalimat_penutup = $pesan->kalimat_penutup;
        $this->salam_penutup = $pesan->salam_penutup;
    }

    public function rules()
    {
        return [
            'judul' => 'required|string|max:255',
            'salam_pembuka' => 'required|string|max:255',
            'kalimat_pembuka' => 'required|string|max:500',
            'kalimat_penutup' => 'required|string|max:500',
            'salam_penutup' => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'judul.required' => 'Judul wajib diisi.',
        'judul.string' => 'Judul harus berupa teks.',
        'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
        'salam_pembuka.required' => 'Salam pembuka wajib diisi.',
        'salam_pembuka.string' => 'Salam pembuka harus berupa teks.',
        'salam_pembuka.max' => 'Salam pembuka tidak boleh lebih dari 255 karakter.',
        'kalimat_pembuka.required' => 'Kalimat pembuka wajib diisi.',
        'kalimat_pembuka.string' => 'Kalimat pembuka harus berupa teks.',
        'kalimat_pembuka.max' => 'Kalimat pembuka tidak boleh lebih dari 500 karakter.',
        'kalimat_penutup.required' => 'Kalimat penutup wajib diisi.',
        'kalimat_penutup.string' => 'Kalimat penutup harus berupa teks.',
        'kalimat_penutup.max' => 'Kalimat penutup tidak boleh lebih dari 500 karakter.',
        'salam_penutup.required' => 'Salam penutup wajib diisi.',
        'salam_penutup.string' => 'Salam penutup harus berupa teks.',
        'salam_penutup.max' => 'Salam penutup tidak boleh lebih dari 255 karakter.',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updatePesan()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Cari data pesan yang akan diperbarui
            $pesan = WhatsAppHistoriTagihan::withTrashed()->findOrFail($this->ms_whatsapp_histori_tagihan_id);

            // Perbarui data pesan
            $pesan->update([
                'judul' => $this->judul,
                'salam_pembuka' => $this->salam_pembuka,
                'kalimat_pembuka' => $this->kalimat_pembuka,
                'kalimat_penutup' => $this->kalimat_penutup,
                'salam_penutup' => $this->salam_penutup,
            ]);

            DB::commit();

            // Kirim notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan berhasil diperbarui.']);
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalEdit']);
            $this->emit('UpdatePesanTransaksiTagihan');
        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan saat memperbarui pesan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.whats-app-histori-tagihan.edit');
    }
}
