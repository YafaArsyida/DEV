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

    public $namaKantin = '';

    public $jenisSaldo = 'estimasi'; // estimasi | belum | sudah
    public $totalSaldo = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];
    public function updateParameters($kantin)
    {
        $this->selectedKantin = $kantin;

        $kantin = Kantin::find($kantin);

        $this->namaKantin = $kantin ? $kantin->nama_kantin : 'Tidak Diketahui';
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

        $query = TransaksiSmartCanteen::where('ms_kantin_id', $this->selectedKantin)
            ->where('status_transaksi', '!=', 'dibatalkan');

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
