<?php

namespace App\Http\Livewire\TahunAjar;

use Livewire\Component;
use App\Models\TahunAjar as TahunAjarModel;

class Edit extends Component
{
    public $ms_tahun_ajar_id, $nama_tahun_ajar, $tanggal_mulai, $tanggal_selesai, $urutan, $deskripsi;

    protected $listeners = ['loadData'];

    public function loadData($ms_tahun_ajar_id)
    {
        $jenjang = TahunAjarModel::findOrFail($ms_tahun_ajar_id);

        $this->ms_tahun_ajar_id = $jenjang->ms_tahun_ajar_id;
        $this->nama_tahun_ajar = $jenjang->nama_tahun_ajar;
        $this->tanggal_mulai = $jenjang->tanggal_mulai;
        $this->tanggal_selesai = $jenjang->tanggal_selesai;
        $this->urutan = $jenjang->urutan;
        $this->deskripsi = $jenjang->deskripsi;

        $this->dispatchBrowserEvent('show-edit-modal'); // Menampilkan modal
    }

    protected function rules()
    {
        return [
            'nama_tahun_ajar' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ];
    }

    protected $messages = [
        'nama_tahun_ajar.required' => 'Nama tahun ajar tidak boleh kosong',
        'nama_tahun_ajar.string' => 'Nama tahun ajar harus berupa teks',
        'nama_tahun_ajar.max' => 'Nama tahun ajar maksimal 255 karakter',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan harus minimal 1',
        'tanggal_mulai.required' => 'Tanggal mulai tidak boleh kosong',
        'tanggal_mulai.date' => 'Tanggal mulai harus berupa format tanggal yang valid',
        'tanggal_selesai.required' => 'Tanggal selesai tidak boleh kosong',
        'tanggal_selesai.date' => 'Tanggal selesai harus berupa format tanggal yang valid',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateTahunAjar()
    {
        $validatedData = $this->validate();

        $tahunAjar = TahunAjarModel::where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)->firstOrFail();
        $tahunAjar->update($validatedData);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah tahun ajar!']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditTahunAjar']);
        $this->emit('refreshTahunAjars');
    }

    public function render()
    {
        return view('livewire.tahun-ajar.edit');
    }
}
