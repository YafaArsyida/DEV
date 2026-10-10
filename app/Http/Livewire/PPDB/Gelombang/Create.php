<?php

namespace App\Http\Livewire\PPDB\Gelombang;

use App\Models\PPDBGelombang;
use App\Models\PPDBPeriode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
    public $ppdb_periode_id;

    public $nama_gelombang = '';

    public $tanggal_mulai = '';

    public $tanggal_selesai = '';

    public $kuota = null;

    public $biaya_pendaftaran = 0;

    public $status = 'aktif';

    protected $listeners = [
        'showCreateGelombang',
    ];

    /**
     * Menyiapkan form tambah gelombang.
     */
    public function showCreateGelombang($periodeId)
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
            'nama_gelombang' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    protected $messages = [
        'ppdb_periode_id.required' => 'Periode PPDB belum dipilih.',
        'ppdb_periode_id.integer' => 'Periode PPDB tidak valid.',
        'ppdb_periode_id.exists' => 'Periode PPDB yang dipilih tidak ditemukan.',

        'nama_gelombang.required' => 'Nama gelombang tidak boleh kosong.',
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
     * Simpan gelombang PPDB.
     */
    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Pastikan periode yang dipilih masih tersedia.
            $periode = PPDBPeriode::where(
                'ppdb_periode_id',
                $this->ppdb_periode_id
            )->firstOrFail();

            // Validasi tanggal gelombang terhadap periode PPDB.
            if (
                $this->tanggal_mulai < $periode->tanggal_mulai->format('Y-m-d') ||
                $this->tanggal_selesai > $periode->tanggal_selesai->format('Y-m-d')
            ) {
                $this->addError(
                    'tanggal_mulai',
                    'Jadwal gelombang harus berada dalam rentang tanggal periode PPDB.'
                );

                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Jadwal gelombang harus berada dalam rentang tanggal periode PPDB.',
                ]);
            }

            PPDBGelombang::create([
                'ppdb_periode_id' => $this->ppdb_periode_id,
                'nama_gelombang' => $this->nama_gelombang,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'kuota' => $this->kuota,
                'biaya_pendaftaran' => $this->biaya_pendaftaran,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Gelombang PPDB berhasil ditambahkan.',
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAddGelombang',
            ]);

            $this->resetInput();

            $this->emit('refreshGelombangs');
        } catch (ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' =>
                    'Gelombang PPDB gagal disimpan. Silakan coba kembali.',
            ]);
        }
    }

    /**
     * Reset seluruh input form.
     */
    public function resetInput()
    {
        $this->nama_gelombang = '';
        $this->ppdb_periode_id = null;
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->kuota = null;
        $this->biaya_pendaftaran = 0;
        $this->status = 'aktif';
    }
    public function render()
    {
        return view('livewire.p-p-d-b.gelombang.create', [
            'periode' => $this->ppdb_periode_id
                ? PPDBPeriode::find($this->ppdb_periode_id)
                : null,
        ]);
    }
}
