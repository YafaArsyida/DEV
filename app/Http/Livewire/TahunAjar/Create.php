<?php

namespace App\Http\Livewire\TahunAjar;

use Livewire\Component;
use App\Models\TahunAjar as TahunAjarModel;

class Create extends Component
{
    public $nama_tahun_ajar, $tanggal_mulai, $tanggal_selesai, $urutan, $deskripsi;

    protected function rules()
    {
        return [
            'nama_tahun_ajar' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1|unique:ms_tahun_ajar,urutan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ];
    }

    protected $messages = [
        'nama_tahun_ajar.required' => 'Nama tahun ajar tidak boleh kosong',
        'nama_tahun_ajar.string' => 'Nama tahun ajar harus berupa teks',
        'nama_tahun_ajar.max' => 'Nama tahun ajar maksimal 255 karakter',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
        'urutan.unique' => 'Urutan ini sudah digunakan, pilih angka lain',
        'tanggal_mulai.required' => 'Tanggal mulai tidak boleh kosong',
        'tanggal_mulai.date' => 'Tanggal mulai harus berupa format tanggal yang valid',
        'tanggal_selesai.required' => 'Tanggal selesai tidak boleh kosong',
        'tanggal_selesai.date' => 'Tanggal selesai harus berupa format tanggal yang valid',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $validatedData = $this->validate();
        TahunAjarModel::create($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah tahun ajar!']);
        $this->resetInput();
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAddTahunAjar']);
        $this->emit('refreshTahunAjars');
    }

    public function resetInput()
    {
        $this->nama_tahun_ajar = '';
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->urutan = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.tahun-ajar.create');
    }
}
