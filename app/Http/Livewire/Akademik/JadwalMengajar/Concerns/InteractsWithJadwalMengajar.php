<?php

namespace App\Http\Livewire\Akademik\JadwalMengajar\Concerns;

use App\Http\Livewire\Akademik\PenugasanGuru\Concerns\InteractsWithPenugasanGuru;
use App\Models\JadwalMengajar;
use App\Models\PeriodeAkademik;
use App\Services\Akademik\JadwalMengajarConflictService;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

trait InteractsWithJadwalMengajar
{
    use InteractsWithPenugasanGuru;

    protected function hariOptions(): array
    {
        return [
            'SENIN' => 'Senin',
            'SELASA' => 'Selasa',
            'RABU' => 'Rabu',
            'KAMIS' => 'Kamis',
            'JUMAT' => 'Jumat',
            'SABTU' => 'Sabtu',
            'MINGGU' => 'Minggu',
        ];
    }

    protected function hariLabel(?string $hari): string
    {
        return $this->hariOptions()[strtoupper((string) $hari)] ?? '-';
    }

    protected function formatTime(?string $time): string
    {
        return $time ? substr($time, 0, 5) : '-';
    }

    protected function normalizeTime(?string $time): string
    {
        if (! $time) {
            return '';
        }

        return strlen($time) === 5 ? $time . ':00' : $time;
    }

    protected function hasValidPeriodeContext($jenjangId, $tahunAjarId, $periodeId): bool
    {
        return $this->hasValidContext($jenjangId, $tahunAjarId)
            && $periodeId
            && PeriodeAkademik::query()
                ->whereKey($periodeId)
                ->where('ms_jenjang_id', $jenjangId)
                ->where('ms_tahun_ajar_id', $tahunAjarId)
                ->where('status', 'AKTIF')
                ->exists();
    }

    protected function assertValidPeriodeContext($jenjangId, $tahunAjarId, $periodeId): void
    {
        if (! $this->hasValidPeriodeContext($jenjangId, $tahunAjarId, $periodeId)) {
            throw ValidationException::withMessages([
                'context' => 'Pilih jenjang, tahun ajar, dan periode akademik yang valid serta dapat diakses.',
            ]);
        }
    }

    protected function assignmentsForPeriodeContext($jenjangId, $tahunAjarId, $periodeId, bool $activeOnly = true)
    {
        return $this->assignmentsForContext($jenjangId, $tahunAjarId)
            ->where('ms_periode_akademik_id', $periodeId)
            ->when($activeOnly, fn ($query) => $query->where('status', 'AKTIF'));
    }

    protected function jadwalForContext($jenjangId, $tahunAjarId, $periodeId)
    {
        return JadwalMengajar::query()
            ->whereHas('ms_penugasan_guru', function ($query) use ($jenjangId, $tahunAjarId, $periodeId) {
                $query->where('ms_periode_akademik_id', $periodeId)
                    ->whereHas('ms_pegawai', fn ($related) => $related->where('ms_jenjang_id', $jenjangId))
                    ->whereHas('ms_kelas', function ($related) use ($jenjangId, $tahunAjarId) {
                        $related->where('ms_jenjang_id', $jenjangId)
                            ->where('ms_tahun_ajar_id', $tahunAjarId);
                    })
                    ->whereHas('ms_mata_pelajaran', fn ($related) => $related->where('ms_jenjang_id', $jenjangId));
            });
    }

    protected function jadwalRelations(): array
    {
        return [
            'ms_penugasan_guru.ms_pegawai',
            'ms_penugasan_guru.ms_kelas',
            'ms_penugasan_guru.ms_mata_pelajaran',
            'ms_penugasan_guru.ms_periode_akademik.ms_jenjang',
            'ms_penugasan_guru.ms_periode_akademik.ms_tahun_ajar',
        ];
    }

    protected function authorizeManage(): void
    {
        $peran = strtoupper(trim(auth()->user()->peran ?? ''));

        abort_unless(
            in_array($peran, ['SUPERADMIN', 'ADMIN', 'KEUANGAN', 'PETUGAS_PPDB'], true),
            403
        );
    }

    protected function resolveAssignment($jenjangId, $tahunAjarId, $periodeId, $assignmentId, bool $activeOnly = true)
    {
        return $this->assignmentsForPeriodeContext($jenjangId, $tahunAjarId, $periodeId, $activeOnly)
            ->with(['ms_pegawai', 'ms_kelas', 'ms_mata_pelajaran', 'ms_periode_akademik'])
            ->find($assignmentId);
    }

    protected function conflictService(): JadwalMengajarConflictService
    {
        return app(JadwalMengajarConflictService::class);
    }

    protected function summarizeSchedules(Collection $schedules): array
    {
        $today = now()->startOfDay();

        $summary = [
            'total' => $schedules->count(),
            'aktif' => 0,
            'belum_berlaku' => 0,
            'berakhir' => 0,
            'guru' => $schedules
                ->map(fn ($jadwal) => $jadwal->ms_penugasan_guru?->ms_pegawai_id)
                ->filter()
                ->unique()
                ->count(),
            'kelas' => $schedules
                ->map(fn ($jadwal) => $jadwal->ms_penugasan_guru?->ms_kelas_id)
                ->filter()
                ->unique()
                ->count(),
        ];

        foreach ($schedules as $jadwal) {
            if ($jadwal->status !== 'AKTIF') {
                continue;
            }

            $mulai = $jadwal->tanggal_mulai?->copy()->startOfDay();
            $selesai = $jadwal->tanggal_selesai?->copy()->endOfDay();

            if ($mulai && $mulai->greaterThan($today)) {
                $summary['belum_berlaku']++;
            } elseif ($selesai && $selesai->lessThan($today)) {
                $summary['berakhir']++;
            } else {
                $summary['aktif']++;
            }
        }

        return $summary;
    }

    protected function matrixData(Collection $schedules, string $selectedHari = ''): array
    {
        $days = array_keys($this->hariOptions());

        if ($selectedHari !== '') {
            $days = [$selectedHari];
        } else {
            $days = array_values(array_filter(
                $days,
                fn ($day) => $day !== 'MINGGU' || $schedules->where('hari', 'MINGGU')->isNotEmpty()
            ));
        }

        $slots = $schedules
            ->map(fn ($jadwal) => [
                'key' => $jadwal->jam_mulai . '|' . $jadwal->jam_selesai,
                'mulai' => $jadwal->jam_mulai,
                'selesai' => $jadwal->jam_selesai,
            ])
            ->unique('key')
            ->sortBy('mulai')
            ->values();

        $matrix = [];

        foreach ($schedules as $jadwal) {
            $matrix[$jadwal->jam_mulai . '|' . $jadwal->jam_selesai][$jadwal->hari][] = $jadwal;
        }

        return [
            'days' => $days,
            'slots' => $slots,
            'matrix' => $matrix,
        ];
    }
}
