<?php

namespace App\Http\Livewire\PPDB\Admin\Laporan;

use App\Models\PPDBJalur;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PPDBPeriode;
use App\Models\PPDBPendaftaran;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $search = '';
    public $filterJalur = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'pendaftaranTersimpan' => '$refresh',
        'pendaftaranDiperbarui' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * Menerima perubahan jenjang dan tahun ajar.
     */
    public function updateParameters(
        $jenjangId = null,
        $tahunAjarId = null
    ) {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;

        $this->filterJalur = '';

        $this->resetPage();
    }

    /**
     * Reset pagination saat pencarian berubah.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterJalur()
    {
        $this->resetPage();
    }

    /**
     * Menghapus pencarian.
     */
    public function resetFilter()
    {
        $this->reset('search');

        $this->resetPage();
    }

    public function render()
    {
        $periode = null;

        $daftarJalur = collect();

        // Cari periode berdasarkan parameter jenjang
        // dan tahun ajar yang dipilih.
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

            if ($periode) {
                $daftarJalur = PPDBJalur::query()
                    ->where(
                        'ppdb_periode_id',
                        $periode->ppdb_periode_id
                    )
                    ->where('status', 'aktif')
                    ->orderBy('nama_jalur')
                    ->get();
            }
        }

        // Query utama laporan.
        $query = PPDBPendaftaran::query()
            ->with([
                'ppdb_calon_siswa',
                'ppdb_jalur',
            ])
            ->where('status', 'diterima');

        // Batasi laporan ke periode terpilih.
        if ($periode) {
            $query->where(
                'ppdb_periode_id',
                $periode->ppdb_periode_id
            );
        } else {
            // Jangan tampilkan data jika parameter
            // atau periode aktif belum valid.
            $query->whereRaw('1 = 0');
        }

        // Cari berdasarkan nomor pendaftaran
        // atau nama calon siswa.
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

        $query->when(
            $this->filterJalur !== '',
            function ($query) {
                $query->where(
                    'ppdb_jalur_id',
                    $this->filterJalur
                );
            }
        );

        // Urutkan berdasarkan pendaftaran terbaru.
        $pendaftar = $query
            ->latest('created_at')
            ->paginate(50);

        return view('livewire.p-p-d-b.admin.laporan.index',[
            'periode' => $periode,
            'pendaftar' => $pendaftar,
            'daftarJalur' => $daftarJalur,
        ]);
    }
}
