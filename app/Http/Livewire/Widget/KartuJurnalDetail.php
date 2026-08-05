<?php

namespace App\Http\Livewire\Widget;

use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class KartuJurnalDetail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $perPage = 50;

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
        $query = AkuntansiJurnal::with([
                'akuntansi_jurnal_detail.akuntansi_rekening',
                'ms_pengguna',
            ])
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')
            ->where('status', 'active')
            ->whereDate('tanggal_transaksi', Carbon::today())

            ->orderBy('tanggal_transaksi', 'asc')
            ->orderBy('akuntansi_jurnal_id', 'asc');

            $transaksiJurnal = $query->paginate($this->perPage);

        return view('livewire.widget.kartu-jurnal-detail', [
            'transaksiJurnal' => $transaksiJurnal,
        ]);
    }
}
