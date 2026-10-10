<?php

namespace App\Http\Livewire\PPDB\Gelombang;

use App\Models\PPDBGelombang;
use App\Models\PPDBPeriode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
     public $ppdb_gelombang_id;

    public $ppdb_periode_id;

    public $nama_gelombang = '';

    public $tanggal_mulai = '';

    public $tanggal_selesai = '';

    public $kuota = null;

    public $biaya_pendaftaran = 0;

    public $status = 'aktif';

    protected $listeners = [
        'loadDataGelombang' => 'loadGelombang',
    ];

    /**
     * Memuat data gelombang untuk diedit.
     */
    public function loadGelombang($id)
    {
        $gelombang = PPDBGelombang::findOrFail($id);

        $this->resetValidation();

        $this->ppdb_gelombang_id = $gelombang->ppdb_gelombang_id;
        $this->ppdb_periode_id = $gelombang->ppdb_periode_id;
        $this->nama_gelombang = $gelombang->nama_gelombang;

        $this->tanggal_mulai = $gelombang->tanggal_mulai
            ? \Carbon\Carbon::parse($gelombang->tanggal_mulai)->format('Y-m-d')
            : '';

        $this->tanggal_selesai = $gelombang->tanggal_selesai
            ? \Carbon\Carbon::parse($gelombang->tanggal_selesai)->format('Y-m-d')
            : '';

        $this->kuota = $gelombang->kuota;
        $this->biaya_pendaftaran = $gelombang->biaya_pendaftaran;
        $this->status = $gelombang->status;
    }

    /**
     * Aturan validasi form.
     */
    protected function rules()
    {
        return [
            'ppdb_gelombang_id' => [
                'required',
                'integer',
                'exists:ppdb_gelombang,ppdb_gelombang_id',
            ],
            'ppdb_periode_id' => [
                'required',
                'integer',
                'exists:ppdb_periode,ppdb_periode_id',
            ],
            'nama_gelombang' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'kuota' => 'required|integer|min:1',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    protected $messages = [
        'ppdb_gelombang_id.required' => 'Data gelombang belum dipilih.',
        'ppdb_gelombang_id.exists' => 'Gelombang PPDB tidak ditemukan.',
        'ppdb_periode_id.required' => 'Periode PPDB wajib dipilih.',
        'ppdb_periode_id.exists' => 'Periode PPDB tidak ditemukan.',

        'nama_gelombang.required' => 'Nama gelombang wajib diisi.',
        'nama_gelombang.max' => 'Nama gelombang maksimal 255 karakter.',

        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
        'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
        'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
        'tanggal_selesai.after_or_equal' =>
            'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',

        'kuota.required' => 'Kuota penerimaan wajib diisi.',
        'kuota.integer' => 'Kuota harus berupa bilangan bulat.',
        'kuota.min' => 'Kuota minimal 1 siswa.',

        'biaya_pendaftaran.required' => 'Biaya pendaftaran wajib diisi.',
        'biaya_pendaftaran.numeric' => 'Biaya pendaftaran harus berupa angka.',
        'biaya_pendaftaran.min' => 'Biaya pendaftaran tidak boleh negatif.',

        'status.required' => 'Status gelombang wajib dipilih.',
        'status.in' => 'Status gelombang tidak valid.',
    ];

    /**
     * Validasi input saat diperbarui.
     */
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    /**
     * Simpan perubahan gelombang.
     */
    public function updateGelombang()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $gelombang = PPDBGelombang::where(
                'ppdb_gelombang_id',
                $this->ppdb_gelombang_id
            )->lockForUpdate()->firstOrFail();

            $periode = PPDBPeriode::findOrFail(
                $gelombang->ppdb_periode_id
            );

            // Gelombang harus berada dalam rentang periode PPDB.
            $tanggalMulaiPeriode = \Carbon\Carbon::parse(
                $periode->tanggal_mulai
            )->format('Y-m-d');

            $tanggalSelesaiPeriode = \Carbon\Carbon::parse(
                $periode->tanggal_selesai
            )->format('Y-m-d');

            if (
                $this->tanggal_mulai < $tanggalMulaiPeriode ||
                $this->tanggal_selesai > $tanggalSelesaiPeriode
            ) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' =>
                        'Jadwal gelombang harus berada dalam rentang tanggal periode PPDB.',
                ]);
            }

            $gelombang->update([
                'nama_gelombang' => $this->nama_gelombang,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'kuota' => $this->kuota,
                'biaya_pendaftaran' => $this->biaya_pendaftaran,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalEditGelombang',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Gelombang PPDB berhasil diperbarui.',
            ]);

            $this->emit('refreshGelombangs');
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' =>
                    'Gelombang PPDB gagal diperbarui. Silakan coba kembali.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.p-p-d-b.gelombang.edit',[
             'periode' => $this->ppdb_periode_id
                ? PPDBPeriode::find($this->ppdb_periode_id)
                : null,
        ]);
    }
}
