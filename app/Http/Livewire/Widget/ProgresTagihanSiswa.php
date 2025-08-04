<?php

namespace App\Http\Livewire\Widget;

use App\Models\KategoriTagihanSiswa;
use Livewire\Component;
use Livewire\WithPagination;

class ProgresTagihanSiswa extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->resetPage();
    }

    public function render()
    {
        $kategoriData = KategoriTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            // ->orderBy('urutan')
            ->paginate(10)
            ->through(function ($kategori) {
                $estimasi = $kategori->total_tagihan_siswa();
                $dibayarkan = $kategori->total_dibayarkan();
                $presentase = $estimasi > 0 ? round(($dibayarkan / $estimasi) * 100, 2) : 0;

                return [
                    'nama_kategori_tagihan_siswa' => $kategori->nama_kategori_tagihan_siswa,
                    // 'estimasi' => $estimasi,
                    // 'dibayarkan' => $dibayarkan,
                    'presentase' => $presentase
                ];
            });

        return view('livewire.widget.progres-tagihan-siswa', [
            'kategoriData' => $kategoriData
        ]);
    }
}
