<?php

namespace App\Http\Livewire\KategoriTagihanSiswa;

use App\Models\KategoriTagihanSiswa;
use Livewire\Component;

class Edit extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $ms_tahun_ajar_id, $ms_jenjang_id, $ms_kategori_tagihan_siswa_id, $nama_kategori_tagihan_siswa, $urutan, $deskripsi;

    protected $listeners = [
        'loadDataKategoriTagihan',
    ];

    public function loadDataKategoriTagihan($ms_kategori_tagihan_siswa_id)
    {
        $kategori = KategoriTagihanSiswa::findOrFail($ms_kategori_tagihan_siswa_id);

        $this->ms_kategori_tagihan_siswa_id = $kategori->ms_kategori_tagihan_siswa_id;
        $this->nama_kategori_tagihan_siswa = $kategori->nama_kategori_tagihan_siswa;
        $this->urutan = $kategori->urutan;
        $this->deskripsi = $kategori->deskripsi;
    }

    protected function rules()
    {
        return [
            'nama_kategori_tagihan_siswa' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_tagihan_siswa.required' => 'Nama kelas tidak boleh kosong',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateKategori()
    {
        $validatedData = $this->validate();

        $kategori = KategoriTagihanSiswa::where('ms_kategori_tagihan_siswa_id', $this->ms_kategori_tagihan_siswa_id)->firstOrFail();
        $kategori->update($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah kategori!']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditKategoriTagihan']);
        $this->emit('refreshKategoriTagihans');
        $this->emit('refreshJenisTagihans');
    }
    public function render()
    {
        return view('livewire.kategori-tagihan-siswa.edit');
    }
}
