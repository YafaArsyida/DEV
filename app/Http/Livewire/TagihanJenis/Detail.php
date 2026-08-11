<?php

namespace App\Http\Livewire\TagihanJenis;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TagihanSiswa;

class Detail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 50;

    public $ms_jenis_tagihan_siswa_id;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null; // Filter kelas

    public $search = ''; // Untuk pencarian
    public $nama_tagihan = ''; // Nama tagihan

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

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function showDetailTagihan($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->ms_jenis_tagihan_siswa_id = $params['ms_jenis_tagihan_siswa_id'];
        $this->nama_tagihan = $params['nama_tagihan'];
        $this->resetPage();
    }

    public function DetailPdf()
    {
        if (!$this->ms_jenis_tagihan_siswa_id) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenis tagihan wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'laporan diproses.']);

        $url = route('keuangan.tagihan-jenis.detail-pdf', [
            'selectedJenisTagihan' => $this->ms_jenis_tagihan_siswa_id,
            'selectedKelas' => $this->selectedKelas,
            'search' => $this->search,
        ]);

        $this->emit('openNewTab', $url);
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

        // QUERY UTAMA
        $query = TagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas'
            ])
            ->select('ms_tagihan_siswa.*') // 🔥 penting biar tidak bentrok
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tagihan_siswa.ms_penempatan_siswa_id')
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_penempatan_siswa.ms_siswa_id')
            ->join('ms_kelas', 'ms_kelas.ms_kelas_id', '=', 'ms_penempatan_siswa.ms_kelas_id')
            ->where('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $this->ms_jenis_tagihan_siswa_id)
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar');

        // FILTER tetap pakai relation (clean)
        if ($this->selectedKelas) {
            $query->where('ms_kelas.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $this->search . '%');
        }

        // ORDER BY jadi simple & cepat
        $tagihans = $query
            ->orderBy('ms_kelas.nama_kelas')
            ->orderBy('ms_siswa.nama_siswa')
            ->paginate($this->perPage);

        // TOTAL
        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($t) => $t->total_bayar ?? 0);
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

        // RETURN
        return view('livewire.tagihan-jenis.detail', compact(
            'select_kelas',
            'tagihans',
            'totalEstimasi',
            'totalDibayarkan',
            'totalKekurangan'
        ));
    }
}
