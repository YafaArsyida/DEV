<?php

namespace App\Http\Livewire\PPDB\Pertanyaan;

use App\Models\PPDBPeriode;
use App\Models\PPDBPertanyaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    public $selectedPertanyaanId = null;

    public $ppdb_periode_id;
    public $pertanyaan;
    public $jenis = 'teks';
    public $opsi_jawaban = [''];
    public $wajib_diisi = true;
    public $urutan = 1;
    public $status = 'aktif';

    protected $listeners = [
        'loadDataPertanyaan' => 'loadData',
    ];

    /**
     * Memuat data pertanyaan yang akan diedit.
     */
    public function loadData($pertanyaanId)
    {
        $this->resetValidation();

        $pertanyaan = PPDBPertanyaan::find($pertanyaanId);

        if (!$pertanyaan) {
            $this->resetForm();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Data pertanyaan tidak ditemukan.',
            ]);

            return;
        }

        $this->selectedPertanyaanId = $pertanyaan->ppdb_pertanyaan_id;
        $this->ppdb_periode_id = $pertanyaan->ppdb_periode_id;
        $this->pertanyaan = $pertanyaan->pertanyaan;
        $this->jenis = $pertanyaan->jenis;
        $this->wajib_diisi = (bool) $pertanyaan->wajib_diisi;
        $this->urutan = $pertanyaan->urutan;
        $this->status = $pertanyaan->status;

        $opsi = $pertanyaan->opsi_jawaban;

        if (is_string($opsi)) {
            $opsi = json_decode($opsi, true);
        }

        $this->opsi_jawaban = $this->jenis === 'pilihan'
            ? (is_array($opsi) && count($opsi) ? array_values($opsi) : [''])
            : [''];

        $this->emit('editPertanyaanLoaded');
    }

    /**
     * Menambahkan opsi jawaban.
     */
    public function addOpsi()
    {
        if (count($this->opsi_jawaban) >= 20) {
            $this->dispatchBrowserEvent('alertify-warning', [
                'message' => 'Maksimal 20 opsi jawaban.',
            ]);

            return;
        }

        $this->opsi_jawaban[] = '';
    }

    /**
     * Menghapus opsi jawaban.
     */
    public function removeOpsi($index)
    {
        if (count($this->opsi_jawaban) <= 1) {
            $this->opsi_jawaban = [''];

            return;
        }

        unset($this->opsi_jawaban[$index]);

        $this->opsi_jawaban = array_values($this->opsi_jawaban);
    }

    protected function rules()
    {
        $rules = [
            'ppdb_periode_id' => [
                'required',
                'integer',
                'exists:ppdb_periode,ppdb_periode_id',
            ],
            'pertanyaan' => ['required', 'string', 'max:2000'],
            'jenis' => [
                'required',
                'in:teks,textarea,pilihan,ya_tidak,angka',
            ],
            'wajib_diisi' => ['required', 'boolean'],
            'urutan' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ];

        if ($this->jenis === 'pilihan') {
            $rules['opsi_jawaban'] = [
                'required',
                'array',
                'min:2',
                'max:20',
            ];

            $rules['opsi_jawaban.*'] = [
                'required',
                'string',
                'max:255',
            ];
        } else {
            $rules['opsi_jawaban'] = ['nullable', 'array'];
        }

        return $rules;
    }

    protected $messages = [
        'pertanyaan.required' => 'Teks pertanyaan wajib diisi.',
        'pertanyaan.max' => 'Teks pertanyaan maksimal 2000 karakter.',
        'jenis.required' => 'Jenis pertanyaan wajib dipilih.',
        'jenis.in' => 'Jenis pertanyaan tidak valid.',
        'opsi_jawaban.min' => 'Minimal harus ada 2 opsi jawaban.',
        'opsi_jawaban.max' => 'Maksimal 20 opsi jawaban.',
        'opsi_jawaban.*.required' => 'Opsi jawaban tidak boleh kosong.',
        'opsi_jawaban.*.max' => 'Setiap opsi maksimal 255 karakter.',
        'urutan.required' => 'Urutan pertanyaan wajib diisi.',
        'urutan.min' => 'Urutan pertanyaan minimal 1.',
        'status.required' => 'Status pertanyaan wajib dipilih.',
    ];

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Menyimpan perubahan pertanyaan.
     */
    public function update()
    {
        if (!$this->selectedPertanyaanId) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pilih pertanyaan yang akan diedit.',
            ]);

            return;
        }

        $validated = $this->validate($this->rules());

        DB::beginTransaction();

        try {
            $pertanyaan = PPDBPertanyaan::query()
                ->where('ppdb_pertanyaan_id', $this->selectedPertanyaanId)
                ->lockForUpdate()
                ->firstOrFail();

            // Periode pertanyaan tidak diubah melalui form edit.
            $validated['ppdb_periode_id'] = $pertanyaan->ppdb_periode_id;

            if ($this->jenis === 'pilihan') {
                $validated['opsi_jawaban'] = collect($this->opsi_jawaban)
                    ->map(fn ($opsi) => trim($opsi))
                    ->values()
                    ->all();
            } else {
                $validated['opsi_jawaban'] = null;
            }

            $pertanyaan->update([
                'ppdb_periode_id' => $validated['ppdb_periode_id'],
                'pertanyaan' => $validated['pertanyaan'],
                'jenis' => $validated['jenis'],
                'opsi_jawaban' => $validated['opsi_jawaban'],
                'wajib_diisi' => $validated['wajib_diisi'],
                'urutan' => $validated['urutan'],
                'status' => $validated['status'],
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Pertanyaan berhasil diperbarui.',
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalEditPertanyaan',
            ]);

            $this->emit('refreshPertanyaans');

            $this->resetForm();
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan saat memperbarui pertanyaan.',
            ]);
        }
    }

    /**
     * Mereset form edit.
     */
    private function resetForm()
    {
        $this->reset([
            'selectedPertanyaanId',
            'ppdb_periode_id',
            'pertanyaan',
            'jenis',
            'opsi_jawaban',
            'wajib_diisi',
            'urutan',
            'status',
        ]);

        $this->jenis = 'teks';
        $this->opsi_jawaban = [''];
        $this->wajib_diisi = true;
        $this->urutan = 1;
        $this->status = 'aktif';

        $this->resetValidation();
    }

    public function render()
    {
        $periode = null;

        if ($this->ppdb_periode_id) {
            $periode = PPDBPeriode::with([
                'ms_jenjang',
                'ms_tahun_ajar',
            ])->find($this->ppdb_periode_id);
        }

        return view('livewire.p-p-d-b.pertanyaan.edit',[
            'periode' => $periode,
        ]);
    }
}
