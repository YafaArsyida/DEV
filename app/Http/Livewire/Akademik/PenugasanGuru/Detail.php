<?php

namespace App\Http\Livewire\Akademik\PenugasanGuru;

use App\Http\Livewire\Akademik\PenugasanGuru\Concerns\InteractsWithPenugasanGuru;
use Livewire\Component;

class Detail extends Component
{
    use InteractsWithPenugasanGuru;

    public $selectedPenugasanGuruId = null;

    public $selectedJenjang = null;

    public $selectedTahunAjar = null;

    protected $listeners = ['loadDetailPenugasanGuru'];

    public function loadDetailPenugasanGuru($id, $jenjang, $tahunAjar)
    {
        $this->assertValidContext($jenjang, $tahunAjar);

        $assignment = $this->assignmentsForContext($jenjang, $tahunAjar)->findOrFail($id);

        $this->selectedPenugasanGuruId = $assignment->ms_penugasan_guru_id;
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function render()
    {
        $penugasanDetail = null;
        $jumlahJadwal = 0;

        if ($this->selectedPenugasanGuruId) {
            $penugasanDetail = $this->assignmentsForContext($this->selectedJenjang, $this->selectedTahunAjar)
                ->with(['ms_pegawai', 'ms_kelas.ms_jenjang', 'ms_kelas.ms_tahun_ajar', 'ms_mata_pelajaran', 'ms_tahun_ajar'])
                ->find($this->selectedPenugasanGuruId);

            $jumlahJadwal = $penugasanDetail
                ? $penugasanDetail->jadwal_mengajar()->count()
                : 0;
        }

        return view('livewire.akademik.penugasan-guru.detail', [
            'penugasanDetail' => $penugasanDetail,
            'jumlahJadwal' => $jumlahJadwal,
        ]);
    }
}
