<?php

namespace App\Http\Livewire\Akademik\JadwalMengajar;

use App\Http\Livewire\Akademik\JadwalMengajar\Concerns\InteractsWithJadwalMengajar;
use App\Models\JadwalMengajar;
use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Livewire\Component;

class Saya extends Component
{
    use InteractsWithJadwalMengajar;

    public $pegawaiId = null;
    public $selectedHari = '';
    public $search = '';

    public function mount()
    {
        $userId = auth()->id();

        $pegawai = Pegawai::query()
            ->where(function ($query) use ($userId) {
                $query->where('ms_pengguna_id', $userId)
                    ->orWhere('user_id', $userId);
            })
            ->first();

        $this->pegawaiId = $pegawai?->ms_pegawai_id;
    }

    public function setHari($hari)
    {
        $this->selectedHari = array_key_exists($hari, $this->hariOptions()) ? $hari : '';
    }

    public function resetFilter()
    {
        $this->selectedHari = '';
        $this->search = '';
    }

    public function render()
    {
        $pegawai = $this->pegawaiId ? Pegawai::find($this->pegawaiId) : null;

        $schedules = $pegawai
            ? JadwalMengajar::query()
                ->whereHas('ms_penugasan_guru', function ($query) use ($pegawai) {
                    $query->where('ms_pegawai_id', $pegawai->ms_pegawai_id);
                })
                ->with($this->jadwalRelations())
                ->get()
            : collect();

        $filtered = $this->applyFilters($schedules);
        $matrixData = $this->matrixData($filtered, (string) $this->selectedHari);

        return view('livewire.akademik.jadwal-mengajar.saya', [
            'pegawai' => $pegawai,
            'summary' => $this->summarizeSchedules($schedules),
            'days' => $matrixData['days'],
            'slots' => $matrixData['slots'],
            'matrix' => $matrixData['matrix'],
            'totalFiltered' => $filtered->count(),
            'hariOptions' => $this->hariOptions(),
        ]);
    }

    private function applyFilters(Collection $schedules): Collection
    {
        $search = mb_strtolower(trim((string) $this->search));

        return $schedules
            ->filter(function ($jadwal) use ($search) {
                if ($this->selectedHari !== '' && $jadwal->hari !== $this->selectedHari) {
                    return false;
                }

                if ($search === '') {
                    return true;
                }

                $assignment = $jadwal->ms_penugasan_guru;

                $haystack = mb_strtolower(implode(' ', array_filter([
                    $assignment?->ms_kelas?->nama_kelas,
                    $assignment?->ms_mata_pelajaran?->nama_mapel,
                    $assignment?->ms_mata_pelajaran?->kode_mapel,
                    $assignment?->ms_periode_akademik?->nama_periode,
                    $jadwal->deskripsi,
                ])));

                return str_contains($haystack, $search);
            })
            ->sortBy([
                ['hari', 'asc'],
                ['jam_mulai', 'asc'],
            ])
            ->values();
    }
}
