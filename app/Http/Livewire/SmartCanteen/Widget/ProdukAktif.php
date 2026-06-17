<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use Livewire\Component;
use Livewire\WithPagination;

class ProdukAktif extends Component
{
    use WithPagination;

    public $selectedKantin;
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
     * Listener untuk update kantin
     */
    public function updateParameters($kantin, $tahunAjar)
    {
        $this->selectedKantin = $kantin;

        // filter kategori berdasar kantin
        $this->kategoriList = KategoriProdukSmartCanteen::where('ms_kantin_id', $kantin)->get();

        $this->resetPage();
    }

    private function loadProduk()
    {
        return ProdukSmartCanteen::with('ms_kategori_produk_kantin')
            ->where('status', 1)
            ->when($this->selectedKantin, function ($q) {
                $q->where('ms_kantin_id', $this->selectedKantin);
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
