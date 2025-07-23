<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\KategoriTagihanSiswa;
use App\Models\TagihanSiswa;
use Livewire\WithPagination;
use Livewire\Component;

class Detail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $ms_penempatan_siswa_id;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKategori;

    public $search = '';

    // Listener untuk Livewire
    protected $listeners = [
        'showDetailTagihan'
    ];

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function updatingselectedKategori()
    {
        $this->resetPage(); // Reset paginasi saat filter kelas berubah
    }

    public function showDetailTagihan($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->ms_penempatan_siswa_id = $params['ms_penempatan_siswa_id'];
        $this->resetPage();
    }

    public function render()
    {
        $select_kategori = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kategori = KategoriTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Query Tagihan
        $query = TagihanSiswa::select('ms_tagihan_siswa.*', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->join('ms_jenis_tagihan_siswa', 'ms_jenis_tagihan_siswa.ms_jenis_tagihan_siswa_id', '=', 'ms_tagihan_siswa.ms_jenis_tagihan_siswa_id')
            ->join('ms_kategori_tagihan_siswa', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', '=', 'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->whereHas('ms_penempatan_siswa', function ($q) {
                $q->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id);
            });

        // Filter berdasarkan kategori tagihan jika dipilih
        if ($this->selectedKategori) {
            $query->where('ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', $this->selectedKategori);
        }

        // Filter berdasarkan pencarian nama jenis tagihan
        if ($this->search) {
            $query->where('ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa', 'like', '%' . $this->search . '%');
        }

        // Paginasi dan urutan berdasarkan kategori tagihan
        $tagihans = $query->orderBy('ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', 'ASC')->paginate(1000);

        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($item) => $item->jumlah_sudah_dibayar());
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

        return view('livewire.tagihan-siswa.detail', [
            'select_kategori' => $select_kategori,
            'tagihans' => $tagihans,
            'totalEstimasi' => $totalEstimasi,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
        ]);
    }
}
