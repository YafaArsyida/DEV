<?php

namespace App\Http\Livewire\AkuntansiLaporanLabaRugi;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $startDate = null;
    public $endDate = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahunAjar = TahunAjar::find($tahunAjar);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';
    }

    public function resetTanggal()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-laba-rugi.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        // PENDAPATAN: akun kode 4%
        $pendapatanPerBulan = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('posisi', 'kredit')
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
            })
            ->whereHas('akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '4%');
            })
            ->get()
            ->groupBy([
                fn($item) => $item->akuntansi_rekening->nama_rekening,
                fn($item) => \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m')
            ]);

        // BEBAN: akun kode 5%
        $bebanPerBulan = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('posisi', 'debit')
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
            })
            ->whereHas('akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '5%');
            })
            ->get()
            ->groupBy([
                fn($item) => $item->akuntansi_rekening->nama_rekening,
                fn($item) => \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m')
            ]);

        // Gabungkan bulan dari pendapatan + beban
        $bulanHeaders = collect($pendapatanPerBulan)->merge($bebanPerBulan)->flatMap(function ($item) {
            return collect($item)->keys()->all();
        })->unique()->sort()->values();

        // Format bulan ke Indonesia
        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {
            return [$bulan => HelperController::formatTanggalIndonesia($bulan . '-01', 'F Y')];
        });

        $tahunAjar = TahunAjar::find($this->selectedTahunAjar);

        return view('livewire.akuntansi-laporan-laba-rugi.index', [
            'pendapatanPerBulan' => $pendapatanPerBulan,
            'bebanPerBulan' => $bebanPerBulan,
            'bulanIndo' => $bulanIndo,

            'tahunAjar' => $tahunAjar,
        ]);
    }
}
