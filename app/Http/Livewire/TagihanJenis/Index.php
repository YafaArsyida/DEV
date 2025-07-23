<?php

namespace App\Http\Livewire\TagihanJenis;

use Livewire\WithPagination;
use Livewire\Component;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = ''; // Pencarian
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKategoriTagihan = null; // Filter kategori tagihan

    public $totalSiswa = 0;
    public $totalEstimasi = 0;
    public $totalDibayarkan = 0;
    public $totalKekurangan = 0;
    public $totalPersen = 0;

    protected $listeners = [
        'refreshTagihans' => '$refresh',
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingselectedKategoriTagihan()
    {
        $this->resetPage();
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->resetPage(); // Reset paginasi saat parameter berubah
    }
    public function cetakLaporanJenisTagihan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan sedang diproses...']);

        $url = route('keuangan.jenis-tagihan-siswa.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'kategori' => $this->selectedKategoriTagihan,
            'search' => $this->search,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $select_kategori = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kategori = KategoriTagihanSiswa::with(['ms_jenjang', 'ms_tahun_ajar'])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $jenis_tagihans = collect([]); // Koleksi kosong sebagai default

        // Reset nilai agar aman saat filter berubah
        $this->totalSiswa = 0;
        $this->totalEstimasi = 0;
        $this->totalDibayarkan = 0;
        $this->totalKekurangan = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = JenisTagihanSiswa::with('ms_tagihan_siswa')
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            if ($this->selectedKategoriTagihan) {
                $query->where('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
            }

            if ($this->search) {
                $query->where('nama_jenis_tagihan_siswa', 'like', '%' . $this->search . '%');
            }

            $jenis_tagihans = $query
                ->orderBy('ms_kategori_tagihan_siswa_id')
                // ->orderBy('nama_jenis_tagihan_siswa')
                ->get(); // pakai get agar perhitungan total tidak terpotong
        }
        // Perhitungan total
        $this->totalSiswa = $jenis_tagihans->sum(fn($item) => $item->jumlah_tagihan_siswa());
        $this->totalEstimasi = $jenis_tagihans->sum(fn($item) => $item->total_tagihan_siswa());
        $this->totalDibayarkan = $jenis_tagihans->sum(fn($item) => $item->total_tagihan_siswa_dibayarkan());
        $this->totalKekurangan = $this->totalEstimasi - $this->totalDibayarkan;

        $this->totalPersen = $this->totalEstimasi > 0
            ? round(($this->totalDibayarkan / $this->totalEstimasi) * 100, 2)
            : 0;

        return view('livewire.tagihan-jenis.index', [
            'select_kategori' => $select_kategori,
            'tagihans' => $jenis_tagihans,
        ]);
    }
}
