<?php

namespace App\Http\Livewire\Akademik\PenugasanGuru;

use App\Http\Livewire\Akademik\PenugasanGuru\Concerns\InteractsWithPenugasanGuru;
use App\Models\PenugasanGuru;
use App\Models\TahunAjar;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
    use InteractsWithPenugasanGuru;

    public $selectedJenjang = null;

    public $selectedTahunAjar = null;

    public $ms_pegawai_id = '';

    public $ms_kelas_id = '';

    public $ms_mata_pelajaran_id = '';

    public $ms_tahun_ajar_id = '';

    public $status = 'AKTIF';

    public $deskripsi = '';

    protected $listeners = ['showCreatePenugasanGuru'];

    protected $messages = [
        'ms_pegawai_id.required' => 'Pilih guru pengampu.',
        'ms_kelas_id.required' => 'Pilih kelas.',
        'ms_tahun_ajar_id.required' => 'Tahun ajaran belum dipilih.',
        'ms_tahun_ajar_id.exists' => 'Tahun ajaran yang dipilih tidak valid.',
        'status.in' => 'Status penugasan tidak valid.',
    ];

    public function showCreatePenugasanGuru($jenjang, $tahunAjar)
    {
        $this->resetValidation();
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->resetForm();
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

    public function save()
    {
        if ($this->ms_mata_pelajaran_id === '') {
            $this->ms_mata_pelajaran_id = null;
        }

        $validated = $this->validate();
        $validated['ms_mata_pelajaran_id'] = $validated['ms_mata_pelajaran_id'] ?? null;
        $this->assertEligibleSelections($validated, $this->selectedJenjang, $this->selectedTahunAjar);

        $existing = PenugasanGuru::withTrashed()
            ->where('ms_pegawai_id', $validated['ms_pegawai_id'])
            ->where('ms_kelas_id', $validated['ms_kelas_id'])
            ->where('ms_tahun_ajar_id', $validated['ms_tahun_ajar_id'])
            ->when(
                $validated['ms_mata_pelajaran_id'] !== null,
                fn ($query) => $query->where('ms_mata_pelajaran_id', $validated['ms_mata_pelajaran_id']),
                fn ($query) => $query->whereNull('ms_mata_pelajaran_id')
            )
            ->first();

        if ($existing && ! $existing->trashed()) {
            throw ValidationException::withMessages([
                'ms_mata_pelajaran_id' => 'Penugasan untuk guru, kelas, mata pelajaran, dan tahun ajaran ini sudah ada.',
            ]);
        }

        if ($existing) {
            $existing->restore();
            $existing->update($validated);
        } else {
            PenugasanGuru::create($validated);
        }

        $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalAddPenugasanGuru']);
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Penugasan guru berhasil ditambahkan.',
        ]);
        $this->emit('refreshPenugasanGurus');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'ms_pegawai_id',
            'ms_kelas_id',
            'ms_mata_pelajaran_id',
            'deskripsi',
        ]);
        $this->ms_tahun_ajar_id = $this->selectedTahunAjar;
        $this->status = 'AKTIF';
    }

    public function render()
    {
        $contextIsValid = $this->hasValidContext($this->selectedJenjang, $this->selectedTahunAjar);

        return view('livewire.akademik.penugasan-guru.create', [
            'teachers' => $contextIsValid ? $this->teacherOptions($this->selectedJenjang)->get() : collect(),
            'classes' => $contextIsValid ? $this->classOptions($this->selectedJenjang, $this->selectedTahunAjar)->get() : collect(),
            'subjects' => $contextIsValid ? $this->subjectOptions($this->selectedJenjang)->get() : collect(),
            'tahunAjar' => $contextIsValid ? TahunAjar::find($this->selectedTahunAjar) : null,
            'contextIsValid' => $contextIsValid,
        ]);
    }
}
