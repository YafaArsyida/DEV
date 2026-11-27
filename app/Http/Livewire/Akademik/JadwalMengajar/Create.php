<?php

namespace App\Http\Livewire\Akademik\JadwalMengajar;

use App\Http\Livewire\Akademik\JadwalMengajar\Concerns\InteractsWithJadwalMengajar;
use App\Models\JadwalMengajar;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use InteractsWithJadwalMengajar;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedPeriode = null;

    public $ms_penugasan_guru_id = '';
    public $hari = 'SENIN';
    public $jam_mulai = '';
    public $jam_selesai = '';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $status = 'AKTIF';
    public $deskripsi = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'showCreateJadwal' => 'showCreateJadwal',
    ];

    protected $messages = [
        'ms_penugasan_guru_id.required' => 'Pilih penugasan guru.',
        'ms_penugasan_guru_id.exists' => 'Penugasan guru tidak ditemukan.',
        'hari.required' => 'Pilih hari.',
        'hari.in' => 'Hari tidak valid.',
        'jam_mulai.required' => 'Jam mulai wajib diisi.',
        'jam_mulai.date_format' => 'Format jam mulai tidak valid.',
        'jam_selesai.required' => 'Jam selesai wajib diisi.',
        'jam_selesai.date_format' => 'Format jam selesai tidak valid.',
        'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
        'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
        'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.',
        'status.in' => 'Status jadwal tidak valid.',
        'deskripsi.max' => 'Keterangan maksimal 1000 karakter.',
    ];

    public function updateParameters($jenjangId = null, $tahunAjarId = null, $periodeId = null)
    {
        $this->selectedJenjang = $jenjangId;
        $this->selectedTahunAjar = $tahunAjarId;
        $this->selectedPeriode = $periodeId;
    }

    public function showCreateJadwal($jenjangId = null, $tahunAjarId = null, $periodeId = null)
    {
        $this->resetValidation();

        if ($jenjangId !== null || $tahunAjarId !== null || $periodeId !== null) {
            $this->selectedJenjang = $jenjangId;
            $this->selectedTahunAjar = $tahunAjarId;
            $this->selectedPeriode = $periodeId;
        }

        $this->resetForm();
    }

    protected function rules(): array
    {
        return [
            'ms_penugasan_guru_id' => [
                'required',
                'integer',
                Rule::exists('ms_penugasan_guru', 'ms_penugasan_guru_id')->whereNull('deleted_at'),
            ],
            'hari' => ['required', Rule::in(array_keys($this->hariOptions()))],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', Rule::in(['AKTIF', 'NONAKTIF'])],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function save()
    {
        $this->authorizeManage();
        $validated = $this->validate();
        $this->assertValidPeriodeContext($this->selectedJenjang, $this->selectedTahunAjar, $this->selectedPeriode);

        $assignment = $this->resolveAssignment(
            $this->selectedJenjang,
            $this->selectedTahunAjar,
            $this->selectedPeriode,
            $validated['ms_penugasan_guru_id'],
            true
        );

        if (! $assignment) {
            $this->addError('ms_penugasan_guru_id', 'Penugasan tidak ditemukan, tidak aktif, atau tidak sesuai parameter akademik.');

            return;
        }

        $conflicts = $this->conflictService()->detect($assignment, $validated);

        if ($conflicts['teacher'] || $conflicts['class']) {
            $message = $conflicts['teacher']
                ? $this->conflictService()->describe($conflicts['teacher'], 'teacher')
                : $this->conflictService()->describe($conflicts['class'], 'class');

            $this->addError('jadwal', $message);
            $this->dispatchBrowserEvent('alertify-error', ['message' => $message]);

            return;
        }

        JadwalMengajar::create([
            'ms_penugasan_guru_id' => $validated['ms_penugasan_guru_id'],
            'hari' => $validated['hari'],
            'jam_mulai' => $this->normalizeTime($validated['jam_mulai']),
            'jam_selesai' => $this->normalizeTime($validated['jam_selesai']),
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'status' => $validated['status'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalAddJadwal']);
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Jadwal mengajar berhasil ditambahkan.',
        ]);
        $this->emit('refreshJadwalMengajar');
        $this->resetForm();
    }

    public function render()
    {
        $contextIsValid = $this->hasValidPeriodeContext(
            $this->selectedJenjang,
            $this->selectedTahunAjar,
            $this->selectedPeriode
        );

        $assignments = $contextIsValid
            ? $this->assignmentsForPeriodeContext(
                $this->selectedJenjang,
                $this->selectedTahunAjar,
                $this->selectedPeriode
            )
                ->with(['ms_pegawai', 'ms_kelas', 'ms_mata_pelajaran', 'ms_periode_akademik'])
                ->get()
                ->sortBy(fn ($assignment) => $assignment->ms_pegawai?->nama_pegawai ?? '')
                ->values()
            : collect();

        return view('livewire.akademik.jadwal-mengajar.create', [
            'contextIsValid' => $contextIsValid,
            'assignments' => $assignments,
            'hariOptions' => $this->hariOptions(),
        ]);
    }

    private function resetForm()
    {
        $this->reset([
            'ms_penugasan_guru_id',
            'jam_mulai',
            'jam_selesai',
            'tanggal_mulai',
            'tanggal_selesai',
            'deskripsi',
        ]);

        $this->hari = 'SENIN';
        $this->status = 'AKTIF';
    }
}
