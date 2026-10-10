<?php

namespace App\Http\Livewire\PPDB\Gelombang;

use App\Models\Jenjang;
use App\Models\PPDBGelombang;
use App\Models\PPDBPeriode;
use App\Models\TahunAjar;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedJenjang;
    public $selectedTahunAjar;
    public $parameterLoaded = false;
    public $search = '';

    public $namaJenjang = '';
    public $namaTahunAjar = '';

    public $selectedPeriode = '';

    protected $listeners = [
        'refreshGelombangs' => '$refresh',
        'parameterUpdated' => 'updateParameters',
    ];


    /**
     * Memperbarui parameter jenjang dan tahun ajaran.
     */
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->namaJenjang = Jenjang::whereKey($jenjang)->value('nama_jenjang') ?? '-';
        $this->namaTahunAjar = TahunAjar::whereKey($tahunAjar)->value('nama_tahun_ajar') ?? '-';

        $this->parameterLoaded = true;

        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedPeriode()
    {
        $this->resetPage();
    }
    public function updatedSelectedJenjang()
    {
        $this->resetPage();
    }

    public function updatedSelectedTahunAjar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $gelombangs = collect();
        
        $select_periode = collect();

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_periode = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->orderBy('nama_periode')
                ->get();
        }

        if ($this->parameterLoaded) {
            $gelombangs = PPDBGelombang::query()
                ->whereHas('ppdb_periode', function ($query) {
                    $query
                        ->where('ms_jenjang_id', $this->selectedJenjang)
                        ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);
                })
                ->with([
                    'ppdb_periode.ms_jenjang',
                    'ppdb_periode.ms_tahun_ajar',
                ])
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query
                            ->where('nama_gelombang', 'like', '%' . $this->search . '%')
                            ->orWhereHas('ppdb_periode', function ($periode) {
                                $periode->where(
                                    'nama_periode',
                                    'like',
                                    '%' . $this->search . '%'
                                );
                            });
                    });
                })
                ->when($this->selectedPeriode, function ($query) {
                    $query->where(
                        'ppdb_periode_id',
                        $this->selectedPeriode
                    );
                })
                ->paginate(12);
        }

        return view('livewire.p-p-d-b.gelombang.index',[
            'gelombangs' => $gelombangs,
            'select_periode' => $select_periode
        ]);
    }
}
