<?php

namespace App\Http\Livewire\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\Kelas;
use App\Models\PenempatanEkstrakurikuler;
use App\Models\PenempatanSiswa;
use Livewire\Component;

class Detail extends Component
{
    public $ms_ekstrakurikuler_id;

    public $search = '';
    public $selectedJenjang;
    public $selectedKelas;

    public $siswaTerdaftar = [];

    // Listener untuk Livewire
    protected $listeners = [
        'detailEkstrakurikuler',
        'cetakEkstrakurikuler'
    ];

    public function detailEkstrakurikuler($ms_ekstrakurikuler_id)
    {
        $this->ms_ekstrakurikuler_id = $ms_ekstrakurikuler_id;

        // Ambil data ekstrakurikuler
        $ekskul = Ekstrakurikuler::find($ms_ekstrakurikuler_id);

        if ($ekskul) {
            $this->selectedJenjang = $ekskul->ms_jenjang_id;
        }
    }
    public function cetakEkstrakurikuler($ms_ekstrakurikuler_id)
    {
        if (!$ms_ekstrakurikuler_id) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'ektrakurikuler tidak diketahui']);
            return;
        }
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'laporan diproses.']);

        $url = route('administrasi.ekstrakurikuler-siswa.pdf', [
            'ekstrakurikuler_id' => $ms_ekstrakurikuler_id,
            'kelas' => $this->selectedKelas,
            'search' => $this->search,
        ]);

        $this->emit('openNewTab', $url);
    }
    public function render()
    {
        $select_kelas = [];
        if ($this->selectedJenjang) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                // ->where('ms_tahun_ajar_id', '1')
                ->get();
        }

        $query = PenempatanSiswa::with([
            'ms_kelas',
            'ms_siswa.ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
        ])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->whereHas('ms_siswa.ms_penempatan_ekstrakurikuler', function ($q) {
                $q->where('ms_ekstrakurikuler_id', $this->ms_ekstrakurikuler_id);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('ms_siswa', function ($subQuery) {
                    $subQuery->where('nama_siswa', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('ms_kelas_id');

        // Filter berdasarkan kelas (jika dipilih)
        if ($this->selectedKelas) {
            $query->where('ms_kelas_id', $this->selectedKelas);
        }

        // Ambil data akhir
        $siswa = $query->get();

        return view('livewire.ekstrakurikuler.detail', [
            'select_kelas' => $select_kelas,
            'siswa' => $siswa
        ]);
    }
}
