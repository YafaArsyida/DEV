<?php

namespace App\Http\Livewire\SmartCanteen\ProdukKantin;

use App\Models\Jenjang;
use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
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
        $query = ProdukSmartCanteen::query();

        if ($this->selectedJenjang) {
            $query->where('ms_jenjang_id', $this->selectedJenjang);
        }

        if ($this->search) {
            $query->where('nama_produk_kantin', 'like', '%' . $this->search . '%');
        }
        // 🔐 FILTER BERDASARKAN PERAN LOGIN
        if (auth()->check()) {
            $peran = auth()->user()->peran;

            if ($peran === 'kantin') {
                $query->where('ms_pengguna_id', auth()->id());
            }
            // superadmin → tidak difilter (lihat semua)
        }

        // ini ambil SEMUA produk sesuai jenjang + search
        $allProduk = $query->get();
        // dd($allProduk->toArray());

        // ambil kategori
        $kategori = KategoriProdukSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang)->get();

        return view('livewire.smart-canteen.produk-kantin.index', [
            'allProduk' => $allProduk,
            'kategori' => $kategori,
        ]);
    }
}
