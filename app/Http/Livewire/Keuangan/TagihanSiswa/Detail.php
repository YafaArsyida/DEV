<?php

namespace App\Http\Livewire\Keuangan\TagihanSiswa;

use App\Models\KategoriTagihanSiswa;
use App\Models\TagihanSiswa;
use Livewire\WithPagination;
use Livewire\Component;

class Detail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 50;

    public $ms_penempatan_siswa_id;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKategori;

    public $search = '';

    public $namaSiswaCurrent = '';

    // Listener untuk Livewire
    protected $listeners = [
        'showDetailTagihan'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingselectedKategori()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function showDetailTagihan($params)
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Detail dimuat'
        ]);
        
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->ms_penempatan_siswa_id = $params['ms_penempatan_siswa_id'];
        $this->namaSiswaCurrent = $params['nama_siswa'] ?? 'Siswa';
    }

    public function DetailPdf()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'laporan diproses.']);

        $url = route('keuangan.tagihan-siswa.detail-pdf', [
            'selectedSiswa' => $this->ms_penempatan_siswa_id,
            'selectedKategori' => $this->selectedKategori
        ]);

        $this->emit('openNewTab', $url);
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

        $query = TagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas'
            ])
            ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where('status_transaksi', '!=', 'dibatalkan');
                }
            ], 'jumlah_bayar');


        // FILTER KATEGORI
        if ($this->selectedKategori) {
            $query->whereRelation(
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_kategori_tagihan_siswa_id',
                $this->selectedKategori
            );
        }

        // SEARCH
        if ($this->search) {
            $query->whereRelation(
                'ms_jenis_tagihan_siswa',
                'nama_jenis_tagihan_siswa',
                'like',
                '%' . $this->search . '%'
            );
        }

        $tagihans = $query
            ->orderBy('ms_jenis_tagihan_siswa_id')
            ->paginate($this->perPage);

        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($t) => $t->total_bayar ?? 0);
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

        return view('livewire.keuangan.tagihan-siswa.detail', compact(
            'select_kategori',
            'tagihans',
            'totalEstimasi',
            'totalDibayarkan',
            'totalKekurangan'
        ));
    }
}
