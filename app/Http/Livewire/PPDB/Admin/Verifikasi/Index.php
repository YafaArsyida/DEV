<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $cari = '';
    public $filterStatus = 'pengecekan';
    public $filterDokumen = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'pendaftaranTersimpan' => '$refresh',
        'pendaftaranDiperbarui' => '$refresh',
    ];

    protected $queryString = [
        'cari' => ['except' => ''],
        'filterStatus' => ['except' => 'pengecekan'],
        'filterDokumen' => ['except' => ''],
    ];

    /**
     * Menerima perubahan parameter jenjang dan tahun ajar.
     */
    public function updateParameters($jenjangId = null, $tahunAjarId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;

        $this->resetPage();
    }

    public function updatingCari()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterDokumen()
    {
        $this->resetPage();
    }

    /**
     * Menghapus filter antrean verifikasi.
     */
    public function resetFilter()
    {
        $this->reset([
            'cari',
            'filterDokumen',
        ]);

        $this->filterStatus = 'pengecekan';

        $this->resetPage();
    }

    public function render()
    {
        $periode = null;

        // Cari periode aktif berdasarkan parameter terpilih.
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

        // Query utama antrean verifikasi.
        $query = PPDBPendaftaran::query()
            ->with([
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_jalur',
                'ppdb_berkas',
            ]);

        // Batasi data ke periode yang dipilih.
        if ($periode) {
            $query->where(
                'ppdb_periode_id',
                $periode->ppdb_periode_id
            );
        } else {
            // Parameter belum lengkap atau periode tidak ditemukan.
            $query->whereRaw('1 = 0');
        }

        // Pencarian nomor pendaftaran atau nama siswa.
        $query->when(
            trim($this->cari) !== '',
            function ($query) {
                $kataKunci = trim($this->cari);

                $query->where(function ($q) use ($kataKunci) {
                    $q->where(
                        'nomor_pendaftaran',
                        'like',
                        '%' . $kataKunci . '%'
                    );

                    $q->orWhereHas(
                        'ppdb_calon_siswa',
                        function ($siswa) use ($kataKunci) {
                            $siswa->where(
                                'nama_siswa',
                                'like',
                                '%' . $kataKunci . '%'
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

        // Filter kelengkapan dokumen.
        if ($this->filterDokumen === 'lengkap') {
            $query->whereDoesntHave(
                'ppdb_berkas',
                function ($berkas) {
                    $berkas->whereNull('path_file');
                }
            );
        }

        if ($this->filterDokumen === 'belum_lengkap') {
            $query->whereDoesntHave('ppdb_berkas');
        }

        // Antrean terbaru ditampilkan lebih dahulu.
        $antrean = $query
            ->latest('updated_at')
            ->paginate(50);

        return view('livewire.p-p-d-b.admin.verifikasi.index',[
            'antrean' => $antrean,
            'periode' => $periode,
        ]);
    }
}
