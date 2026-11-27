<?php

namespace App\Http\Livewire\Akademik\JadwalMengajar;

use App\Http\Livewire\Akademik\JadwalMengajar\Concerns\InteractsWithJadwalMengajar;
use App\Models\Jenjang;
use App\Models\PeriodeAkademik;
use App\Models\TahunAjar;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    use InteractsWithJadwalMengajar;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedPeriode = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';
    public $namaPeriode = '';

    public $modeTampilan = 'kelas';
    public $selectedKelas = null;
    public $selectedGuru = null;
    public $selectedHari = '';
    public $search = '';

    public $detailId = null;
    public $detail = [];

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshJadwalMengajar' => '$refresh',
    ];

    public function updateParameters($jenjangId = null, $tahunAjarId = null, $periodeId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;
        $this->selectedPeriode = $periodeId;

        $this->namaJenjang = $jenjangId
            ? Jenjang::whereKey($jenjangId)->value('nama_jenjang') ?? ''
            : '';
        $this->namaTahunAjar = $tahunAjarId
            ? TahunAjar::whereKey($tahunAjarId)->value('nama_tahun_ajar') ?? ''
            : '';
        $this->namaPeriode = $periodeId
            ? PeriodeAkademik::whereKey($periodeId)->value('nama_periode') ?? ''
            : '';

        $this->resetFilter();
    }

    public function setModeTampilan($mode)
    {
        if (! in_array($mode, ['kelas', 'guru', 'semua'], true)) {
            return;
        }

        $this->modeTampilan = $mode;
        $this->selectedKelas = null;
        $this->selectedGuru = null;
        $this->search = '';
    }

    public function setHari($hari)
    {
        $this->selectedHari = array_key_exists($hari, $this->hariOptions()) ? $hari : '';
    }

    public function resetFilter()
    {
        $this->search = '';
        $this->selectedHari = '';
        $this->selectedKelas = null;
        $this->selectedGuru = null;
    }

    public function showDetail($id)
    {
        if (! $this->hasValidPeriodeContext($this->selectedJenjang, $this->selectedTahunAjar, $this->selectedPeriode)) {
            return;
        }

        $jadwal = $this->jadwalForContext(
            $this->selectedJenjang,
            $this->selectedTahunAjar,
            $this->selectedPeriode
        )->with($this->jadwalRelations())->find($id);

        if (! $jadwal) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jadwal tidak ditemukan pada parameter akademik yang dipilih.',
            ]);

            return;
        }

        $assignment = $jadwal->ms_penugasan_guru;

        $this->detailId = $jadwal->ej_jadwal_mengajar_id;
        $this->detail = [
            'guru' => $assignment?->ms_pegawai?->nama_pegawai ?? '-',
            'nip' => $assignment?->ms_pegawai?->nip ?: 'NIP belum diisi',
            'kelas' => $assignment?->ms_kelas?->nama_kelas ?? '-',
            'mapel' => $assignment?->ms_mata_pelajaran?->nama_mapel ?? '-',
            'kode_mapel' => $assignment?->ms_mata_pelajaran?->kode_mapel ?: 'Tanpa kode',
            'jenjang' => $assignment?->ms_periode_akademik?->ms_jenjang?->nama_jenjang ?? '-',
            'tahun_ajar' => $assignment?->ms_periode_akademik?->ms_tahun_ajar?->nama_tahun_ajar ?? '-',
            'periode' => $assignment?->ms_periode_akademik?->nama_periode ?? '-',
            'penugasan_status' => $assignment?->status === 'AKTIF' ? 'Aktif' : 'Nonaktif',
            'hari' => $this->hariLabel($jadwal->hari),
            'jam' => $this->formatTime($jadwal->jam_mulai) . ' - ' . $this->formatTime($jadwal->jam_selesai),
            'tanggal' => ($jadwal->tanggal_mulai?->format('d M Y') ?? '-')
                . ' - '
                . ($jadwal->tanggal_selesai?->format('d M Y') ?? '-'),
            'status' => $jadwal->status,
            'status_label' => $jadwal->status === 'AKTIF' ? 'Aktif' : 'Nonaktif',
            'deskripsi' => $jadwal->deskripsi ?: '-',
        ];

        $this->dispatchBrowserEvent('show-modal', ['modalId' => 'ModalDetailJadwal']);
    }

    public function render()
    {
        $hasParameters = $this->hasValidPeriodeContext(
            $this->selectedJenjang,
            $this->selectedTahunAjar,
            $this->selectedPeriode
        );

        $contextSchedules = $hasParameters
            ? $this->jadwalForContext(
                $this->selectedJenjang,
                $this->selectedTahunAjar,
                $this->selectedPeriode
            )->with($this->jadwalRelations())->get()
            : collect();

        $schedules = $this->applyFilters($contextSchedules);
        $matrixData = $this->matrixData($schedules, (string) $this->selectedHari);

        $teacherOptions = $contextSchedules
            ->map(fn ($jadwal) => $jadwal->ms_penugasan_guru?->ms_pegawai)
            ->filter()
            ->unique('ms_pegawai_id')
            ->sortBy('nama_pegawai')
            ->values();

        $classOptions = $contextSchedules
            ->map(fn ($jadwal) => $jadwal->ms_penugasan_guru?->ms_kelas)
            ->filter()
            ->unique('ms_kelas_id')
            ->sortBy('nama_kelas')
            ->values();

        $canManage = in_array(
            strtoupper(trim(auth()->user()->peran ?? '')),
            ['SUPERADMIN', 'ADMIN', 'KEUANGAN', 'PETUGAS_PPDB'],
            true
        );

        return view('livewire.akademik.jadwal-mengajar.index', [
            'hasParameters' => $hasParameters,
            'summary' => $this->summarizeSchedules($contextSchedules),
            'teacherOptions' => $teacherOptions,
            'classOptions' => $classOptions,
            'days' => $matrixData['days'],
            'slots' => $matrixData['slots'],
            'matrix' => $matrixData['matrix'],
            'totalFiltered' => $schedules->count(),
            'hariOptions' => $this->hariOptions(),
            'canManage' => $canManage,
        ]);
    }

    private function applyFilters(Collection $schedules): Collection
    {
        $search = mb_strtolower(trim((string) $this->search));

        return $schedules
            ->filter(function ($jadwal) use ($search) {
                $assignment = $jadwal->ms_penugasan_guru;

                if ($this->modeTampilan === 'kelas'
                    && $this->selectedKelas
                    && (int) $assignment?->ms_kelas_id !== (int) $this->selectedKelas) {
                    return false;
                }

                if ($this->modeTampilan === 'guru'
                    && $this->selectedGuru
                    && (int) $assignment?->ms_pegawai_id !== (int) $this->selectedGuru) {
                    return false;
                }

                if ($this->selectedHari !== '' && $jadwal->hari !== $this->selectedHari) {
                    return false;
                }

                if ($search === '') {
                    return true;
                }

                $haystack = mb_strtolower(implode(' ', array_filter([
                    $assignment?->ms_pegawai?->nama_pegawai,
                    $assignment?->ms_pegawai?->nip,
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
