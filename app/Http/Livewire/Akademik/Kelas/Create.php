<?php

namespace App\Http\Livewire\Akademik\Kelas;

use App\Models\Kelas as KelasModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use Illuminate\Validation\ValidationException;

class Create extends Component
{
    public $nama_kelas;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $urutan;
    public $deskripsi;

    protected $listeners = [
        'showCreateKelas',
    ];

    public function showCreateKelas($jenjang, $tahunAjar)
    {
        $this->resetValidation();
        $this->resetInput();

        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
    }

    protected function rules()
    {
        return [
            'nama_kelas' => 'required|string|max:255',
            'ms_jenjang_id' => 'required',
            'ms_tahun_ajar_id' => 'required',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kelas.required' => 'Nama kelas tidak boleh kosong',
        'ms_jenjang_id.required' => 'Pilih jenjang',
        'ms_tahun_ajar_id.required' => 'Pilih tahun ajar',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
        // 'urutan.unique' => 'Urutan ini sudah digunakan, pilih angka lain',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            KelasModel::create([
                'nama_kelas' => $this->nama_kelas,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'ms_tahun_ajar_id' => $this->ms_tahun_ajar_id,
                'urutan' => $this->urutan,
                'deskripsi' => $this->deskripsi,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Kelas berhasil ditambahkan'
            ]);

            // $this->dispatchBrowserEvent('hide-modal', [
            //     'modalId' => 'ModalAddKelas'
            // ]);

            $this->resetInput();
            $this->emit('refreshKelass');
        } catch (ValidationException $e) {
            DB::rollBack();

            // 🔥 OPTIONAL notif
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek input!'
            ]);

            throw $e; // 🔥 WAJIB agar @error tampil

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function resetInput()
    {
        $this->nama_kelas = '';
        $this->urutan = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.akademik.kelas.create');
    }
}
