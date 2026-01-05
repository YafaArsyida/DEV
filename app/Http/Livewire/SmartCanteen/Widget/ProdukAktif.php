<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use Livewire\Component;
use Livewire\WithPagination;

class ProdukAktif extends Component
{
    use WithPagination;

    public $selectedJenjang;
    public $selectKategoriProduk = '';
    public $kategoriList = [];

    protected $listeners = [
        'parameterUpdated' => 'updateParameters'
    ];

    public function mount()
    {
        // default kosong
        $this->kategoriList = [];
    }

    public function updatingSelectKategoriProduk()
    {
        $this->resetPage();
    }

    public function updatedSelectKategoriProduk()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Memperbarui...'
        ]);
    }

    /**
     * Listener untuk update jenjang
     */
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;

        // filter kategori berdasar jenjang
        $this->kategoriList = KategoriProdukSmartCanteen::where('ms_jenjang_id', $jenjang)->get();

        $this->resetPage();
    }

    private function loadProduk()
    {
        return ProdukSmartCanteen::with('ms_kategori_produk_kantin')
            ->where('status', 1)
            ->when($this->selectedJenjang, function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang);
            })
            ->when($this->selectKategoriProduk, function ($q) {
                $q->where('ms_kategori_produk_kantin_id', $this->selectKategoriProduk);
            })
            ->orderBy('nama_produk_kantin')
            ->paginate(8);
    }

    public function render()
    {
        return view('livewire.smart-canteen.widget.produk-aktif', [
            'produkAktif' => $this->loadProduk(),
            'kategoriList' => $this->kategoriList,
        ]);
    }
}
