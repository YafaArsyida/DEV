<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\Jenjang;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TahunAjar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KartuVolumeTransaksi extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    // Periode
    public $periode = 'today'; // today | yesterday | 1_month | 2_month
    public $labelPeriode = 'Hari Ini';
    public $startDate;
    public $endDate;

    // Petugas
    public $selectedPetugas = null;
    public $select_petugas = [];

    // Output
    public $jumlahTransaksi = 0;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->loadPetugasByJenjang();
    }

    protected function loadPetugasByJenjang()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->select_petugas = collect();
            return;
        }

        $this->select_petugas = User::whereHas('ms_akses_jenjang', function ($q) {
            $q->where('ms_jenjang_id', $this->selectedJenjang);
        })
            ->where('peran', 'kantin')
            ->orderBy('nama')
            ->get();
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

            '2_month' => $this->setRange(
                Carbon::now()->subMonths(2)->startOfDay(),
                Carbon::now()->endOfDay(),
                '2 Bulan Terakhir'
            ),
        };

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

    public function hitungJumlahTransaksi()
    {
        $query = TransaksiSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang);

        // Filter petugas
        if ($this->selectedPetugas) {
            $query->where('ms_pengguna_id', $this->selectedPetugas);
        }

        // Filter tanggal
        $query->whereBetween('tanggal_transaksi', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ]);

        $this->jumlahTransaksi = $query->count();
    }

    public function updatedSelectedPetugas()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Filter petugas diperbarui'
        ]);
    }

    public function render()
    {
        $this->hitungJumlahTransaksi();
        return view('livewire.smart-canteen.widget.kartu-volume-transaksi');
    }
}
