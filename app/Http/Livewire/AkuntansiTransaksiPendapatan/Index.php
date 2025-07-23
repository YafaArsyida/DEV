<?php

namespace App\Http\Livewire\AkuntansiTransaksiPendapatan;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $totalPendapatan;

    protected $listeners = [
        'parameterUpdated',
        'refreshTransaksiPendapatan'
    ];

    public function parameterUpdated($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function refreshTransaksiPendapatan()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function render()
    {
        $query = AkuntansiJurnalDetail::query()
            ->where('posisi', 'Kredit')
            ->whereHas('ms_akuntansi_rekening', function ($q) {
                $q->where('akuntansi_kelompok_rekening_id', 4);
            })
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang);

        $data = $query->get();
        $this->totalPendapatan = $query->sum('nominal');

        return view('livewire.akuntansi-transaksi-pendapatan.index', [
            'data' => $data,
        ]);
    }
}
