<?php

namespace App\Http\Livewire\Siswa;

use App\Models\Jenjang;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PenempatanSiswa as PenempatanSiswaModel;
use App\Models\Kelas as KelasModel;
use App\Models\TahunAjar;

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

    public $namaJenjang = '';
    public $namaTahunAjar = '';
    public $namaKelas = '';

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

    public function updatedSelectedKelas()
    {
        $kelas = KelasModel::find($this->selectedKelas);
        $this->namaKelas = $kelas ? $kelas->nama_kelas : '';

        $this->resetPage(); // Reset pagination ketika kelas berubah
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

    public function showExportSiswa()
    {
        // Query dengan filter jenjang, tahun ajar, dan kelas
        $query = PenempatanSiswaModel::with(['ms_siswa', 'ms_kelas', 'ms_tahun_ajar', 'ms_jenjang'])
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

        // Filter berdasarkan kelas (jika dipilih)
        if ($this->selectedKelas) {
            $query->where('ms_kelas_id', $this->selectedKelas);
        }

        // Ambil semua data tanpa pagination
        $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')->get();

        // Hitung saldo_tabungan dan saldo_edupay
        $siswas = $siswas->map(function ($item) {
            $item['saldo_tabungan'] = $item->ms_siswa->saldo_tabungan();
            $item['saldo_edupay'] = $item->ms_siswa->saldo_edupay();
            return $item;
        });

        // Emit data ke komponen Livewire lainnya
        $this->emit('prepareExport', $this->selectedJenjang, $this->selectedTahunAjar, $siswas->toArray());
    }


    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = KelasModel::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Data siswa (hanya jika Jenjang dan Tahun Ajar dipilih)
        $siswas = null;
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = PenempatanSiswaModel::with([
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
                ->orderBy('ms_siswa.nama_siswa')->paginate(50);

            $this->siswasOnPage = $siswas->items();
        }

        // Cek apakah koleksi siswa kosong.
        if (!$siswas || $siswas->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
        }

        // Return data ke view
        return view('livewire.siswa.index', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
