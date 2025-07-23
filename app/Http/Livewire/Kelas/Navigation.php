<?php

namespace App\Http\Livewire\Kelas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas as KelasModel;

class Navigation extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'refreshKelass' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];
    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

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
        $kelass = $kelass->paginate(10); // Memanggil paginate() langsung pada query builder

        // Return data ke view
        return view('livewire.kelas.navigation', [
            'kelass' => $kelass,
        ]);
    }
}
