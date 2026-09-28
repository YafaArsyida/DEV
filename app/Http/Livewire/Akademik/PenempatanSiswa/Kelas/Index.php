<?php

namespace App\Http\Livewire\Akademik\PenempatanSiswa\Kelas;

use App\Models\Jenjang;
use App\Models\Kelas;
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
        'refreshKelass' => '$refresh',
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
        $kelass = Kelas::query()
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
        ->paginate(12);

        // Calculate stats
        $totalKelas = Kelas::query()
            ->when($this->selectedJenjang, fn ($q) => $q->where('ms_jenjang_id', $this->selectedJenjang))
            ->when($this->selectedTahunAjar, fn ($q) => $q->where('ms_tahun_ajar_id', $this->selectedTahunAjar))
            ->count();

        $totalSiswa = \App\Models\PenempatanSiswa::query()
            ->when($this->selectedJenjang, fn ($q) => $q->whereHas('ms_kelas', fn ($sq) => $sq->where('ms_jenjang_id', $this->selectedJenjang)))
            ->when($this->selectedTahunAjar, fn ($q) => $q->whereHas('ms_kelas', fn ($sq) => $sq->where('ms_tahun_ajar_id', $this->selectedTahunAjar)))
            ->count();

        $rataRataSiswa = $totalKelas > 0 ? round($totalSiswa / $totalKelas, 1) : 0;

        return view('livewire.akademik.penempatan-siswa.kelas.index',[
             'kelass' => $kelass,
            'totalKelas' => $totalKelas,
            'totalSiswa' => $totalSiswa,
            'rataRataSiswa' => $rataRataSiswa,
        ]);
    }
}
