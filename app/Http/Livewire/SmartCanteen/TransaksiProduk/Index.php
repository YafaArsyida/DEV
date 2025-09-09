<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\Jenjang;
use App\Models\KategoriProdukKantin;
use App\Models\ProdukKantin;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedJenjang = null;
    public $namaJenjang = '';
    public $selectedKategori = null;

    public $ms_siswa_id;
    public $nama_siswa;
    public $educard;
    public $saldo_edupay;

    public $activeTab = 'semua'; // default tab

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    protected $listeners = [
        'refreshProduk' => '$refresh',
        'parameterUpdated' => 'updateParameters',
        'filterKategori' => 'setKategori',
        'scanSuccess'
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

    public function scanSuccess($data)
    {
        $this->ms_siswa_id = $data['ms_siswa_id'];
        $this->nama_siswa  = $data['nama_siswa'];
        $this->educard     = $data['educard'];
        $this->saldo_edupay = $data['saldo_edupay'];
    }
    
    public function render()
    {
        $query = ProdukKantin::query();

        if ($this->selectedJenjang) {
            $query->where('ms_jenjang_id', $this->selectedJenjang);
        }

        if ($this->search) {
            $query->where('nama_produk_kantin', 'like', '%' . $this->search . '%');
        }

        // ini ambil SEMUA produk sesuai jenjang + search
        $allProduk = $query->get();
        // dd($allProduk->toArray());

        // ambil kategori
        $kategori = KategoriProdukKantin::where('ms_jenjang_id', $this->selectedJenjang)->get();

        return view('livewire.smart-canteen.transaksi-produk.index', [
            'allProduk' => $allProduk,
            'kategori' => $kategori,
        ]);
    }
}
