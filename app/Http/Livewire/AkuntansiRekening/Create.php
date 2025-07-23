<?php

namespace App\Http\Livewire\AkuntansiRekening;

use App\Models\AkuntansiKelompokRekening;
use App\Models\AkuntansiRekening;
use Livewire\Component;

class Create extends Component
{
    public $akuntansi_kelompok_rekening_id, $kode_rekening, $nama_rekening, $deskripsi;

    public $posisi_normal = 'debit';

    protected $listeners = [
        'CreateAkuntansiRekening'
    ];

    public function CreateAkuntansiRekening()
    {
        $this->resetInput();
        $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_rekening' => 'required|string|max:255',
            'posisi_normal' => 'required|string|in:debit,kredit',
            'kode_rekening' => 'required|string|max:10|unique:akuntansi_rekening,kode_rekening',
            'akuntansi_kelompok_rekening_id' => 'required|exists:akuntansi_kelompok_rekening,akuntansi_kelompok_rekening_id',
            'deskripsi' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'nama_rekening.required' => 'Nama rekening tidak boleh kosong',
        'kode_rekening.required' => 'Kode rekening tidak boleh kosong',
        'kode_rekening.unique' => 'Kode rekening sudah digunakan',
        'akuntansi_kelompok_rekening_id.required' => 'Pilih kategori kelompok rekening',
        'akuntansi_kelompok_rekening_id.exists' => 'Kelompok rekening tidak valid',
    ];
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $validatedData = $this->validate();

        try {
            AkuntansiRekening::create([
                'akuntansi_kelompok_rekening_id' => $this->akuntansi_kelompok_rekening_id,
                'kode_rekening' => $this->kode_rekening,
                'nama_rekening' => $this->nama_rekening,
                'posisi_normal' => $this->posisi_normal,
                'deskripsi' => $this->deskripsi,
            ]);

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambahkan rekening baru']);
            $this->resetInput();
            $this->emit('refreshAkuntansiRekening');
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function resetInput()
    {
        $this->nama_rekening = '';
        $this->kode_rekening = '';
        $this->deskripsi = '';
        $this->akuntansi_kelompok_rekening_id = '';
    }

    public function render()
    {
        $select_kelompok = AkuntansiKelompokRekening::get();
        return view('livewire.akuntansi-rekening.create', [
            'select_kelompok' => $select_kelompok,
        ]);
    }
}
