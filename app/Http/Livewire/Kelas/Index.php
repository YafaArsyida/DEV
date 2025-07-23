<?php

namespace App\Http\Livewire\Kelas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas as KelasModel;
use App\Models\Jenjang as JenjangModel;
use App\Models\TahunAjar as TahunAjarModel;

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
        'refreshKelass' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];
    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = JenjangModel::find($jenjang);
        $tahunAjar = TahunAjarModel::find($tahunAjar);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';

        // Reset halaman pagination ketika filter berubah
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Query kelas hanya jika jenjang dan tahun ajar dipilih
        $kelass = KelasModel::query();

        // Filter berdasarkan Jenjang
        if ($this->selectedJenjang) {
            $kelass->where('ms_jenjang_id', $this->selectedJenjang);
        }

        // Filter berdasarkan Tahun Ajar
        if ($this->selectedTahunAjar) {
            $kelass->where('ms_tahun_ajar_id', $this->selectedTahunAjar);
        }

        // Filter berdasarkan Pencarian (jika ada input pencarian)
        if ($this->search) {
            $kelass->where('nama_kelas', 'like', '%' . $this->search . '%');
        }

        // Ambil data kelas yang sudah difilter dan paginasi
        $kelass = $kelass->paginate(100); // Memanggil paginate() langsung pada query builder

        // Return data ke view
        return view('livewire.kelas.index', [
            'kelass' => $kelass,
        ]);
    }
}
