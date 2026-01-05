<?php

namespace App\Http\Livewire\KoperasiPintar\KategoriProdukKoperasi;

use App\Models\Jenjang;
use App\Models\KoperasiPintar\KategoriProdukKoperasi;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedJenjang = null;
    public $namaJenjang = '';

    protected $listeners = [
        'refreshKategori' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];

    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;

        $j = Jenjang::find($jenjang);
        $this->namaJenjang = $j ? $j->nama_jenjang : 'Tidak Diketahui';
    }

    public function render()
    {
        $kategori = KategoriProdukKoperasi::query();

        // filter jenjang
        if ($this->selectedJenjang) {
            $kategori->where('ms_jenjang_id', $this->selectedJenjang);
        }

        // filter pencarian
        if ($this->search) {
            $kategori->where('nama_kategori_produk_koperasi', 'like', '%' . $this->search . '%');
        }
        return view('livewire.koperasi-pintar.kategori-produk-koperasi.index', [
            'kategori' => $kategori->get()
        ]);
    }
}
