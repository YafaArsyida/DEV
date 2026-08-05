<?php

namespace App\Http\Livewire\AkuntansiLaporanJurnalUmum;

use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $perPage = 50;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
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
            'message' => 'Periode diperbarui'
        ]);
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode diperbarui'
        ]);
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function resetTanggal()
    {
        // $this->startDate = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->resetPage(); // Reset paginasi saat pencarian berubah
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
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
        $query = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
            'ms_pengguna',
        ])
        ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
        ->where('ms_jenjang_id', $this->selectedJenjang)
        ->where('ms_departemen_id', 'SEKOLAH')
        // ->where('status', 'active')
        ->when(
            $this->startDate && $this->endDate,
            function ($query) {
                $startDate = Carbon::parse($this->startDate)
                    ->startOfDay();

                $endDate = Carbon::parse($this->endDate)
                    ->endOfDay();

                $query->whereBetween('tanggal_transaksi', [
                    $startDate,
                    $endDate
                ]);
            }
        )
        ->when(
            $this->search,
            function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {

                    // Cari berdasarkan deskripsi header
                    $query->where(
                        'deskripsi',
                        'like',
                        "%{$search}%"
                    )

                    // Atau cari berdasarkan nomor jurnal
                    ->orWhere(
                        'nomor_jurnal',
                        'like',
                        "%{$search}%"
                    )

                    // Atau cari berdasarkan kode rekening
                    ->orWhereHas(
                        'akuntansi_jurnal_detail',
                        function ($query) use ($search) {
                            $query->where(
                                'kode_rekening',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                });
            }
        )
        ->orderBy('tanggal_transaksi', 'asc')
        ->orderBy('akuntansi_jurnal_id', 'asc');

        $transaksiJurnal = $query->paginate($this->perPage);

        return view('livewire.akuntansi-laporan-jurnal-umum.index', [
            // 'select_bulan' => $select_bulan,
            'transaksiJurnal' => $transaksiJurnal,
        ]);
    }
}
