<?php

namespace App\Http\Livewire\Akademik\PenugasanGuru;

use App\Http\Livewire\Akademik\PenugasanGuru\Concerns\InteractsWithPenugasanGuru;
use Livewire\Component;

class Delete extends Component
{
    use InteractsWithPenugasanGuru;

    public $penugasanGuruId;

    public $namaGuru = '';

    public $namaKelas = '';

    public $namaMapel = '';

    public $selectedJenjang = null;

    public $selectedTahunAjar = null;

    protected $listeners = ['confirmDeletePenugasanGuru'];

    public function confirmDeletePenugasanGuru($id, $jenjang, $tahunAjar)
    {
        $this->assertValidContext($jenjang, $tahunAjar);
        $assignment = $this->assignmentsForContext($jenjang, $tahunAjar)
            ->with(['ms_pegawai', 'ms_kelas', 'ms_mata_pelajaran'])
            ->findOrFail($id);

        $this->penugasanGuruId = $assignment->ms_penugasan_guru_id;
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->namaGuru = $assignment->ms_pegawai->nama_pegawai;
        $this->namaKelas = $assignment->ms_kelas->nama_kelas;
        $this->namaMapel = $assignment->ms_mata_pelajaran?->nama_mapel;
    }

    public function deletePenugasanGuru()
    {
        abort_unless($this->penugasanGuruId, 404);
        $this->assertValidContext($this->selectedJenjang, $this->selectedTahunAjar);

        $assignment = $this->assignmentsForContext($this->selectedJenjang, $this->selectedTahunAjar)
            ->withCount('jadwal_mengajar')
            ->findOrFail($this->penugasanGuruId);

        if ($assignment->jadwal_mengajar_count > 0) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Penugasan tidak dapat dihapus karena sudah memiliki jadwal mengajar.',
            ]);

            return;
        }

        $assignment->delete();

        $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalDeletePenugasanGuru']);
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Penugasan guru berhasil dihapus.',
        ]);
        $this->reset([
            'penugasanGuruId',
            'namaGuru',
            'namaKelas',
            'namaMapel',
            'selectedJenjang',
            'selectedTahunAjar',
        ]);
        $this->emit('refreshPenugasanGurus');
    }

    public function render()
    {
        return view('livewire.akademik.penugasan-guru.delete');
    }
}
