<?php

namespace App\Http\Livewire\PPDB\Jalur;

use App\Models\PPDBJalur;
use App\Models\PPDBPeriode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    public $ppdb_jalur_id;
    public $ppdb_periode_id;

    public $kode_jalur = '';
    public $nama_jalur = '';
    public $deskripsi = '';
    public $kuota = null;
    public $biaya_pendaftaran = 0;
    public $status = 'Aktif';

    protected $listeners = [
        'loadDataJalur' => 'loadJalur',
    ];

    /**
     * Memuat data jalur untuk diedit.
     */
    public function loadJalur($id)
    {
        $jalur = PPDBJalur::findOrFail($id);

        $this->resetValidation();

        $this->ppdb_jalur_id = $jalur->ppdb_jalur_id;
        $this->ppdb_periode_id = $jalur->ppdb_periode_id;
        $this->kode_jalur = $jalur->kode_jalur;
        $this->nama_jalur = $jalur->nama_jalur;
        $this->deskripsi = $jalur->deskripsi ?? '';
        $this->kuota = $jalur->kuota;
        $this->biaya_pendaftaran = $jalur->biaya_pendaftaran;
        $this->status = $jalur->status;
    }

    /**
     * Aturan validasi form.
     */
    protected function rules()
    {
        return [
            'ppdb_jalur_id' => [
                'required',
                'integer',
                'exists:ppdb_jalur,ppdb_jalur_id',
            ],

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
                    ->where('ppdb_periode_id', $this->ppdb_periode_id)
                    ->ignore($this->ppdb_jalur_id, 'ppdb_jalur_id'),
            ],

            'nama_jalur' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'kuota' => 'required|integer|min:1',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'status' => 'required|in:Aktif,Non Aktif',
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    protected $messages = [
        'ppdb_jalur_id.required' => 'Data jalur belum dipilih.',
        'ppdb_jalur_id.exists' => 'Jalur PPDB tidak ditemukan.',

        'ppdb_periode_id.required' => 'Periode PPDB wajib dipilih.',
        'ppdb_periode_id.exists' => 'Periode PPDB tidak ditemukan.',

        'kode_jalur.required' => 'Kode jalur wajib diisi.',
        'kode_jalur.max' => 'Kode jalur maksimal 50 karakter.',
        'kode_jalur.unique' => 'Kode jalur sudah digunakan pada periode PPDB ini.',

        'nama_jalur.required' => 'Nama jalur wajib diisi.',
        'nama_jalur.max' => 'Nama jalur maksimal 255 karakter.',

        'deskripsi.max' => 'Deskripsi maksimal 2.000 karakter.',

        'kuota.required' => 'Kuota penerimaan wajib diisi.',
        'kuota.integer' => 'Kuota harus berupa bilangan bulat.',
        'kuota.min' => 'Kuota minimal 1 siswa.',

        'biaya_pendaftaran.required' => 'Biaya pendaftaran wajib diisi.',
        'biaya_pendaftaran.numeric' => 'Biaya pendaftaran harus berupa angka.',
        'biaya_pendaftaran.min' => 'Biaya pendaftaran tidak boleh negatif.',

        'status.required' => 'Status jalur wajib dipilih.',
        'status.in' => 'Status jalur tidak valid.',
    ];

    /**
     * Validasi input saat diperbarui.
     */
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    /**
     * Simpan perubahan jalur.
     */
    public function updateJalur()
    {
        // Normalisasi kode agar konsisten saat disimpan.
        $this->kode_jalur = strtoupper(trim($this->kode_jalur));

        $this->validate();

        DB::beginTransaction();

        try {
            $jalur = PPDBJalur::where(
                'ppdb_jalur_id',
                $this->ppdb_jalur_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan jalur tidak berpindah periode melalui request.
            if ((int) $jalur->ppdb_periode_id !== (int) $this->ppdb_periode_id) {
                throw ValidationException::withMessages([
                    'ppdb_periode_id' => 'Periode jalur tidak boleh diubah.',
                ]);
            }

            // Periksa ulang duplikasi kode dalam periode yang sama.
            $kodeSudahDigunakan = PPDBJalur::where(
                'ppdb_periode_id',
                $jalur->ppdb_periode_id
            )
                ->where('kode_jalur', $this->kode_jalur)
                ->where('ppdb_jalur_id', '!=', $jalur->ppdb_jalur_id)
                ->exists();

            if ($kodeSudahDigunakan) {
                throw ValidationException::withMessages([
                    'kode_jalur' => 'Kode jalur sudah digunakan pada periode PPDB ini.',
                ]);
            }

            $jalur->update([
                'kode_jalur' => $this->kode_jalur,
                'nama_jalur' => trim($this->nama_jalur),
                'deskripsi' => filled($this->deskripsi)
                    ? trim($this->deskripsi)
                    : null,
                'kuota' => $this->kuota,
                'biaya_pendaftaran' => $this->biaya_pendaftaran,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalEditJalur',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Jalur PPDB berhasil diperbarui.',
            ]);

            $this->emit('refreshJalurs');
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jalur PPDB gagal diperbarui. Silakan coba kembali.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.p-p-d-b.jalur.edit',[
            'periode' => $this->ppdb_periode_id
                ? PPDBPeriode::find($this->ppdb_periode_id)
                : null,
        ]);
    }
}
