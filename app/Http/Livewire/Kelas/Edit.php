<?php

namespace App\Http\Livewire\Kelas;

use App\Models\Kelas as KelasModel;
use Illuminate\Support\Facades\DB;

use Livewire\Component;
use Illuminate\Validation\ValidationException;

class Edit extends Component
{
    public $kelas;

    public $nama_kelas;
    public $ms_kelas_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $urutan;
    public $deskripsi;

    protected $listeners = [
        'loadDataKelas'
    ];

    public function loadDataKelas($ms_kelas_id)
    {
        $this->resetValidation();

        $this->kelas = KelasModel::findOrFail($ms_kelas_id);

        if (!$this->kelas) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Data tidak ditemukan!'
            ]);

            return;
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data dimuat'
        ]);

        $this->ms_kelas_id = $this->kelas->ms_kelas_id;
        $this->nama_kelas = $this->kelas->nama_kelas;
        $this->urutan = $this->kelas->urutan;
        $this->deskripsi = $this->kelas->deskripsi;
    }

    public function rules()
    {
        return [
            'nama_kelas' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kelas.required' => 'Nama kelas tidak boleh kosong',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateKelas()
    {
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();

            $oldNamaKelas = $this->kelas->nama_kelas;

            $this->kelas->update($validatedData);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Berhasil mengubah kelas {$oldNamaKelas}"
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalEditKelas'
            ]);

            $this->emit('refreshKelass');
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal validasi, cek input!'
            ]);

            throw $e; // 🔥 WAJIB agar @error tampil

        } catch (\Throwable $e) {
            DB::rollBack();

            // report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.kelas.edit');
    }
}
