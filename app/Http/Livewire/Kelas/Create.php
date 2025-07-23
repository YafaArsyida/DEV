<?php

namespace App\Http\Livewire\Kelas;

use App\Models\AktifitasPengguna;
use App\Models\Jenjang as JenjangModel;
use App\Models\Kelas as KelasModel;
use App\Models\TahunAjar as TahunAjarModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $nama_kelas, $ms_jenjang_id, $ms_tahun_ajar_id, $urutan, $deskripsi;

    protected $listeners = [
        'showCreateKelas',
    ];

    public function showCreateKelas($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
        $this->emitSelf('render');
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
        try {
            $validatedData = $this->validate();
            // Buat kelas baru
            KelasModel::create($validatedData);

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah kelas!']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        $this->resetInput();
        $this->emit('refreshKelass');
        $this->emit('refreshSiswas');
    }

    public function resetInput()
    {
        $this->nama_kelas = '';
        $this->urutan = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.kelas.create');
    }
}
