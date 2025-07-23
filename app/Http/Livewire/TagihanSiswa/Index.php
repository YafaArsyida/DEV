<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    public $totalTagihan = 0;
    public $totalDibayarkan = 0;
    public $totalKekurangan = 0;
    public $jumlahTagihan = 0;
    public $totalPersen = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'refreshTagihans' => '$refresh',
        'parameterUpdated' => 'updateParameters',
        'tagihanUpdated'
    ];

    public function tagihanUpdated()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function updatingSelectedKelas()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->emitSelf('$refresh'); //lebih ringan

        // $this->resetPage(); // Reset paginasi saat parameter berubah
    }

    public function cetakLaporanTagihan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'laporan diproses.']);

        $url = route('keuangan.tagihan-siswa.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'kelas' => $this->selectedKelas,
            'search' => $this->search,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Data siswa (hanya jika Jenjang dan Tahun Ajar dipilih)
        $tagihans = [];

        // Reset nilai agar aman saat filter berubah
        $this->totalTagihan = 0;
        $this->totalDibayarkan = 0;
        $this->jumlahTagihan = 0;
        $this->totalKekurangan = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = PenempatanSiswa::with(['ms_siswa', 'ms_kelas'])
                ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar);

            if ($this->selectedKelas) {
                $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
            }

            if ($this->search) {
                $query->whereHas('ms_siswa', function ($q) {
                    $q->where('nama_siswa', 'like', '%' . $this->search . '%');
                });
            }

            $tagihans = $query->get();

            foreach ($tagihans as $item) {
                $tagihan = $item->total_tagihan_siswa();
                $dibayar = $item->total_dibayarkan();
                $jumlah = $item->jumlah_jenis_tagihan_siswa();

                $this->totalTagihan += $tagihan;
                $this->totalDibayarkan += $dibayar;
                $this->jumlahTagihan += $jumlah;
            }

            $this->totalKekurangan = $this->totalTagihan - $this->totalDibayarkan;
            // Hindari pembagian 0
            if ($this->totalTagihan > 0) {
                $this->totalPersen = round(($this->totalDibayarkan / $this->totalTagihan) * 100, 2);
            } else {
                $this->totalPersen = 0;
            }
        }

        return view('livewire.tagihan-siswa.index', [
            'select_kelas' => $select_kelas,
            'tagihans' => $tagihans,
        ]);
    }
}
