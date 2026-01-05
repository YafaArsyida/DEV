<?php

namespace App\Http\Livewire\KoperasiPintar\ProdukKoperasi;

use App\Models\KoperasiPintar\ProdukKoperasi;
use Livewire\Component;

class Delete extends Component
{
    public $ms_produk_koperasi_id; // id produk yang mau dihapus

    protected $listeners = ['confirmDeleteProdukKoperasi'];

    public function confirmDeleteProdukKoperasi($ms_produk_koperasi_id)
    {
        $this->ms_produk_koperasi_id = $ms_produk_koperasi_id;
    }

    public function deleteProduk()
    {
        try {
            ProdukKoperasi::findOrFail($this->ms_produk_koperasi_id)->delete();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Produk berhasil dihapus!']);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalDeleteProdukKoperasi']);
            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.produk-koperasi.delete');
    }
}
