<?php

namespace App\Http\Livewire\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use Livewire\Component;

class Create extends Component
{
    public $ms_jenjang_id;

    public $nama_ekstrakurikuler, $biaya, $kuota, $deskripsi;

    protected $listeners = [
        'createEkstrakurikuler',
    ];

    public function createEkstrakurikuler($jenjang)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_ekstrakurikuler' => 'required|string|max:255',
            'biaya' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_ekstrakurikuler.required' => 'Nama Ekstrakurikuler tidak boleh kosong',
        'biaya.required' => 'Biaya wajib diisi',
        'biaya.numeric' => 'Biaya harus berupa angka',
        'biaya.min' => 'Biaya tidak boleh negatif',
        'kuota.required' => 'Kuota wajib diisi',
        'kuota.integer' => 'Kuota harus berupa bilangan bulat',
        'kuota.min' => 'Minimal kuota adalah 1',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        try {
            $validatedData = $this->validate();
            // Buat Ekstrakurikuler baru
            Ekstrakurikuler::create([
                'nama_ekstrakurikuler' => $this->nama_ekstrakurikuler,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'biaya' => $this->biaya,
                'kuota' => $this->kuota,
                'deskripsi' => $this->deskripsi,
            ]);

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah Ekstrakurikuler!']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        $this->resetInput();
        $this->emit('refreshEkstrakurikuler');
        $this->emit('refreshSiswas');
    }

    public function resetInput()
    {
        $this->nama_ekstrakurikuler = '';
        $this->biaya = '';
        $this->kuota = '';
        $this->deskripsi = '';
    }


    public function render()
    {
        return view('livewire.ekstrakurikuler.create');
    }
}
