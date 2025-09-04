<?php

namespace App\Http\Livewire\SmartCanteen\ProdukKantin;

use App\Models\ProdukKantin;
use Livewire\Component;

class Detail extends Component
{
    public $produk;

    protected $listeners = ['showDetailProduk'];

    public function showDetailProduk($produkId)
    {
        $this->produk = ProdukKantin::findOrFail($produkId);
    }

    public function render()
    {
        return view('livewire.smart-canteen.produk-kantin.detail');
    }
}
