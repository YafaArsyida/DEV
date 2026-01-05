<?php

namespace App\Http\Livewire\SmartCanteen\LaporanTransaksi;

use App\Models\SmartCanteen\TransaksiSmartCanteen;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Overview extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedPeriode = 'hari_ini'; // default

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshSaldoEduPay'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function refreshSaldoEduPay()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updatedSelectedPeriode()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Memperbarui data...'
        ]);
    }

    public function render()
    {
        $user = Auth::user();

        // base query
        $query = TransaksiSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang);

        // filter sesuai peran
        if ($user->peran === 'kantin') {
            $query->where('ms_pengguna_id', $user->ms_pengguna_id);
        }

        // build periode range dengan start/end yang jelas
        $start = null;
        $end = null;

        if ($this->selectedPeriode === 'hari_ini') {
            $start = Carbon::today()->startOfDay();
            $end   = Carbon::today()->endOfDay();
        } elseif ($this->selectedPeriode === 'kemarin') {
            $start = Carbon::yesterday()->startOfDay();
            $end   = Carbon::yesterday()->endOfDay();
        } elseif ($this->selectedPeriode === '1_bulan') {
            $start = Carbon::now()->subMonth()->startOfDay();
            $end   = Carbon::now()->endOfDay();
        } elseif ($this->selectedPeriode === '3_bulan') {
            $start = Carbon::now()->subMonths(3)->startOfDay();
            $end   = Carbon::now()->endOfDay();
        }

        if ($start && $end) {
            $query->whereBetween('tanggal_transaksi', [$start, $end]);
        }

        // Hitung total
        $totalSiswa   = (clone $query)->where('user_type', 'siswa')->sum('total_transaksi');
        $totalPegawai = (clone $query)->where('user_type', 'pegawai')->sum('total_transaksi');
        $totalSemua   = $totalPegawai + $totalSiswa;

        return view('livewire.smart-canteen.laporan-transaksi.overview', [
            'totalSemua'   => $totalSemua,
            'totalSiswa'   => $totalSiswa,
            'totalPegawai' => $totalPegawai,
        ]);
    }
}
