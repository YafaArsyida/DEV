<?php

namespace App\Http\Livewire\KoperasiPintar\ProdukKoperasi;

use App\Models\KoperasiPintar\ProdukKoperasi;
use Livewire\Component;

class Detail extends Component
{
    public $produk;

    protected $listeners = ['showDetailProdukKoperasi'];

    public function showDetailProdukKoperasi($produkId)
    {
        $this->produk = ProdukKoperasi::findOrFail($produkId);
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.produk-koperasi.detail');
    }
}
