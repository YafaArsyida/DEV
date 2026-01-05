<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\Jenjang;
use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KartuJumlahProduk extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = null;
    public $namaTahunAjar = null;

    public $selectedKategori = null;
    public $listKategori = [];

    public $jumlahProduk = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatedSelectedKategori()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Filter kategori diperbarui'
        ]);
    }

    public function setKategori($kategoriId = null)
    {
        $this->selectedKategori = $kategoriId;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Filter kategori diperbarui'
        ]);
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahunAjar = TahunAjar::find($tahunAjar);

        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';

        // Load kategori sesuai jenjang
        $this->loadKategori();

        // Reset kategori saat jenjang berubah
        $this->selectedKategori = null;
    }

    public function loadKategori()
    {
        if (!$this->selectedJenjang) {
            $this->listKategori = [];
            return;
        }

        $this->listKategori = KategoriProdukSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang)
            ->orderBy('nama_kategori_produk_kantin')
            ->get();
    }

    public function render()
    {
        $query = ProdukSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang);

        $user = Auth::user();

        // Jika petugas kantin → hanya data miliknya
        if ($user->peran === 'kantin') {
            $query->where('ms_pengguna_id', $user->ms_pengguna_id);
        }

        if ($this->selectedKategori) {
            $query->where('ms_kategori_produk_kantin_id', $this->selectedKategori);
        }

        $this->jumlahProduk = $query->count();
        
        return view('livewire.smart-canteen.widget.kartu-jumlah-produk');
    }
}
