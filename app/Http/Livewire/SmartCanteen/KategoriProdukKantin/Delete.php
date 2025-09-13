<?php

namespace App\Http\Livewire\SmartCanteen\KategoriProdukKantin;

use App\Models\KategoriProdukKantin;
use App\Models\KategoriProdukSmartCanteen;
use App\Models\ProdukKantin;
use App\Models\ProdukSmartCanteen;
use Livewire\Component;

class Delete extends Component
{
    public $ms_kategori_produk_kantin_id;

    protected $listeners = ['confirmDeleteKategori' => 'setKategoriId'];

    public function setKategoriId($id)
    {
        $this->ms_kategori_produk_kantin_id = $id;
    }

    public function deleteKategori()
    {
        if ($this->ms_kategori_produk_kantin_id) {
            $kategori = KategoriProdukSmartCanteen::find($this->ms_kategori_produk_kantin_id);

            if ($kategori) {
                // cek apakah kategori dipakai di produk kantin
                $isUsed = ProdukSmartCanteen::where('ms_kategori_produk_kantin_id', $this->ms_kategori_produk_kantin_id)->exists();

                if ($isUsed) {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => 'Kategori tidak dapat dihapus karena sudah digunakan di Produk Kantin.'
                    ]);
                } else {
                    $namaKategori = $kategori->nama_kategori_produk_kantin;

                    $kategori->delete();

                    $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalDeleteKategori']);
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
        return view('livewire.smart-canteen.kategori-produk-kantin.delete');
    }
}
