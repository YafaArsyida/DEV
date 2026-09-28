<?php

namespace App\Http\Livewire\Akademik\PenempatanEkstrakurikuler\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\Jenjang;
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
        'refreshEkstrakurikuler' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahunAjar = TahunAjar::find($tahunAjar);
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
        // Query hanya jika jenjang dan tahun ajar dipilih
        $data = Ekstrakurikuler::query()
            ->when($this->selectedJenjang, fn ($q) =>
                $q->where('ms_jenjang_id', $this->selectedJenjang)
            )
            ->when($this->selectedTahunAjar, fn ($q) =>
                $q->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            )
            ->when($this->search, fn ($q) =>
                $q->where('nama_ekstrakurikuler', 'like', '%' . $this->search . '%')
            )
        ->withCount('ms_penempatan_ekstrakurikuler')
        ->orderByDesc('ms_ekstrakurikuler_id')
        ->paginate(40);

        return view('livewire.akademik.penempatan-ekstrakurikuler.ekstrakurikuler.index',[
            'data' => $data
        ]);
    }
}
