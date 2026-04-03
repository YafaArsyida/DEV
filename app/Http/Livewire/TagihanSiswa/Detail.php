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
            $select_kategori = KategoriTagihanSiswa::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Query Tagihan
        $query = $query = TagihanSiswa::query()
            ->with(['ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa'])
            ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar');

        if ($this->selectedKategori) {
            $query->whereHas('ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa', function ($q) {
                $q->where('ms_kategori_tagihan_siswa_id', $this->selectedKategori);
            });
        }

        if ($this->search) {
            $query->whereHas('ms_jenis_tagihan_siswa', function ($q) {
                $q->where('nama_jenis_tagihan_siswa', 'like', '%' . $this->search . '%');
            });
        }

        // Paginasi dan urutan berdasarkan kategori tagihan
        $tagihans = $query
            ->orderBy('ms_jenis_tagihan_siswa_id')
            ->paginate(20); // 🔥 ideal

        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($t) => $t->total_bayar ?? 0);
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
