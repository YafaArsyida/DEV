<?php

namespace App\Http\Livewire\Akademik\PenugasanGuru;

use App\Http\Livewire\Akademik\PenugasanGuru\Concerns\InteractsWithPenugasanGuru;
use App\Models\MataPelajaran;
use App\Models\Pegawai;
use App\Models\PenugasanGuru;
use App\Models\TahunAjar;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    use InteractsWithPenugasanGuru;

    public $penugasanGuruId;

    public $selectedJenjang = null;

    public $selectedTahunAjar = null;

    public $ms_pegawai_id = '';

    public $ms_kelas_id = '';

    public $ms_mata_pelajaran_id = '';

    public $ms_tahun_ajar_id = '';

    public $status = 'AKTIF';

    public $deskripsi = '';

    protected $listeners = ['loadPenugasanGuru'];

    public function loadPenugasanGuru($id, $jenjang, $tahunAjar)
    {
        $this->resetValidation();
        $this->assertValidContext($jenjang, $tahunAjar);
        $assignment = $this->assignmentsForContext($jenjang, $tahunAjar)->findOrFail($id);

        $this->penugasanGuruId = $assignment->ms_penugasan_guru_id;
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->ms_pegawai_id = $assignment->ms_pegawai_id;
        $this->ms_kelas_id = $assignment->ms_kelas_id;
        $this->ms_mata_pelajaran_id = $assignment->ms_mata_pelajaran_id ?? '';
        $this->ms_tahun_ajar_id = $assignment->ms_tahun_ajar_id ?: $tahunAjar;
        $this->status = $assignment->status;
        $this->deskripsi = $assignment->deskripsi;
    }

    protected function rules(): array
    {
        return [
            'ms_pegawai_id' => ['required', 'integer', Rule::exists('ms_pegawai', 'ms_pegawai_id')->whereNull('deleted_at')],
            'ms_kelas_id' => ['required', 'integer', Rule::exists('ms_kelas', 'ms_kelas_id')->whereNull('deleted_at')],
            'ms_mata_pelajaran_id' => ['nullable', 'integer', Rule::exists('ms_mata_pelajaran', 'ms_mata_pelajaran_id')->whereNull('deleted_at')],
            'ms_tahun_ajar_id' => ['required', 'integer', Rule::exists('ms_tahun_ajar', 'ms_tahun_ajar_id')->whereNull('deleted_at')],
            'status' => ['required', Rule::in(['AKTIF', 'NONAKTIF'])],
            'deskripsi' => 'nullable|string|max:1000',
        ];
    }

    public function updatePenugasanGuru()
    {
        abort_unless($this->penugasanGuruId, 404);

        if ($this->ms_mata_pelajaran_id === '') {
            $this->ms_mata_pelajaran_id = null;
        }

        $validated = $this->validate();
        $validated['ms_mata_pelajaran_id'] = $validated['ms_mata_pelajaran_id'] ?? null;
        $assignment = $this->assignmentsForContext($this->selectedJenjang, $this->selectedTahunAjar)
            ->findOrFail($this->penugasanGuruId);
        $this->assertEligibleSelections($validated, $this->selectedJenjang, $this->selectedTahunAjar, $assignment);

        $duplicate = PenugasanGuru::withTrashed()
            ->where('ms_pegawai_id', $validated['ms_pegawai_id'])
            ->where('ms_kelas_id', $validated['ms_kelas_id'])
            ->where('ms_tahun_ajar_id', $validated['ms_tahun_ajar_id'])
            ->when(
                $validated['ms_mata_pelajaran_id'] !== null,
                fn ($query) => $query->where('ms_mata_pelajaran_id', $validated['ms_mata_pelajaran_id']),
                fn ($query) => $query->whereNull('ms_mata_pelajaran_id')
            )
            ->where('ms_penugasan_guru_id', '!=', $assignment->ms_penugasan_guru_id)
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'ms_mata_pelajaran_id' => 'Kombinasi guru, kelas, mata pelajaran, dan tahun ajaran ini sudah pernah digunakan.',
            ]);
        }

        $assignment->update($validated);

        $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalEditPenugasanGuru']);
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Penugasan guru berhasil diperbarui.',
        ]);
        $this->emit('refreshPenugasanGurus');
    }

    public function render()
    {
        $contextIsValid = $this->hasValidContext($this->selectedJenjang, $this->selectedTahunAjar);
        $teachers = $contextIsValid ? $this->teacherOptions($this->selectedJenjang)->get() : collect();
        $subjects = $contextIsValid ? $this->subjectOptions($this->selectedJenjang)->get() : collect();

        if ($contextIsValid && $this->penugasanGuruId) {
            $currentTeacher = Pegawai::query()
                ->whereKey($this->ms_pegawai_id)
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->first();
            $currentSubject = $this->ms_mata_pelajaran_id
                ? MataPelajaran::query()
                    ->whereKey($this->ms_mata_pelajaran_id)
                    ->where('ms_jenjang_id', $this->selectedJenjang)
                    ->first()
                : null;

            if ($currentTeacher && ! $teachers->contains('ms_pegawai_id', $currentTeacher->ms_pegawai_id)) {
                $teachers->prepend($currentTeacher);
            }
            if ($currentSubject && ! $subjects->contains('ms_mata_pelajaran_id', $currentSubject->ms_mata_pelajaran_id)) {
                $subjects->prepend($currentSubject);
            }
        }

        return view('livewire.akademik.penugasan-guru.edit', [
            'teachers' => $teachers,
            'classes' => $contextIsValid ? $this->classOptions($this->selectedJenjang, $this->selectedTahunAjar)->get() : collect(),
            'subjects' => $subjects,
            'tahunAjar' => $contextIsValid ? TahunAjar::find($this->ms_tahun_ajar_id ?: $this->selectedTahunAjar) : null,
            'contextIsValid' => $contextIsValid,
        ]);
    }
}
