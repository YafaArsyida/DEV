<?php

namespace App\Http\Livewire\AkuntansiKelompokRekening;

use App\Models\AkuntansiKelompokRekening;
use Livewire\Component;

class Create extends Component
{
    public $nama_kelompok_rekening, $deskripsi;

    protected $listeners = [
        'CreateKelompokRekening',
    ];

    public function CreateKelompokRekening()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    protected function rules()
    {
        return [
            'nama_kelompok_rekening' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kelompok_rekening.required' => 'Nama kategori tidak boleh kosong',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        try {
            $validatedData = $this->validate();
            AkuntansiKelompokRekening::create($validatedData);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah kategori!']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        $this->resetInput();
        $this->emit('refreshAkuntansiKelompokRekening');
        $this->emit('refreshAkuntansiRekening');
    }

    public function resetInput()
    {
        $this->nama_kelompok_rekening = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.akuntansi-kelompok-rekening.create');
    }
}
