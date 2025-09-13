<?php

namespace App\Http\Livewire\SmartCanteen\ProdukKantin;

use App\Models\ProdukKantin;
use App\Models\ProdukSmartCanteen;
use Livewire\Component;

class Delete extends Component
{
    public $ms_produk_kantin_id; // id produk yang mau dihapus

    protected $listeners = ['confirmDeleteProduk'];

    public function confirmDeleteProduk($ms_produk_kantin_id)
    {
        $this->ms_produk_kantin_id = $ms_produk_kantin_id;
    }

    public function deleteProduk()
    {
        try {
            ProdukSmartCanteen::findOrFail($this->ms_produk_kantin_id)->delete();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Produk berhasil dihapus!']);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalDeleteProduk']);
            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.smart-canteen.produk-kantin.delete');
    }
}
