<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\Jenjang;
use App\Models\KategoriProdukSmartCanteen;
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
    public $ms_penempatan_siswa_id;
    public $nama;
    public $nama_kelas;
    public $educard;
    public $saldo_edupay;

    public $nama_jabatan;

    public $activeTab = 'semua'; // default tab

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    protected $listeners = [
        'refreshProduk' => '$refresh',
        'parameterUpdated' => 'updateParameters',
        'filterKategori' => 'setKategori',
        'scanSuccess',
        'openScanModal'
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

    public function openScanModal()
    {
        $this->reset([
            'user_type',
            'user_id',
            'ms_penempatan_siswa_id',
            'nama',
            'nama_kelas',
            'educard',
            'saldo_edupay',
            
            'nama_jabatan',
        ]);
    }

    public function scanSuccess($data)
    {
        $this->user_type                = $data['user_type'];   // 'siswa' atau 'pegawai'
        $this->user_id                  = $data['user_id'];     // ms_siswa_id atau ms_pegawai_id
        $this->ms_penempatan_siswa_id   = $data['ms_penempatan_siswa_id'];     // ms_siswa_id atau ms_pegawai_id
        $this->nama                     = $data['nama'];        // nama siswa/pegawai
        $this->nama_kelas               = $data['nama_kelas'];        // nama siswa/pegawai
        $this->educard                  = $data['educard'];
        $this->saldo_edupay             = $data['saldo_edupay'];
        
        $this->nama_jabatan               = $data['nama_jabatan'];        // nama siswa/pegawai
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
