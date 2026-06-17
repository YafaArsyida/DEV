<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KartuJumlahProduk extends Component
{
    public $selectedKantin = null;
    public $selectedTahunAjar = null;

    public $namaKantin = null;
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

    public function updateParameters($kantin, $tahunAjar)
    {
        $this->selectedKantin = $kantin;
        $this->selectedTahunAjar = $tahunAjar;

        $kantin = Kantin::find($kantin);
        $tahunAjar = TahunAjar::find($tahunAjar);

        $this->namaKantin = $kantin ? $kantin->nama_kantin : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';

        // Load kategori sesuai jenjang
        $this->loadKategori();

        // Reset kategori saat jenjang berubah
        $this->selectedKategori = null;
    }

    public function loadKategori()
    {
        if (!$this->selectedKantin) {
            $this->listKategori = [];
            return;
        }

        $this->listKategori = KategoriProdukSmartCanteen::where('ms_kantin_id', $this->selectedKantin)
            ->orderBy('nama_kategori_produk_kantin')
            ->get();
    }

    public function render()
    {
        $query = ProdukSmartCanteen::where('ms_kantin_id', $this->selectedKantin);

        $user = Auth::user();

        if ($this->selectedKategori) {
            $query->where('ms_kategori_produk_kantin_id', $this->selectedKategori);
        }

        $this->jumlahProduk = $query->count();
        
        return view('livewire.smart-canteen.widget.kartu-jumlah-produk');
    }
}
