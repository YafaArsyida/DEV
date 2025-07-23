<?php

namespace App\Http\Livewire\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedJenjang = null;

    public $namaJenjang = '';

    protected $listeners = [
        'refreshEkstrakurikuler' => '$refresh',
        'parameterUpdated' => 'updateParameters'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); // Memicu render ulang komponen sendiri
    }

    public function render()
    {
        // Query hanya jika jenjang dan tahun ajar dipilih
        $data = Ekstrakurikuler::query();

        // Filter berdasarkan Jenjang
        if ($this->selectedJenjang) {
            $data->where('ms_jenjang_id', $this->selectedJenjang);
        }

        // Filter berdasarkan Pencarian (jika ada input pencarian)
        if ($this->search) {
            $data->where('nama_ekstrakurikuler', 'like', '%' . $this->search . '%');
        }

        // Ambil data yang sudah difilter dan paginasi
        $data = $data->get(); // Memanggil paginate() langsung pada query builder

        return view('livewire.ekstrakurikuler.index', [
            'data' => $data
        ]);
    }
}
