<?php

namespace App\Http\Livewire\KoperasiPintar\KategoriProdukKoperasi;

use App\Models\KoperasiPintar\KategoriProdukKoperasi;
use App\Models\KoperasiPintar\ProdukKoperasi;
use Livewire\Component;

class Delete extends Component
{
    public $ms_kategori_produk_koperasi_id;

    protected $listeners = ['confirmDeleteKategoriKoperasi' => 'setKategoriId'];

    public function setKategoriId($id)
    {
        $this->ms_kategori_produk_koperasi_id = $id;
    }

    public function deleteKategori()
    {
        if ($this->ms_kategori_produk_koperasi_id) {
            $kategori = KategoriProdukKoperasi::find($this->ms_kategori_produk_koperasi_id);

            if ($kategori) {
                // cek apakah kategori dipakai di produk kantin
                $isUsed = ProdukKoperasi::where('ms_kategori_produk_koperasi_id', $this->ms_kategori_produk_koperasi_id)->exists();

                if ($isUsed) {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => 'Kategori tidak dapat dihapus karena sudah digunakan di Produk.'
                    ]);
                } else {
                    $namaKategori = $kategori->nama_kategori_produk_koperasi;

                    $kategori->delete();

                    $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalDeleteKategoriKoperasi']);
                    $this->emit('refreshKategori');
                    $this->emit('refreshProduk');
                    $this->dispatchBrowserEvent('alertify-success', [
                        'message' => "Kategori <b>{$namaKategori}</b> berhasil dihapus."
                    ]);
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Kategori tidak ditemukan.'
                ]);
            }
        }
    }
    public function render()
    {
        return view('livewire.koperasi-pintar.kategori-produk-koperasi.delete');
    }
}
