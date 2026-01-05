<?php

namespace App\Http\Livewire\KoperasiPintar\ProdukKoperasi;

use App\Models\Jenjang;
use App\Models\KoperasiPintar\KategoriProdukKoperasi;
use App\Models\KoperasiPintar\ProdukKoperasi;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedJenjang = null;
    public $namaJenjang = '';
    public $selectedKategori = null;

    public $activeTab = 'semua'; // default tab

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    protected $listeners = [
        'refreshProduk' => '$refresh',
        'parameterUpdated' => 'updateParameters',
        'filterKategori' => 'setKategori',
    ];

    public function setKategori($kategoriId)
    {
        $this->selectedKategori = $kategoriId;
    }

    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;

        $j = Jenjang::find($jenjang);
        $this->namaJenjang = $j ? $j->nama_jenjang : 'Tidak Diketahui';
    }

    public function render()
    {
        $query = ProdukKoperasi::query();

        if ($this->selectedJenjang) {
            $query->where('ms_jenjang_id', $this->selectedJenjang);
        }

        if ($this->search) {
            $query->where('nama_produk_koperasi', 'like', '%' . $this->search . '%');
        }

        // ini ambil SEMUA produk sesuai jenjang + search
        $allProduk = $query->get();
        // dd($allProduk->toArray());

        // ambil kategori
        $kategori = KategoriProdukKoperasi::where('ms_jenjang_id', $this->selectedJenjang)->get();

        return view('livewire.koperasi-pintar.produk-koperasi.index', [
            'allProduk' => $allProduk,
            'kategori' => $kategori,
        ]);
    }
}
