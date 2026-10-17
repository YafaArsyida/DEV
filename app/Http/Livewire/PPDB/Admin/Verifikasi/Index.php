<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $cari = '';
    public $filterStatus = 'pengecekan';
    public $filterDokumen = '';

    protected $queryString = [
        'cari' => ['except' => ''],
        'filterStatus' => ['except' => 'pengecekan'],
        'filterDokumen' => ['except' => ''],
    ];

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
        $query = PPDBPendaftaran::query()
            ->whereHas('ppdb_periode', function ($q) {
                $q->where('status', 'aktif');
            })
            ->with([
                'ppdb_calon_siswa',
                'ppdb_jalur',
                'ppdb_berkas',
            ]);

        if ($this->cari !== '') {
            $kataKunci = trim($this->cari);

            $query->where(function ($q) use ($kataKunci) {
                $q->where(
                    'nomor_pendaftaran',
                    'like',
                    '%' . $kataKunci . '%'
                );

                $q->orWhereHas('calonSiswa', function ($siswa) use ($kataKunci) {
                    $siswa->where(
                        'nama_lengkap',
                        'like',
                        '%' . $kataKunci . '%'
                    );
                });
            });
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterDokumen === 'lengkap') {
            $query->whereDoesntHave('ppdb_berkas', function ($berkas) {
                $berkas->whereNull('path_file');
            });
        }

        if ($this->filterDokumen === 'belum_lengkap') {
            $query->whereDoesntHave('ppdb_berkas');
        }

        $antrean = $query
            ->latest('updated_at')
            ->paginate(50);

        $jumlahMenunggu = PPDBPendaftaran::query()
            ->whereHas('ppdb_periode', function ($q) {
                $q->where('status', 'aktif');
            })
            ->where('status', 'pengecekan')
            ->count();

        return view('livewire.p-p-d-b.admin.verifikasi.index',[
            'antrean' => $antrean,
            'jumlahMenunggu' => $jumlahMenunggu,
        ]);
    }
}
