<?php

namespace App\Http\Livewire\PPDB\Periode;

use App\Models\PPDBPeriode;
use Livewire\Component;

class Edit extends Component
{
    public $ppdb_periode_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $nama_periode;
    public $deskripsi;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $status;

    protected $listeners = [
        'loadDataPeriode' => 'loadPeriode',
    ];

    public function loadPeriode($id)
    {
        $periode = PPDBPeriode::findOrFail($id);

        $this->ppdb_periode_id = $periode->ppdb_periode_id;
        $this->ms_jenjang_id = $periode->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $periode->ms_tahun_ajar_id;
        $this->nama_periode = $periode->nama_periode;
        $this->deskripsi = $periode->deskripsi;
        $this->tanggal_mulai = $periode->tanggal_mulai
            ? \Carbon\Carbon::parse($periode->tanggal_mulai)->format('Y-m-d')
            : null;

        $this->tanggal_selesai = $periode->tanggal_selesai
            ? \Carbon\Carbon::parse($periode->tanggal_selesai)->format('Y-m-d')
            : null;
        $this->status = $periode->status;
    }

    protected function rules()
    {
        return [
            'nama_periode' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required',
        ];
    }

    protected $messages = [
        'nama_periode.required' => 'Nama periode wajib diisi.',
        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        'status.required' => 'Status periode wajib dipilih.',
    ];

    public function updatePeriode()
    {
        $this->validate();

        $periode = PPDBPeriode::findOrFail($this->ppdb_periode_id);

        $periode->update([
            'nama_periode' => $this->nama_periode,
            'deskripsi' => $this->deskripsi,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'status' => $this->status,
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalEditPeriode',
        ]);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode PPDB berhasil diperbarui.',
        ]);

        $this->emit('refreshPeriodes');
    }

    public function render()
    {
        return view('livewire.p-p-d-b.periode.edit');
    }
}
