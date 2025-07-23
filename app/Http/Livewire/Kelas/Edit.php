<?php

namespace App\Http\Livewire\Kelas;

use App\Models\AktifitasPengguna;
use App\Models\Jenjang as JenjangModel;
use App\Models\Kelas as KelasModel;
use App\Models\TahunAjar as TahunAjarModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public $nama_kelas, $ms_kelas_id, $ms_jenjang_id, $ms_tahun_ajar_id, $urutan, $deskripsi;

    protected $listeners = ['loadDataKelas'];

    public function loadDataKelas($ms_kelas_id)
    {
        $kelas = KelasModel::findOrFail($ms_kelas_id);

        $this->ms_kelas_id = $kelas->ms_kelas_id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->urutan = $kelas->urutan;
        $this->deskripsi = $kelas->deskripsi;
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
        $validatedData = $this->validate();
        $kelas = KelasModel::where('ms_kelas_id', $this->ms_kelas_id)->firstOrFail();

        // Simpan data kelas sebelum diupdate untuk log aktivitas
        $oldNamaKelas = $kelas->nama_kelas;

        $kelas->update($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah kelas!']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditKelas']);
        $this->emit('refreshKelass');
        $this->emit('refreshSiswas');
    }

    public function render()
    {
        return view('livewire.kelas.edit');
    }
}
