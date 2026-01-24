<?php

namespace App\Http\Livewire\Pegawai;

use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\Pegawai;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search = '';
    public $selectedJenjang = null;

    public $activeTab = 'semua';

    protected $listeners = [
        'parameterUpdated',
        'PegawaiIndex' => '$refresh',
    ];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function parameterUpdated($jenjangId)
    {
        $this->selectedJenjang = $jenjangId;
        $this->activeTab = 'semua';
    }

    public function getJabatanProperty()
    {
        return Jabatan::orderBy('nama_jabatan')
            ->get();
    }

    public function getAllPegawaiProperty()
    {
        $query = Pegawai::with([
            'ms_jabatan',
            'ms_jenjang',
            'ms_educard'
        ]);

        if ($this->selectedJenjang) {
            $query->where('ms_jenjang_id', $this->selectedJenjang);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_pegawai', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            });
        }

        return $query
            ->orderBy('ms_jenjang_id', 'ASC')
            ->orderBy('ms_jabatan_id', 'ASC')
            ->orderBy('nama_pegawai', 'ASC')
            ->get();
    }

    public function render()
    {
        return view('livewire.pegawai.index', [
            'jabatan'    => $this->jabatan,
            'allPegawai' => $this->allPegawai,
        ]);
    }
}
