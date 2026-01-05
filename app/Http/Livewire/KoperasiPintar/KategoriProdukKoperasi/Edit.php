<?php

namespace App\Http\Livewire\KoperasiPintar\KategoriProdukKoperasi;

use App\Models\KoperasiPintar\KategoriProdukKoperasi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $ms_kategori_produk_koperasi_id, $ms_jenjang_id, $nama_kategori_produk_koperasi, $deskripsi;

    protected $listeners = [
        'loadDataKategoriKoperasi',
    ];

    public function loadDataKategoriKoperasi($id)
    {
        $kategori = KategoriProdukKoperasi::findOrFail($id);
        $this->ms_kategori_produk_koperasi_id = $kategori->ms_kategori_produk_koperasi_id;
        $this->ms_jenjang_id = $kategori->ms_jenjang_id;
        $this->nama_kategori_produk_koperasi = $kategori->nama_kategori_produk_koperasi;
        $this->deskripsi = $kategori->deskripsi;
    }

    protected function rules()
    {
        return [
            'nama_kategori_produk_koperasi' => 'required|string|max:50',
            'ms_jenjang_id' => 'required',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_produk_koperasi.required' => 'Nama kategori tidak boleh kosong',
        'ms_jenjang_id.required' => 'Pilih jenjang',
    ];

    public function update()
    {
        $validatedData = $this->validate();
        DB::beginTransaction();

        try {

            $kategori = KategoriProdukKoperasi::findOrFail($this->ms_kategori_produk_koperasi_id);
            $kategori->update([
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'nama_kategori_produk_koperasi' => $this->nama_kategori_produk_koperasi,
                'deskripsi' => $this->deskripsi,
            ]);
            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah kategori!']);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalEditKategoriKoperasi']);
            $this->emit('refreshKategori');
            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.kategori-produk-koperasi.edit');
    }
}
