<?php

namespace App\Http\Livewire\SmartCanteen\ProdukKantin;

use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedKantin = null;
    public $namaKantin = '';
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

    public function updateParameters($kantin)
    {
        $this->selectedKantin = $kantin;

        $j = Kantin::find($kantin);
        $this->namaKantin = $j ? $j->nama_kantin : 'Tidak Diketahui';
    }

    public function render()
    {
        // default kosong
        $allProduk = collect();
        $kategori = collect();

        // hanya query jika kantin dipilih
        if ($this->selectedKantin) {
            $query = ProdukSmartCanteen::where(
                'ms_kantin_id',
                $this->selectedKantin
            );

            if ($this->search) {
                $query->where(
                    'nama_produk_kantin',
                    'like',
                    '%' . $this->search . '%'
                );
            }

            $allProduk = $query->get();
            $kategori = KategoriProdukSmartCanteen::where('ms_kantin_id', $this->selectedKantin)->get();
        }

        return view('livewire.smart-canteen.produk-kantin.index', [
            'allProduk' => $allProduk,
            'kategori' => $kategori,
        ]);
    }
}
