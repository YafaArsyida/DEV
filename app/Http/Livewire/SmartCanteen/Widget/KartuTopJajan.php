<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\SmartCanteen\TransaksiSmartCanteen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KartuTopJajan extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedJenis = '';   // siswa / pegawai / semua
    public $search = '';
    public $selectedPeriode = 'bulan_ini'; // default bulan ini

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function render()
    {
        $user = Auth::user();

        $query = TransaksiSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang);

        // filter sesuai peran
        if ($user->peran === 'kantin') {
            $query->where('ms_pengguna_id', $user->ms_pengguna_id);
        }

        // Filter periode
        $startDate = null;
        $endDate = Carbon::now()->endOfDay();

        if ($this->selectedPeriode === 'bulan_ini') {
            $startDate = Carbon::now()->startOfMonth();
        } elseif ($this->selectedPeriode === '3_bulan') {
            $startDate = Carbon::now()->subMonths(3)->startOfDay();
        } elseif ($this->selectedPeriode === '6_bulan') {
            $startDate = Carbon::now()->subMonths(6)->startOfDay();
        }

        if ($startDate) {
            $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        }

        // Filter jenis pembeli
        if ($this->selectedJenis === 'siswa') {
            $query->where('user_type', 'siswa');
        } elseif ($this->selectedJenis === 'pegawai') {
            $query->where('user_type', 'pegawai');
        }

        // Search nama
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('ms_siswa', function ($qs) {
                    $qs->where('nama_siswa', 'like', '%' . $this->search . '%');
                })->orWhereHas('ms_pegawai', function ($qp) {
                    $qp->where('nama_pegawai', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Hitung top jajan (group by user_id + user_type)
        $siswas = $query->selectRaw('user_id, user_type, SUM(total_transaksi) as total_jajan')
            ->groupBy('user_id', 'user_type')
            ->orderByDesc('total_jajan')
            ->take(10)
            ->get();

        return view('livewire.smart-canteen.widget.kartu-top-jajan',[
            'siswas' => $siswas,
        ]);
    }
}
