<?php

namespace App\Http\Livewire\PPDB\Jalur;

use App\Models\PPDBJalur;
use App\Models\PPDBPeriode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
     public $ppdb_periode_id;

    public $kode_jalur = '';
    public $nama_jalur = '';
    public $deskripsi = '';

    public $kuota = null;
    public $biaya_pendaftaran = 0;
    public $status = 'aktif';

    protected $listeners = [
        'showCreateJalur',
    ];

    /**
     * Menyiapkan form tambah jalur.
     */
    public function showCreateJalur($periodeId)
    {
        $this->resetValidation();
        $this->resetInput();

        $this->ppdb_periode_id = $periodeId;
    }

    /**
     * Aturan validasi form.
     */
    protected function rules()
    {
        return [
            'ppdb_periode_id' => [
                'required',
                'integer',
                'exists:ppdb_periode,ppdb_periode_id',
            ],

            'kode_jalur' => [
                'required',
                'string',
                'max:50',
                Rule::unique('ppdb_jalur', 'kode_jalur')
                    ->where('ppdb_periode_id', $this->ppdb_periode_id),
            ],

            'nama_jalur' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'kuota' => [
                'required',
                'integer',
                'min:1',
            ],

            'biaya_pendaftaran' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    protected $messages = [
        'ppdb_periode_id.required' => 'Periode PPDB belum dipilih.',
        'ppdb_periode_id.integer' => 'Periode PPDB tidak valid.',
        'ppdb_periode_id.exists' => 'Periode PPDB yang dipilih tidak ditemukan.',

        'kode_jalur.required' => 'Kode jalur wajib diisi.',
        'kode_jalur.string' => 'Kode jalur harus berupa teks.',
        'kode_jalur.max' =>
            'Kode jalur maksimal 50 karakter.',
        'kode_jalur.unique' =>
            'Kode jalur sudah digunakan pada periode PPDB ini.',

        'nama_jalur.required' =>
            'Nama jalur tidak boleh kosong.',
        'nama_jalur.string' =>
            'Nama jalur harus berupa teks.',
        'nama_jalur.max' =>
            'Nama jalur maksimal 255 karakter.',

        'deskripsi.string' =>
            'Deskripsi harus berupa teks.',
        'deskripsi.max' =>
            'Deskripsi maksimal 2.000 karakter.',

        'kuota.required' =>
            'Kuota penerimaan wajib diisi.',
        'kuota.integer' =>
            'Kuota harus berupa bilangan bulat.',
        'kuota.min' =>
            'Kuota minimal 1 siswa.',

        'biaya_pendaftaran.required' =>
            'Biaya pendaftaran wajib diisi.',
        'biaya_pendaftaran.numeric' =>
            'Biaya pendaftaran harus berupa angka.',
        'biaya_pendaftaran.min' =>
            'Biaya pendaftaran tidak boleh negatif.',
    ];

    /**
     * Validasi input ketika diperbarui.
     */
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    /**
     * Simpan jalur PPDB.
     */
    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Pastikan periode masih tersedia.
            $periode = PPDBPeriode::query()
                ->where(
                    'ppdb_periode_id', $this->ppdb_periode_id
                )
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan kode jalur belum digunakan pada periode ini.
            $kodeSudahDigunakan = PPDBJalur::query()
                ->where(
                    'ppdb_periode_id', $periode->ppdb_periode_id
                )
                ->where('kode_jalur', $this->kode_jalur)
                ->exists();

            if ($kodeSudahDigunakan) {
                throw ValidationException::withMessages([
                    'kode_jalur' => 'Kode jalur sudah digunakan pada periode PPDB ini.',
                ]);
            }

            PPDBJalur::create([
                'ppdb_periode_id' => $periode->ppdb_periode_id,
                'kode_jalur' => strtoupper(trim($this->kode_jalur)),
                'nama_jalur' => trim($this->nama_jalur),
                'deskripsi' => $this->deskripsi ?: null,
                'kuota' => $this->kuota,
                'biaya_pendaftaran' => $this->biaya_pendaftaran,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Jalur PPDB berhasil ditambahkan.',
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAddJalur',
            ]);

            $this->resetInput();
            $this->resetValidation();

            $this->emit('refreshJalurs');
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jalur PPDB gagal disimpan. Silakan coba kembali.',
            ]);
        }
    }

    /**
     * Reset seluruh input form.
     */
    public function resetInput()
    {
        $this->ppdb_periode_id = null;

        $this->kode_jalur = '';
        $this->nama_jalur = '';
        $this->deskripsi = '';

        $this->kuota = null;
        $this->biaya_pendaftaran = 0;
        $this->status = 'aktif';
    }
    public function render()
    {
        return view('livewire.p-p-d-b.jalur.create',[
            'periode' => $this->ppdb_periode_id
                ? PPDBPeriode::find($this->ppdb_periode_id)
                : null,
        ]);
    }
}
