<?php

namespace App\Http\Livewire\Jenjang;

use Livewire\Component;
use App\Models\Jenjang as JenjangModel;

class Edit extends Component
{
    public $ms_jenjang_id, $nama_jenjang, $urutan, $deskripsi;

    protected $listeners = ['loadDataJenjang'];

    public function loadDataJenjang($ms_jenjang_id)
    {
        $jenjang = JenjangModel::findOrFail($ms_jenjang_id);

        $this->ms_jenjang_id = $jenjang->ms_jenjang_id;
        $this->nama_jenjang = $jenjang->nama_jenjang;
        $this->urutan = $jenjang->urutan;
        $this->deskripsi = $jenjang->deskripsi;

        $this->dispatchBrowserEvent('show-edit-modal'); // Menampilkan modal
    }

    public function rules()
    {
        return [
            'nama_jenjang' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_jenjang.required' => 'Nama jenjang tidak boleh kosong',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateJenjang()
    {
        $validatedData = $this->validate();

        $jenjang = JenjangModel::where('ms_jenjang_id', $this->ms_jenjang_id)->firstOrFail();
        $jenjang->update($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah jenjang!']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditJenjang']);
        $this->emit('refreshJenjangs');
    }

    public function render()
    {
        return view('livewire.jenjang.edit');
    }
}
