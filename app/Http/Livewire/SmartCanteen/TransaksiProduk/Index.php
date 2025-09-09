<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\Jenjang;
use App\Models\KategoriProdukKantin;
use App\Models\KategoriProdukSmartCanteen;
use App\Models\ProdukKantin;
use App\Models\ProdukSmartCanteen;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedJenjang = null;
    public $namaJenjang = '';
    public $selectedKategori = null;

    public $user_type;
    public $user_id;
    public $nama;
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
        $this->user_type    = $data['user_type'];   // 'siswa' atau 'pegawai'
        $this->user_id      = $data['user_id'];     // ms_siswa_id atau ms_pegawai_id
        $this->nama         = $data['nama'];        // nama siswa/pegawai
        $this->educard      = $data['educard'];
        $this->saldo_edupay = $data['saldo_edupay'];
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

        // ini ambil SEMUA produk sesuai jenjang + search
        $allProduk = $query->get();

        // ambil kategori
        $kategori = KategoriProdukSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang)->get();

        return view('livewire.smart-canteen.transaksi-produk.index', [
            'allProduk' => $allProduk,
            'kategori' => $kategori,
        ]);
    }
}
