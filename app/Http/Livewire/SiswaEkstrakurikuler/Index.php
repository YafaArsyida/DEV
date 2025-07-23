<?php

namespace App\Http\Livewire\SiswaEkstrakurikuler;

use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\PenempatanEkstrakurikuler;
use App\Models\PenempatanSiswa;
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
    public $selectedKelas = null;

    public $siswasOnPage = [];
    public $siswaSelected = [];
    public $selectAll = false;

    public $namaJenjang = '';
    public $namaTahunAjar = '';

    // Listener untuk Livewire
    protected $listeners = [
        'refreshSiswas' => 'handleRefreshSiswas',
        'parameterUpdated' => 'updateParameters',
    ];

    public function handleRefreshSiswas()
    {
        $this->emitSelf('$refresh'); // Memicu render ulang komponen sendiri
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahunAjar = TahunAjar::find($tahunAjar);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';

        $this->resetPage(); // Reset pagination ketika parameter berubah
    }

    public function render()
    {
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $siswas = null;
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = PenempatanSiswa::with([
                'ms_siswa.ms_educard',
                'ms_kelas',
                'ms_tahun_ajar',
                'ms_jenjang',
                'ms_siswa.ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
            ])
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            // Filter berdasarkan kelas (jika dipilih)
            if ($this->selectedKelas) {
                $query->where('ms_kelas_id', $this->selectedKelas);
            }

            if ($this->search) {
                $query->where(function ($query) {
                    $query->whereHas('ms_siswa', function ($query) {
                        $query->where('nama_siswa', 'like', '%' . $this->search . '%');
                    })->orWhereHas('ms_siswa.ms_educard', function ($query) {
                        $query->where('kode_kartu', 'like', '%' . $this->search . '%');
                    });
                });
            }

            $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
                ->orderBy('ms_siswa.nama_siswa')->paginate(100);

            $this->siswasOnPage = $siswas->items();
        }

        // Cek apakah koleksi siswa kosong.
        if (!$siswas || $siswas->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
        }

        return view('livewire.siswa-ekstrakurikuler.index', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
