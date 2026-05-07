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
        $kelass = KelasModel::query()
        ->when($this->selectedJenjang, fn ($q) =>
            $q->where('ms_jenjang_id', $this->selectedJenjang)
        )
        ->when($this->selectedTahunAjar, fn ($q) =>
            $q->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
        )
        ->when($this->search, fn ($q) =>
            $q->where('nama_kelas', 'like', '%' . $this->search . '%')
        )
        ->withCount('ms_penempatan_siswa')
        ->latest('ms_kelas_id') // optional sorting
        ->paginate(10);

        // Return data ke view
        return view('livewire.kelas.index', [
            'kelass' => $kelass,
        ]);
    }
}
