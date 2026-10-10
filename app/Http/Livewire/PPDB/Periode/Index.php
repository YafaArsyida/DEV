<?php

namespace App\Http\Livewire\PPDB\Periode;

use App\Models\Jenjang;
use App\Models\PPDBPeriode;
use App\Models\TahunAjar;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $parameterLoaded = false;

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';

    protected $listeners = [
        'refreshPeriodes' => '$refresh',
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

    /**
     * Reset pagination saat pencarian berubah.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $periodes = collect();

        if ($this->parameterLoaded) {
            $periodes = PPDBPeriode::query()
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->when(
                    $this->search,
                    fn ($q) => $q->where(
                        'nama_periode',
                        'like',
                        '%' . $this->search . '%'
                    )
                )
                ->with([
                    'ms_tahun_ajar',
                    'ms_jenjang',
                ])
                ->withCount('ppdb_gelombang')
                ->paginate(12);
        }

        return view('livewire.p-p-d-b.periode.index', [
            'periodes' => $periodes,
        ]);
    }
}
