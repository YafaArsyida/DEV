<?php

namespace App\Http\Livewire\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    public $nama_ekstrakurikuler, $biaya, $kuota, $deskripsi;

    protected $listeners = [
        'createEkstrakurikuler',
    ];

    public function createEkstrakurikuler($jenjang, $tahunAjar)
    {
        $this->resetValidation();
        $this->resetInput();

        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
    }

    protected function rules()
    {
        return [
            'ms_jenjang_id' => 'required|exists:ms_jenjang,ms_jenjang_id',
            'ms_tahun_ajar_id' => 'required|exists:ms_tahun_ajar,ms_tahun_ajar_id',
            'nama_ekstrakurikuler' => 'required|string|max:255',
            'biaya' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'ms_jenjang_id.required' => 'Pilih jenjang',
        'ms_jenjang_id.exists' => 'Jenjang tidak valid',
        'ms_tahun_ajar_id.required' => 'Pilih tahun ajar',
        'ms_tahun_ajar_id.exists' => 'Tahun ajar tidak valid',
        'nama_ekstrakurikuler.required' => 'Nama Ekstrakurikuler tidak boleh kosong',
        'biaya.required' => 'Biaya wajib diisi',
        'biaya.numeric' => 'Biaya harus berupa angka',
        'biaya.min' => 'Biaya tidak boleh negatif',
        'kuota.required' => 'Kuota wajib diisi',
        'kuota.integer' => 'Kuota harus berupa bilangan bulat',
        'kuota.min' => 'Minimal kuota adalah 1',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();

            Ekstrakurikuler::create([
                'nama_ekstrakurikuler'  => $validatedData['nama_ekstrakurikuler'],
                'ms_jenjang_id'         => $validatedData['ms_jenjang_id'],
                'ms_tahun_ajar_id'      => $validatedData['ms_tahun_ajar_id'],
                'biaya'                 => $validatedData['biaya'],
                'kuota'                 => $validatedData['kuota'],
                'deskripsi'             => $validatedData['deskripsi'],
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil menambah ekstrakurikuler!'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAddEkstrakurikuler'
            ]);

            $this->resetInput();

            $this->emit('refreshEkstrakurikuler');
            $this->emit('refreshSiswas');

        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek input!'
            ]);

            throw $e;

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function resetInput()
    {
        $this->nama_ekstrakurikuler = '';
        $this->biaya = '';
        $this->kuota = '';
        $this->deskripsi = '';
    }


    public function render()
    {
        return view('livewire.ekstrakurikuler.create');
    }
}
