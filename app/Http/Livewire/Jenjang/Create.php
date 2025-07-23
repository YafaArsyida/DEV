<?php

namespace App\Http\Livewire\Jenjang;

use Livewire\Component;
use App\Models\Jenjang as JenjangModel;

class Create extends Component
{
    public $nama_jenjang, $urutan, $deskripsi;

    protected function rules()
    {
        return [
            'nama_jenjang' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1|unique:ms_jenjang,urutan',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_jenjang.required' => 'Nama jenjang tidak boleh kosong',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
        'urutan.unique' => 'Urutan ini sudah digunakan, pilih angka lain',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $validatedData = $this->validate();
        JenjangModel::create($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah jenjang!']);
        $this->resetInput();
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAddJenjang']);
        $this->emit('refreshJenjangs');
    }

    public function resetInput()
    {
        $this->nama_jenjang = '';
        $this->urutan = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.jenjang.create');
    }
}
