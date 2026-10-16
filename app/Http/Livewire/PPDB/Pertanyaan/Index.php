<?php

namespace App\Http\Livewire\PPDB\Pertanyaan;

use App\Models\Jenjang;
use App\Models\TahunAjar;
use App\Models\PPDBPeriode;
use App\Models\PPDBPertanyaan;
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
        'refreshPertanyaans' => '$refresh',
        'parameterUpdated' => 'updateParameters',
    ];

    /**
     * Memperbarui parameter jenjang dan tahun ajaran.
     */
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->namaJenjang = Jenjang::whereKey($jenjang)
            ->value('nama_jenjang') ?? '-';

        $this->namaTahunAjar = TahunAjar::whereKey($tahunAjar)
            ->value('nama_tahun_ajar') ?? '-';

        $this->parameterLoaded = true;
        $this->selectedPeriode = '';

        $this->resetPage();
    }

    /**
     * Reset pagination ketika pencarian berubah.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination ketika periode berubah.
     */
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
         $pertanyaans = collect();
        $select_periode = collect();

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_periode = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->orderBy('nama_periode')
                ->get();
        }

        if (
            $this->parameterLoaded
            && $this->selectedJenjang
            && $this->selectedTahunAjar
        ) {
            $pertanyaans = PPDBPertanyaan::query()
                ->with([
                    'ppdb_periode.ms_jenjang',
                    'ppdb_periode.ms_tahun_ajar',
                ])
                ->whereHas('ppdb_periode', function ($query) {
                    $query
                        ->where(
                            'ms_jenjang_id',
                            $this->selectedJenjang
                        )
                        ->where(
                            'ms_tahun_ajar_id',
                            $this->selectedTahunAjar
                        );
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query
                            ->where(
                                'pertanyaan',
                                'like',
                                '%' . $this->search . '%'
                            )
                            ->orWhereHas(
                                'ppdb_periode',
                                function ($periode) {
                                    $periode->where(
                                        'nama_periode',
                                        'like',
                                        '%' . $this->search . '%'
                                    );
                                }
                            );
                    });
                })
                ->when($this->selectedPeriode, function ($query) {
                    $query->where(
                        'ppdb_periode_id',
                        $this->selectedPeriode
                    );
                })
                ->orderBy('ppdb_periode_id')
                ->orderBy('urutan')
                ->orderBy('ppdb_pertanyaan_id')
                ->paginate(12);
        }

        return view('livewire.p-p-d-b.pertanyaan.index',[
            'pertanyaans' => $pertanyaans,
            'select_periode' => $select_periode,
        ]);
    }
}
