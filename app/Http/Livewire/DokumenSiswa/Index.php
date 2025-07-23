<?php

namespace App\Http\Livewire\DokumenSiswa;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';
    public $isExport = false;
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    public $siswasOnPage = [];
    public $siswaSelected = [];
    public $selectAll = false;

    // Listener untuk Livewire
    protected $listeners = [
        'refreshSiswas' => 'handleRefreshSiswas',
        'parameterUpdated' => 'updateParameters',
    ];

    public function handleRefreshSiswas($selected = [])
    {
        $this->siswaSelected = $selected; // Kosongkan array
        $this->emitSelf('$refresh'); // Memicu render ulang komponen sendiri
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Tambahkan semua ID siswa dari halaman aktif
            $this->siswaSelected = collect($this->siswasOnPage)->pluck('ms_penempatan_siswa_id')->toArray();
        } else {
            // Kosongkan siswaSelected
            $this->siswaSelected = [];
        }
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function updatingSelectedKelas()
    {
        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->resetPage(); // Reset pagination ketika parameter berubah
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Data siswa (hanya jika Jenjang dan Tahun Ajar dipilih)
        $siswas = null;
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = PenempatanSiswa::with(['ms_siswa.ms_educard', 'ms_kelas', 'ms_tahun_ajar', 'ms_jenjang'])
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            // Filter berdasarkan kelas (jika dipilih)
            if ($this->selectedKelas) {
                $query->where('ms_kelas_id', $this->selectedKelas);
            }

            // Filter berdasarkan pencarian nama siswa
            if ($this->search) {
                $query->whereHas('ms_siswa', function ($query) {
                    $query->where('nama_siswa', 'like', '%' . $this->search . '%');
                });
            }

            $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
                ->orderBy('ms_siswa.nama_siswa')->paginate(50);

            $this->siswasOnPage = $siswas->items();
        }

        // Cek apakah koleksi siswa kosong.
        if (!$siswas || $siswas->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
        }

        return view('livewire.dokumen-siswa.index', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
