<?php

namespace App\Http\Livewire\AkuntansiLaporanBukuBesar;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use App\Models\TahunAjar;
use Carbon\Carbon;
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

    public function mount()
    {
        // Default ke hari ini
        // $this->startDate = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function updatedStartDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateBulan($bulan)
    {
        $this->selectedBulan = $bulan;
    }

    public function render()
    {
        // Ambil data jenis akun rekening dan detail jurnal
        $jenisAkunRekening = AkuntansiRekening::orderBy('kode_rekening')
            ->get();

        $transaksiJurnal = AkuntansiJurnalDetail::where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->when($this->startDate && $this->endDate, function ($query) {
                $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
                $endDate   = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

                $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })
            ->when($this->search, function ($query) {
                $query->where('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('tanggal_transaksi')
            ->get()
            ->groupBy('kode_rekening');

        return view('livewire.akuntansi-laporan-buku-besar.index', [
            // 'select_bulan' => $select_bulan,
            'jenisAkunRekening' => $jenisAkunRekening,
            'transaksiJurnal' => $transaksiJurnal,
        ]);
    }
}
