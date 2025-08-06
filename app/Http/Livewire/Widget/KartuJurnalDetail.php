<?php

namespace App\Http\Livewire\Widget;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class KartuJurnalDetail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshJurnalHariIni'
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function refreshJurnalHariIni()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function render()
    {
        $transaksiJurnal = AkuntansiJurnalDetail::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->whereDate('tanggal_transaksi', Carbon::today())
            ->orderBy('tanggal_transaksi')
            ->get()
            ->groupBy(['deskripsi', 'nominal']);

        return view('livewire.widget.kartu-jurnal-detail', [
            'transaksiJurnal' => $transaksiJurnal,
        ]);
    }
}
