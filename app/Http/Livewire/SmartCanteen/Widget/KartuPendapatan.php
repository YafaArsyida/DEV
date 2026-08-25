<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KartuPendapatan extends Component
{
    public $selectedKantin = null;

    public $periode = 'today'; // today | yesterday | 1_month | 3_month
    public $labelPeriode = 'Hari Ini';

    public $startDate;
    public $endDate;

    public $totalTransaksi = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($kantin)
    {
        $this->selectedKantin = $kantin;

        $kantin = Kantin::find($kantin);
    }

    public function mount()
    {
        $this->setPeriode('today');
    }

    public function setPeriode($periode)
    {
        $this->periode = $periode;

        match ($periode) {
            'today' => $this->setRange(
                Carbon::today(),
                Carbon::today(),
                'Hari Ini'
            ),

            'yesterday' => $this->setRange(
                Carbon::yesterday(),
                Carbon::yesterday(),
                'Kemarin'
            ),

            '1_month' => $this->setRange(
                Carbon::now()->subMonth()->startOfDay(),
                Carbon::now()->endOfDay(),
                '1 Bulan Terakhir'
            ),

            '3_month' => $this->setRange(
                Carbon::now()->subMonths(3)->startOfDay(),
                Carbon::now()->endOfDay(),
                '3 Bulan Terakhir'
            ),

            default => null
        };
        // 🔔 Notifikasi update
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Memperbarui...'
        ]);
    }

    protected function setRange($start, $end, $label)
    {
        $this->startDate = $start;
        $this->endDate   = $end;
        $this->labelPeriode = $label;
    }

    public function hitungTotal()
    {
        $user = Auth::user();

        $query = TransaksiSmartCanteen::where('ms_kantin_id', $this->selectedKantin);

        // Filter tanggal
        $query->whereBetween('tanggal_transaksi', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ]);

        $this->totalTransaksi = $query->sum('total_transaksi');
    }

    public function render()
    {
        $this->hitungTotal();

        return view('livewire.smart-canteen.widget.kartu-pendapatan');
    }
}
