<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Histori extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $startDate;
    public $endDate;

    // Parameter dari listener
    public $selectedKantin;
    public $selectedJenjang;
    public $selectedMetode;

    public $namaKantin;

    public $namaJenjang = '-';

    public $selectedPetugas = null;       // filter petugas kantin
    public $select_petugas = [];


    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'openHistori',
        'koreksiBerhasil'
    ];

    public function updateParameters($kantin)
    {
        $this->selectedKantin = $kantin;

        $this->namaKantin = Kantin::find($kantin)?->nama_kantin ?? '-';

        $this->resetPage();
    }
    
    public function openHistori()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function koreksiBerhasil(){
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }
    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function resetTanggal()
    {
        // $this->startDate = now()->format('Y-m-d');
        $this->startDate = now()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->resetPage();
    }

    public function getDataProperty()
    {
        $query = TransaksiSmartCanteen::with([
            'ms_siswa',
            'ms_pegawai',
            'dt_transaksi_kantin.ms_produk_kantin',
        ])
            ->where('ms_kantin_id', $this->selectedKantin)
            ->orderBy('tanggal_transaksi', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_transaksi', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ]);
        }
        // Filter metode transaksi
        if ($this->selectedMetode) {
            $query->where('metode_pembayaran', $this->selectedMetode);
        }

        return $query->paginate(20);
    }

    public function render()
    {
        return view('livewire.smart-canteen.transaksi-produk.histori',[
            'riwayat' => $this->getDataProperty()
        ]);
    }
}
