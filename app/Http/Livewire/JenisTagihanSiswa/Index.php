<?php

namespace App\Http\Livewire\JenisTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\Jenjang;
use App\Models\KategoriTagihanSiswa;
use App\Models\TahunAjar;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKategoriTagihan = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';
    public $namaKategori = '';

    public function toggleStatus($tahunId, $status)
    {
        $jenis_tagihan = JenisTagihanSiswa::find($tahunId);

        if ($jenis_tagihan) {
            $jenis_tagihan->cicilan_status = $status ? 'Aktif' : 'Tidak Aktif';
            $jenis_tagihan->save();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Status cicilan berhasil diubah!']);
            $this->emit('refreshJenisTagihans');
        } else {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal mengubah status. Data tidak ditemukan.']);
        }
    }

    // Listener untuk Livewire
    protected $listeners = [
        'refreshJenisTagihans' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function updatedSelectedKategoriTagihan()
    {
        $kategori = KategoriTagihanSiswa::find($this->selectedKategoriTagihan);
        $this->namaKategori = $kategori ? $kategori->nama_kategori_tagihan_siswa : '';

        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahunAjar = TahunAjar::find($tahunAjar);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';

        $this->selectedKategoriTagihan = null;
        $this->resetPage(); // Reset pagination ketika parameter berubah
    }
    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kategori = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kategori = KategoriTagihanSiswa::with(['ms_jenjang', 'ms_tahun_ajar'])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $jenis_tagihans = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = JenisTagihanSiswa::with(['ms_kategori_tagihan_siswa', 'ms_tahun_ajar', 'ms_jenjang'])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            if ($this->selectedKategoriTagihan) {
                $query->where('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
            }

            // Filter berdasarkan pencarian nama
            if ($this->search) {
                $query->where('nama_jenis_tagihan_siswa', 'like', '%' . $this->search . '%');
            }

            $jenis_tagihans = $query->orderBy('created_at', 'ASC')->get();
        }
        return view('livewire.jenis-tagihan-siswa.index', [
            'jenis_tagihans' => $jenis_tagihans,
            'select_kategori' => $select_kategori
        ]);
    }
}
