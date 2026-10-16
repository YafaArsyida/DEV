<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterStatus = '';
    public $filterJalur = '';
    public $filterSumber = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterJalur' => ['except' => ''],
        'filterSumber' => ['except' => ''],
    ];

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
        $pendaftar = PPDBPendaftaran::query()
            ->with([
                'ms_pengguna',
                'ppdb_calon_siswa',
                'ppdb_orangtua',
                'ppdb_gelombang',
                'ppdb_jalur',
            ])
            ->when($this->search, function ($query) {
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
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterJalur, function ($query) {
                $query->where(
                    'ppdb_jalur_id', $this->filterJalur
                );
            })
            ->when($this->filterSumber, function ($query) {
                $query->whereHas('ms_pengguna', function ($query) {
                    if ($this->filterSumber === 'online') {
                        $query->where('peran', 'ORANG_TUA');
                    } elseif ($this->filterSumber === 'dibantu_panitia') {
                        $query->whereIn('peran', [
                            'ADMIN',
                            'SUPERADMIN',
                        ]);
                    }
                });
            })
            ->latest('created_at')
            ->paginate(50);

        return view('livewire.p-p-d-b.admin.pendaftar.index',[
            'pendaftar' => $pendaftar,

            'daftarJalur' => PPDBJalur::query()
                ->where('status', 'aktif')
                ->orderBy('nama_jalur')
                ->get(),
        ]);
    }
}
