<?php

namespace App\Http\Livewire\WhatsAppEduPay;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\WhatsAppEduPay;

class Create extends Component
{
    public $ms_jenjang_id;
    public $judul;
    public $salam_pembuka;
    public $kalimat_pembuka;
    public $kalimat_penutup;
    public $salam_penutup;

    protected $listeners = ['createPesanEduPay'];

    public function createPesanEduPay($ms_jenjang_id)
    {
        $this->resetInputFields(); // Reset input field setiap kali modal dibuka
        $this->ms_jenjang_id = $ms_jenjang_id;
    }

    public function resetInputFields()
    {
        $this->judul = '';
        $this->salam_pembuka = '';
        $this->kalimat_pembuka = '';
        $this->kalimat_penutup = '';
        $this->salam_penutup = '';
    }

    public function rules()
    {
        return [
            'ms_jenjang_id' => 'required|exists:ms_jenjang,ms_jenjang_id',
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

    public function createPesan()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Buat pesan baru
            WhatsAppEduPay::create([
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'judul' => $this->judul,
                'salam_pembuka' => $this->salam_pembuka,
                'kalimat_pembuka' => $this->kalimat_pembuka,
                'kalimat_penutup' => $this->kalimat_penutup,
                'salam_penutup' => $this->salam_penutup,
            ]);

            DB::commit();

            // Kirim notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan berhasil dibuat.']);
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'createPesanEduPay']);
            $this->emit('UpdatePesanTransaksiEduPay');
        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan saat membuat pesan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.whats-app-edu-pay.create');
    }
}
