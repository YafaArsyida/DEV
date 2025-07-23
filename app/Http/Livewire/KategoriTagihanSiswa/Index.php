<?php

namespace App\Http\Livewire\KategoriTagihanSiswa;

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

    public $namaJenjang = '';
    public $namaTahunAjar = '';

    protected $listeners = [
        'refreshKategoriTagihans' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahunAjar = TahunAjar::find($tahunAjar);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';


        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query kelas hanya jika jenjang dan tahun ajar dipilih
        $kategoris = KategoriTagihanSiswa::query();

        // Filter berdasarkan Jenjang
        if ($this->selectedJenjang) {
            $kategoris->where('ms_jenjang_id', $this->selectedJenjang);
        }

        // Filter berdasarkan Tahun Ajar
        if ($this->selectedTahunAjar) {
            $kategoris->where('ms_tahun_ajar_id', $this->selectedTahunAjar);
        }

        // Filter berdasarkan Pencarian (jika ada input pencarian)
        if ($this->search) {
            $kategoris->where('nama_kategori_tagihan_siswa', 'like', '%' . $this->search . '%');
        }

        // Ambil data kelas yang sudah difilter dan paginasi
        $kategoris = $kategoris->paginate(1000);

        return view('livewire.kategori-tagihan-siswa.index', [
            'kategoris' => $kategoris,
        ]);
    }
}
