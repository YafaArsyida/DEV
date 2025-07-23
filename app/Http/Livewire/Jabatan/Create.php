<?php

namespace App\Http\Livewire\Jabatan;

use App\Models\Jabatan;
use Livewire\Component;

class Create extends Component
{
    public $nama_jabatan, $deskripsi;

    protected function rules()
    {
        return [
            'nama_jabatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_jabatan.required' => 'Nama jabatan tidak boleh kosong',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $validatedData = $this->validate();
        Jabatan::create($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah jabatan!']);
        $this->resetInput();
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAddJabatan']);
        $this->emit('refreshJabatans');
    }

    public function resetInput()
    {
        $this->nama_jabatan = '';
        $this->deskripsi = '';
    }
    public function render()
    {
        return view('livewire.jabatan.create');
    }
}
