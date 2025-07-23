<?php

namespace App\Http\Livewire\AkuntansiMutasiKas;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $search = '';

    public $totalKredit;
    public $totalDebit;
    public $totalSaldo;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh');
    }

    public function render()
    {
        $query = AkuntansiJurnalDetail::query()
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('kode_rekening', 101)
            ->orderBy('tanggal_transaksi', 'ASC');

        // Filter berdasarkan nama siswa jika ada
        if ($this->search) {
            $query->where('deskripsi', 'like', '%' . trim($this->search) . '%');
        }
        $laporan = $query->get();

        $this->totalKredit = (clone $query)->where('posisi', 'kredit')->sum('nominal');
        $this->totalDebit = (clone $query)->where('posisi', 'debit')->sum('nominal');
        $this->totalSaldo = $this->totalDebit - $this->totalKredit;

        return view('livewire.akuntansi-mutasi-kas.index', [
            'laporan' => $laporan
        ]);
    }
}
