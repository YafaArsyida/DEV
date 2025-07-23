<?php

namespace App\Http\Livewire\Jabatan;

use App\Models\Jabatan;
use Livewire\Component;

class Edit extends Component
{
    public $ms_jabatan_id, $nama_jabatan, $deskripsi;

    protected $listeners = ['loadDataJabatan'];

    public function loadDataJabatan($ms_jabatan_id)
    {
        $jabatan = Jabatan::findOrFail($ms_jabatan_id);

        $this->ms_jabatan_id = $jabatan->ms_jabatan_id;
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->deskripsi = $jabatan->deskripsi;

        $this->dispatchBrowserEvent('show-edit-modal'); // Menampilkan modal
    }

    public function rules()
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

    public function updateJabatan()
    {
        $validatedData = $this->validate();

        $jabatan = Jabatan::where('ms_jabatan_id', $this->ms_jabatan_id)->firstOrFail();
        $jabatan->update($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah jabatan!']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditJabatan']);
        $this->emit('refreshJabatans');
    }
    public function render()
    {
        return view('livewire.jabatan.edit');
    }
}
