<?php

namespace App\Http\Livewire\TagihanJenis;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Kelas;
use App\Models\TagihanSiswa;

class Detail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $ms_jenis_tagihan_siswa_id;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null; // Filter kelas

    public $search = ''; // Untuk pencarian

    protected $listeners = [
        'showDetailTagihan'
    ];

    // Reset pagination saat filter atau pencarian berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedKelas()
    {
        $this->resetPage();
    }

    public function showDetailTagihan($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->ms_jenis_tagihan_siswa_id = $params['ms_jenis_tagihan_siswa_id'];
        $this->resetPage();
    }

    public function render()
    {
        // Query untuk memilih kelas berdasarkan jenjang dan tahun ajar
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Query untuk mendapatkan tagihan berdasarkan jenis tagihan dengan JOIN
        $query = TagihanSiswa::select('ms_tagihan_siswa.*', 'ms_siswa.nama_siswa', 'ms_kelas.nama_kelas', 'ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa', 'ms_kategori_tagihan_siswa.nama_kategori_tagihan_siswa')
            ->join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->join('ms_kelas', 'ms_penempatan_siswa.ms_kelas_id', '=', 'ms_kelas.ms_kelas_id')
            ->join('ms_jenis_tagihan_siswa', 'ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', '=', 'ms_jenis_tagihan_siswa.ms_jenis_tagihan_siswa_id')
            ->join('ms_kategori_tagihan_siswa', 'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id', '=', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->where('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $this->ms_jenis_tagihan_siswa_id);

        // Filter kelas jika dipilih
        if ($this->selectedKelas) {
            $query->where('ms_kelas.ms_kelas_id', $this->selectedKelas);
        }

        // Filter pencarian siswa
        if ($this->search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $this->search . '%');
        }

        // Mengambil tagihan yang sudah difilter
        $tagihans = $query->orderBy('ms_kelas.ms_kelas_id', 'ASC')
            ->orderBy('ms_siswa.nama_siswa', 'ASC')
            ->paginate(1000);

        return view('livewire.tagihan-jenis.detail', [
            'select_kelas' => $select_kelas,
            'tagihans' => $tagihans,
        ]);
    }
}
