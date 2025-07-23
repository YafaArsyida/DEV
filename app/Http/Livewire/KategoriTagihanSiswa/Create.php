<?php

namespace App\Http\Livewire\KategoriTagihanSiswa;

use App\Models\KategoriTagihanSiswa;
use Livewire\Component;

class Create extends Component
{
    public $ms_tahun_ajar_id, $ms_jenjang_id, $nama_kategori_tagihan_siswa, $urutan, $deskripsi;

    protected $listeners = [
        'showCreateKategori',
    ];

    public function showCreateKategori($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
        $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_kategori_tagihan_siswa' => 'required|string|max:255',
            'ms_jenjang_id' => 'required',
            'ms_tahun_ajar_id' => 'required',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_tagihan_siswa.required' => 'Nama kategori tidak boleh kosong',
        'ms_jenjang_id.required' => 'Pilih jenjang',
        'ms_tahun_ajar_id.required' => 'Pilih tahun ajar',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        try {
            $validatedData = $this->validate();
            KategoriTagihanSiswa::create($validatedData);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah kategori!']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        $this->resetInput();
        $this->emit('refreshKategoriTagihans');
        $this->emit('refreshJenisTagihans');
    }

    public function resetInput()
    {
        $this->nama_kategori_tagihan_siswa = '';
        $this->urutan = '';
        $this->deskripsi = '';
    }
    public function render()
    {
        return view('livewire.kategori-tagihan-siswa.create');
    }
}
