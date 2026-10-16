<?php

namespace App\Http\Livewire\PPDB\Pertanyaan;

use App\Models\PPDBPeriode;
use App\Models\PPDBPertanyaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
    public $ppdb_periode_id;

    public $pertanyaan = '';
    public $jenis = 'teks';
    public $opsi_jawaban = [''];
    public $wajib_diisi = true;
    public $urutan = 1;
    public $status = 'aktif';

    protected $listeners = [
        'showCreatePertanyaan',
    ];

    /**
     * Menyiapkan form tambah pertanyaan.
     */
    public function showCreatePertanyaan($periodeId)
    {
        $this->resetValidation();
        $this->resetInput();

        $this->ppdb_periode_id = $periodeId;

        $urutanTerakhir = PPDBPertanyaan::query()
            ->where('ppdb_periode_id', $periodeId)
            ->max('urutan');

        $this->urutan = ($urutanTerakhir ?? 0) + 1;
    }

    /**
     * Menambahkan opsi jawaban.
     */
    public function addOpsi()
    {
        if (count($this->opsi_jawaban) < 20) {
            $this->opsi_jawaban[] = '';
        }
    }

    /**
     * Menghapus opsi jawaban.
     */
    public function removeOpsi($index)
    {
        if (count($this->opsi_jawaban) > 1) {
            unset($this->opsi_jawaban[$index]);

            $this->opsi_jawaban = array_values(
                $this->opsi_jawaban
            );

            $this->resetValidation('opsi_jawaban');
        }
    }

    /**
     * Aturan validasi form.
     */
    protected function rules()
    {
        $rules = [
            'ppdb_periode_id' => [
                'required',
                'integer',
                'exists:ppdb_periode,ppdb_periode_id',
            ],

            'pertanyaan' => [
                'required',
                'string',
                'max:2000',
            ],

            'jenis' => [
                'required',
                'in:teks,textarea,pilihan,ya_tidak,angka',
            ],

            'wajib_diisi' => [
                'required',
                'boolean',
            ],

            'urutan' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],
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

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    protected $messages = [
        'ppdb_periode_id.required' =>
            'Periode PPDB belum dipilih.',
        'ppdb_periode_id.integer' =>
            'Periode PPDB tidak valid.',
        'ppdb_periode_id.exists' =>
            'Periode PPDB yang dipilih tidak ditemukan.',

        'pertanyaan.required' =>
            'Pertanyaan wajib diisi.',
        'pertanyaan.string' =>
            'Pertanyaan harus berupa teks.',
        'pertanyaan.max' =>
            'Pertanyaan maksimal 2.000 karakter.',

        'jenis.required' =>
            'Jenis jawaban wajib dipilih.',
        'jenis.in' =>
            'Jenis jawaban tidak valid.',

        'opsi_jawaban.required_if' =>
            'Opsi jawaban wajib diisi untuk jenis pilihan.',
        'opsi_jawaban.array' =>
            'Format opsi jawaban tidak valid.',
        'opsi_jawaban.min' =>
            'Tambahkan minimal 2 opsi jawaban.',
        'opsi_jawaban.max' =>
            'Maksimal 20 opsi jawaban.',
        'opsi_jawaban.*.required_if' =>
            'Opsi jawaban tidak boleh kosong.',
        'opsi_jawaban.*.string' =>
            'Opsi jawaban harus berupa teks.',
        'opsi_jawaban.*.max' =>
            'Opsi jawaban maksimal 255 karakter.',

        'wajib_diisi.required' =>
            'Pengaturan wajib diisi harus ditentukan.',
        'wajib_diisi.boolean' =>
            'Pengaturan wajib diisi tidak valid.',

        'urutan.required' =>
            'Urutan tampil wajib diisi.',
        'urutan.integer' =>
            'Urutan tampil harus berupa bilangan bulat.',
        'urutan.min' =>
            'Urutan tampil minimal 1.',

        'status.required' =>
            'Status pertanyaan wajib dipilih.',
        'status.in' =>
            'Status pertanyaan tidak valid.',
    ];

    /**
     * Validasi input ketika diperbarui.
     */
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    /**
     * Simpan pertanyaan PPDB.
     */
    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Pastikan periode masih tersedia.
            $periode = PPDBPeriode::query()
                ->where(
                    'ppdb_periode_id',
                    $this->ppdb_periode_id
                )
                ->lockForUpdate()
                ->firstOrFail();

            // Periksa kembali opsi pilihan.
            $opsi = [];

            if ($this->jenis === 'pilihan') {
                $opsi = collect($this->opsi_jawaban)
                    ->map(function ($item) {
                        return trim($item);
                    })
                    ->filter()
                    ->values()
                    ->all();

                if (count($opsi) < 2) {
                    throw ValidationException::withMessages([
                        'opsi_jawaban' =>
                            'Minimal harus ada 2 opsi jawaban yang terisi.',
                    ]);
                }

                // Tolak opsi yang sama.
                if (count($opsi) !== count(array_unique(
                    array_map('mb_strtolower', $opsi)
                ))) {
                    throw ValidationException::withMessages([
                        'opsi_jawaban' =>
                            'Opsi jawaban tidak boleh duplikat.',
                    ]);
                }
            }

            PPDBPertanyaan::create([
                'ppdb_periode_id' => $periode->ppdb_periode_id,
                'pertanyaan' => trim($this->pertanyaan),
                'jenis' => $this->jenis,
                'opsi_jawaban' => $this->jenis === 'pilihan'
                    ? $opsi
                    : null,
                'wajib_diisi' => (bool) $this->wajib_diisi,
                'urutan' => $this->urutan,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Pertanyaan PPDB berhasil ditambahkan.',
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAddPertanyaan',
            ]);

            $this->resetInput();
            $this->resetValidation();

            $this->emit('refreshPertanyaans');
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' =>
                    'Pertanyaan PPDB gagal disimpan. Silakan coba kembali.',
            ]);
        }
    }

    /**
     * Reset seluruh input form.
     */
    public function resetInput()
    {
        $this->ppdb_periode_id = null;

        $this->pertanyaan = '';
        $this->jenis = 'teks';
        $this->opsi_jawaban = [''];
        $this->wajib_diisi = true;
        $this->urutan = 1;
        $this->status = 'aktif';
    }

    public function render()
    {
        return view('livewire.p-p-d-b.pertanyaan.create',[
            'periode' => $this->ppdb_periode_id
                ? PPDBPeriode::with([
                    'ms_jenjang',
                    'ms_tahun_ajar',
                ])->find($this->ppdb_periode_id)
                : null,
        ]);
    }
}
