<?php

namespace App\Http\Livewire\SmartCanteen\KategoriProdukKantin;

use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedKantin = null;
    public $selectedTahunAjar = null;
    public $namaKantin = '';

    protected $listeners = [
        'refreshKategori' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];

    public function updateParameters($kantin, $tahunAjar)
    {
        $this->selectedKantin = $kantin;

        $j = Kantin::find($kantin);
        $this->namaKantin = $j ? $j->nama_kantin : 'Tidak Diketahui';
    }

    public function render()
    {
        $kategori = collect();

        if($this->selectedKantin){
            $query = KategoriProdukSmartCanteen::where(
                'ms_kantin_id',
                $this->selectedKantin
            );

            // filter pencarian
            if ($this->search) {
                $query->where(
                    'nama_kategori_produk_kantin',
                    'like',
                    '%' . $this->search . '%');
            }
            $kategori = $query->get();
        }
        
        return view('livewire.smart-canteen.kategori-produk-kantin.index', [
            'kategori' => $kategori
        ]);
    }
}
