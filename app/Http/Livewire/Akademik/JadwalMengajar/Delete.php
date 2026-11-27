<?php

namespace App\Http\Livewire\Akademik\JadwalMengajar;

use App\Http\Livewire\Akademik\JadwalMengajar\Concerns\InteractsWithJadwalMengajar;
use Livewire\Component;

class Delete extends Component
{
    use InteractsWithJadwalMengajar;

    public $jadwalId = null;
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedPeriode = null;

    public $namaGuru = '';
    public $namaKelas = '';
    public $namaMapel = '';
    public $hari = '';
    public $jam = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'confirmDeleteJadwal' => 'confirmDeleteJadwal',
    ];

    public function updateParameters($jenjangId = null, $tahunAjarId = null, $periodeId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;
        $this->selectedPeriode = $periodeId;
    }

    public function confirmDeleteJadwal($id, $jenjangId = null, $tahunAjarId = null, $periodeId = null)
    {
        if ($jenjangId !== null || $tahunAjarId !== null || $periodeId !== null) {
            $this->selectedJenjang = $jenjangId;
            $this->selectedTahunAjar = $tahunAjarId;
            $this->selectedPeriode = $periodeId;
        }

        $this->assertValidPeriodeContext($this->selectedJenjang, $this->selectedTahunAjar, $this->selectedPeriode);

        $jadwal = $this->jadwalForContext(
            $this->selectedJenjang,
            $this->selectedTahunAjar,
            $this->selectedPeriode
        )->with($this->jadwalRelations())->findOrFail($id);

        $assignment = $jadwal->ms_penugasan_guru;

        $this->jadwalId = $jadwal->ej_jadwal_mengajar_id;
        $this->namaGuru = $assignment?->ms_pegawai?->nama_pegawai ?? '-';
        $this->namaKelas = $assignment?->ms_kelas?->nama_kelas ?? '-';
        $this->namaMapel = $assignment?->ms_mata_pelajaran?->nama_mapel ?? '-';
        $this->hari = $this->hariLabel($jadwal->hari);
        $this->jam = $this->formatTime($jadwal->jam_mulai) . ' - ' . $this->formatTime($jadwal->jam_selesai);
    }

    public function deleteJadwal()
    {
        $this->authorizeManage();
        abort_unless($this->jadwalId, 404);

        $this->assertValidPeriodeContext($this->selectedJenjang, $this->selectedTahunAjar, $this->selectedPeriode);

        $jadwal = $this->jadwalForContext(
            $this->selectedJenjang,
            $this->selectedTahunAjar,
            $this->selectedPeriode
        )->findOrFail($this->jadwalId);

        $jadwal->delete();

        $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalDeleteJadwal']);
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Jadwal mengajar berhasil dihapus. Data penugasan guru tetap tersimpan.',
        ]);
        $this->emit('refreshJadwalMengajar');

        $this->reset([
            'jadwalId',
            'namaGuru',
            'namaKelas',
            'namaMapel',
            'hari',
            'jam',
        ]);
    }

    public function render()
    {
        return view('livewire.akademik.jadwal-mengajar.delete');
    }
}
