<?php

namespace App\Http\Livewire\Akademik\PenugasanGuru\Concerns;

use App\Http\Controllers\HelperController;
use App\Models\Jabatan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pegawai;
use App\Models\PenugasanGuru;
use App\Models\TahunAjar;
use Illuminate\Validation\ValidationException;

trait InteractsWithPenugasanGuru
{
    protected function allowedJenjangIds(): array
    {
        return auth()->user()->ms_jenjang()
            ->pluck('ms_jenjang.ms_jenjang_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    protected function hasValidContext($jenjangId, $tahunAjarId): bool
    {
        return $jenjangId
            && in_array((int) $jenjangId, $this->allowedJenjangIds(), true)
            && TahunAjar::query()
                ->whereKey($tahunAjarId)
                ->where('status', 'Aktif')
                ->exists();
    }

    protected function teacherOptions($jenjangId, bool $activeOnly = true)
    {
        $guruJabatanIds = Jabatan::query()
            ->get()
            ->filter(fn (Jabatan $jabatan) => HelperController::mapRoleDariJabatan($jabatan->ms_jabatan_id) === 'GURU')
            ->modelKeys();

        return Pegawai::query()
            ->where('ms_jenjang_id', $jenjangId)
            ->when($activeOnly, fn ($query) => $query->where('status', 'Aktif'))
            ->where(function ($query) use ($guruJabatanIds) {
                $query->where('jenis_pegawai', 'GURU');

                if ($guruJabatanIds) {
                    $query->orWhereIn('ms_jabatan_id', $guruJabatanIds);
                }
            })
            ->orderBy('nama_pegawai');
    }

    protected function classOptions($jenjangId, $tahunAjarId)
    {
        return Kelas::query()
            ->where('ms_jenjang_id', $jenjangId)
            ->where('ms_tahun_ajar_id', $tahunAjarId)
            ->orderBy('urutan')
            ->orderBy('nama_kelas');
    }

    protected function subjectOptions($jenjangId, bool $activeOnly = true)
    {
        return MataPelajaran::query()
            ->where('ms_jenjang_id', $jenjangId)
            ->when($activeOnly, fn ($query) => $query->where('status', 'AKTIF'))
            ->orderBy('urutan')
            ->orderBy('nama_mapel');
    }

    protected function assignmentsForContext($jenjangId, $tahunAjarId)
    {
        return PenugasanGuru::query()
            ->whereHas('ms_pegawai', fn ($query) => $query->where('ms_jenjang_id', $jenjangId))
            ->whereHas('ms_kelas', function ($query) use ($jenjangId, $tahunAjarId) {
                $query->where('ms_jenjang_id', $jenjangId)
                    ->where('ms_tahun_ajar_id', $tahunAjarId);
            })
            ->where(function ($query) use ($jenjangId) {
                $query->whereNull('ms_mata_pelajaran_id')
                    ->orWhereHas('ms_mata_pelajaran', fn ($related) => $related->where('ms_jenjang_id', $jenjangId));
            });
    }

    protected function assertValidContext($jenjangId, $tahunAjarId): void
    {
        if (! $this->hasValidContext($jenjangId, $tahunAjarId)) {
            throw ValidationException::withMessages([
                'context' => 'Pilih jenjang dan tahun ajar yang valid dan memiliki akses.',
            ]);
        }
    }

    protected function assertEligibleSelections(array $data, $jenjangId, $tahunAjarId, ?PenugasanGuru $existing = null): void
    {
        $this->assertValidContext($jenjangId, $tahunAjarId);

        $keepTeacher = $existing && (int) $existing->ms_pegawai_id === (int) $data['ms_pegawai_id'];
        if (! $this->teacherOptions($jenjangId, ! $keepTeacher)->whereKey($data['ms_pegawai_id'])->exists()) {
            throw ValidationException::withMessages([
                'ms_pegawai_id' => 'Guru tidak aktif atau bukan bagian dari jenjang yang dipilih.',
            ]);
        }

        if (! $this->classOptions($jenjangId, $tahunAjarId)->whereKey($data['ms_kelas_id'])->exists()) {
            throw ValidationException::withMessages([
                'ms_kelas_id' => 'Kelas tidak termasuk dalam jenjang dan tahun ajar yang dipilih.',
            ]);
        }

        $subjectId = $data['ms_mata_pelajaran_id'] ?? null;

        if ($subjectId !== null && $subjectId !== '') {
            $keepSubject = $existing && (int) $existing->ms_mata_pelajaran_id === (int) $subjectId;

            if (! $this->subjectOptions($jenjangId, ! $keepSubject)->whereKey($subjectId)->exists()) {
                throw ValidationException::withMessages([
                    'ms_mata_pelajaran_id' => 'Mata pelajaran tidak aktif atau bukan bagian dari jenjang yang dipilih.',
                ]);
            }
        }
    }
}
