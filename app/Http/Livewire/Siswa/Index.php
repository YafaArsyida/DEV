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
    public $perPage = 50;

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

    protected $listeners = [
        'refreshSiswas' => 'handleRefreshSiswas',
        'parameterUpdated' => 'updateParameters',
        'refreshKelass' => 'updatedSelectedKelas'
    ];

    public function handleRefreshSiswas($selected = [])
    {
        $this->siswaSelected = $selected;
        $this->selectAll = false;

        $this->emitSelf('$refresh'); // optional
    }

    public function updatedSelectAll($value)
    {
        $this->siswaSelected = $value
            ? collect($this->siswasOnPage)->pluck('ms_penempatan_siswa_id')->toArray()
            : [];
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->namaJenjang = Jenjang::whereKey($jenjang)->value('nama_jenjang') ?? '-';
        $this->namaTahunAjar = TahunAjar::whereKey($tahunAjar)->value('nama_tahun_ajar') ?? '-';

        $this->resetPage();
    }

    public function updatedSelectedKelas()
    {
        $this->namaKelas = KelasModel::whereKey($this->selectedKelas)
            ->value('nama_kelas') ?? '';

        $this->resetPage();
    }

    // public function showExportSiswa()
    // {
    //     // Query dengan filter jenjang, tahun ajar, dan kelas
    //     $query = PenempatanSiswaModel::with(['ms_siswa', 'ms_kelas', 'ms_tahun_ajar', 'ms_jenjang'])
    //         ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
    //         ->where('ms_jenjang_id', $this->selectedJenjang)
    //         ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

    //     // Filter berdasarkan kelas (jika dipilih)
    //     if ($this->selectedKelas) {
    //         $query->where('ms_kelas_id', $this->selectedKelas);
    //     }

    //     // Ambil semua data tanpa pagination
    //     $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
    //         ->orderBy('ms_siswa.nama_siswa')->get();

    //     // Hitung saldo_tabungan dan saldo_edupay
    //     $siswas = $siswas->map(function ($item) {
    //         $item['saldo_tabungan'] = $item->ms_siswa->saldo_tabungan();
    //         $item['saldo_edupay'] = $item->ms_siswa->saldo_edupay();
    //         return $item;
    //     });

    //     // Emit data ke komponen Livewire lainnya
    //     $this->emit('prepareExport', $this->selectedJenjang, $this->selectedTahunAjar, $siswas->toArray());
    // }


    public function render()
    {
        $select_kelas = collect();

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = KelasModel::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Data siswa (hanya jika Jenjang dan Tahun Ajar dipilih)
        $siswas = collect();

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $siswas = PenempatanSiswaModel::query()
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->select('ms_penempatan_siswa.*') // 🔥 WAJIB
                ->with([
                    'ms_siswa.ms_educard',
                    'ms_kelas',
                ])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)

                ->when(
                    $this->selectedKelas,
                    fn($q) =>
                    $q->where('ms_kelas_id', $this->selectedKelas)
                )
                ->when($this->search, function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereHas('ms_siswa', function ($q3) {
                            $q3->where('nama_siswa', 'like', '%' . $this->search . '%');
                        })
                            ->orWhereHas('ms_siswa.ms_educard', function ($q3) {
                                $q3->where('kode_kartu', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->orderBy('ms_kelas_id')
                ->orderBy('ms_siswa.nama_siswa')
                ->paginate($this->perPage);

            $this->siswasOnPage = $siswas->items();
        }

        // Return data ke view
        return view('livewire.siswa.index', compact('select_kelas', 'siswas'));
    }
}
