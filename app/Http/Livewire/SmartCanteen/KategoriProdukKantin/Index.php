<?php

namespace App\Http\Livewire\SmartCanteen\KategoriProdukKantin;

use App\Models\Jenjang;
use App\Models\KategoriProdukKantin;
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
        $kategori = KategoriProdukKantin::query();

        // filter jenjang
        if ($this->selectedJenjang) {
            $kategori->where('ms_jenjang_id', $this->selectedJenjang);
        }

        // filter pencarian
        if ($this->search) {
            $kategori->where('nama_kategori_produk_kantin', 'like', '%' . $this->search . '%');
        }

        return view('livewire.smart-canteen.kategori-produk-kantin.index', [
            'kategori' => $kategori->get()
        ]);
    }
}
