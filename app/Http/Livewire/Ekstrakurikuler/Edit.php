<?php

namespace App\Http\Livewire\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use Livewire\Component;

class Edit extends Component
{
    public $ms_jenjang_id;
    public $ms_ekstrakurikuler_id;

    public $nama_ekstrakurikuler, $biaya, $kuota, $deskripsi;

    protected $listeners = ['loadEkstrakurikuler'];

    public function loadEkstrakurikuler($ms_ekstrakurikuler_id)
    {
        $ekstrakurikuler = Ekstrakurikuler::findOrFail($ms_ekstrakurikuler_id);

        $this->ms_ekstrakurikuler_id = $ekstrakurikuler->ms_ekstrakurikuler_id;
        $this->nama_ekstrakurikuler = $ekstrakurikuler->nama_ekstrakurikuler;
        $this->biaya = $ekstrakurikuler->biaya;
        $this->kuota = $ekstrakurikuler->kuota;
        $this->deskripsi = $ekstrakurikuler->deskripsi;
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

    public function updateEkstrakurikuler()
    {
        $validatedData = $this->validate();
        $ekstrakurikuler = Ekstrakurikuler::where('ms_ekstrakurikuler_id', $this->ms_ekstrakurikuler_id)->firstOrFail();

        $ekstrakurikuler->update($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah kelas!']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'editEkstrakurikuler']);
        $this->emit('refreshEkstrakurikuler');
        $this->emit('refreshSiswas');
    }
    public function render()
    {
        return view('livewire.ekstrakurikuler.edit');
    }
}
