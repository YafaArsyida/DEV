<?php

namespace App\Http\Livewire\AkuntansiLaporanJurnalUmum;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TahunAjar;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedBulan = null;
    public $startDate = null;
    public $endDate = null;

    public $search = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function updateBulan($bulan)
    {
        $this->selectedBulan = $bulan;
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-jurnal-umum.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'search' => $this->search
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $transaksiJurnal = AkuntansiJurnalDetail::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            // ->when($this->selectedBulan, function ($query) {
            //     $query->whereMonth('tanggal_transaksi', $this->selectedBulan);
            // })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
            })
            ->when($this->search, function ($query) {
                $query->where('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('tanggal_transaksi')
            ->get()
            ->groupBy(['deskripsi', 'nominal']);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);

        return view('livewire.akuntansi-laporan-jurnal-umum.index', [
            // 'select_bulan' => $select_bulan,
            'transaksiJurnal' => $transaksiJurnal,
        ]);
    }
}
