<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PPDBPeriode;
use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $search = '';
    public $filterStatus = '';
    public $filterJalur = '';
    public $filterSumber = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'pendaftaranTersimpan' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterJalur' => ['except' => ''],
        'filterSumber' => ['except' => ''],
    ];

    /**
     * Menerima perubahan jenjang dan tahun ajar.
     */
    public function updateParameters($jenjangId = null, $tahunAjarId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;

        // Jalur dapat berbeda pada setiap periode.
        $this->filterJalur = '';

        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterJalur()
    {
        $this->resetPage();
    }

    public function updatingFilterSumber()
    {
        $this->resetPage();
    }

    /**
     * Menghapus seluruh filter tabel.
     */
    public function resetFilter()
    {
        $this->reset([
            'search',
            'filterStatus',
            'filterJalur',
            'filterSumber',
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $periode = null;

        // Cari periode aktif berdasarkan parameter pilihan.
        if (
            $this->selectedJenjang &&
            $this->selectedTahunAjar
        ) {
            $periode = PPDBPeriode::query()
                ->where(
                    'ms_jenjang_id',
                    $this->selectedJenjang
                )
                ->where(
                    'ms_tahun_ajar_id',
                    $this->selectedTahunAjar
                )
                ->where('status', 'aktif')
                ->first();
        }

        // Jalur hanya ditampilkan dari periode terpilih.
        $daftarJalur = $periode
            ? PPDBJalur::query()
                ->where(
                    'ppdb_periode_id',
                    $periode->ppdb_periode_id
                )
                ->where('status', 'aktif')
                ->orderBy('nama_jalur')
                ->get()
            : collect();

        // Satu query utama untuk seluruh data pendaftar.
        $query = PPDBPendaftaran::query()
            ->with([
                'ms_pengguna',
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_gelombang',
                'ppdb_jalur',
                'pembuat',
            ]);

        // Jangan tampilkan data jika periode belum valid.
        if ($periode) {
            $query->where(
                'ppdb_periode_id',
                $periode->ppdb_periode_id
            );
        } else {
            $query->whereRaw('1 = 0');
        }

        // Pencarian nomor pendaftaran atau nama calon siswa.
        $query->when(
            trim($this->search) !== '',
            function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {
                    $query->where(
                        'nomor_pendaftaran',
                        'like',
                        '%' . $search . '%'
                    );

                    $query->orWhereHas(
                        'ppdb_calon_siswa',
                        function ($query) use ($search) {
                            $query->where(
                                'nama_siswa',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
                });
            }
        );

        // Filter status pendaftaran.
        $query->when(
            $this->filterStatus !== '',
            function ($query) {
                $query->where(
                    'status',
                    $this->filterStatus
                );
            }
        );

        // Filter jalur pendaftaran.
        $query->when(
            $this->filterJalur !== '',
            function ($query) {
                $query->where(
                    'ppdb_jalur_id',
                    $this->filterJalur
                );
            }
        );

        // Filter sumber pendaftaran.
        $query->when(
            $this->filterSumber !== '',
            function ($query) {
                $query->whereHas(
                    'ms_pengguna',
                    function ($query) {
                        if ($this->filterSumber === 'online') {
                            $query->where(
                                'peran',
                                'ORANG_TUA'
                            );
                        } elseif (
                            $this->filterSumber === 'dibantu_panitia'
                        ) {
                            $query->whereIn('peran', [
                                'ADMIN',
                                'SUPERADMIN',
                            ]);
                        }
                    }
                );
            }
        );

        // Urutkan dari pendaftaran terbaru.
        $pendaftar = $query
            ->latest('created_at')
            ->paginate(50);

        return view('livewire.p-p-d-b.admin.pendaftar.index',
            [
                'periode' => $periode,
                'pendaftar' => $pendaftar,
                'daftarJalur' => $daftarJalur,
            ]
        );
    }
}
