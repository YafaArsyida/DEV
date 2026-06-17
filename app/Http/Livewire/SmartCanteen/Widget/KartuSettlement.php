<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KartuSettlement extends Component
{
    public $selectedKantin = null;
    public $selectedTahunAjar = null;

    public $namaKantin = '';
    public $namaTahunAjar = '';

    public $jenisSaldo = 'estimasi'; // estimasi | belum | sudah
    public $totalSaldo = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];
    public function updateParameters($kantin, $tahunAjar)
    {
        $this->selectedKantin = $kantin;
        $this->selectedTahunAjar = $tahunAjar;

        $kantin = Kantin::find($kantin);
        $tahunAjar = TahunAjar::find($tahunAjar);

        $this->namaKantin = $kantin ? $kantin->nama_kantin : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';
    }

    public function setJenisSaldo($jenis)
    {
        $this->jenisSaldo = $jenis;
    }

    public function hitungSaldo()
    {
        if (!$this->selectedKantin) {
            $this->totalSaldo = 0;
            return;
        }

        $query = TransaksiSmartCanteen::where('ms_kantin_id', $this->selectedKantin);

        // Filter settlement
        if ($this->jenisSaldo === 'belum') {
            $query->where('status_settlement', 'belum');
        }

        if ($this->jenisSaldo === 'sudah') {
            $query->where('status_settlement', 'sudah');
        }

        $this->totalSaldo = $query->sum('total_transaksi');
    }

    public function render()
    {
        $this->hitungSaldo();
        return view('livewire.smart-canteen.widget.kartu-settlement');
    }
}
