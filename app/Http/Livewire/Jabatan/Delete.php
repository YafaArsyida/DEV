<?php

namespace App\Http\Livewire\Jabatan;

use App\Models\Jabatan;
use App\Models\Pegawai;
use Livewire\Component;

class Delete extends Component
{
    public $ms_jabatan_id;

    protected $listeners = ['confirmDeleteJabatan' => 'setJabatanId'];

    public function setJabatanId($id)
    {
        $this->ms_jabatan_id = $id;
    }

    public function deleteJabatan()
    {
        // Validasi apakah id tersedia
        if ($this->ms_jabatan_id) {
            $jabatan = Jabatan::find($this->ms_jabatan_id);

            if ($jabatan) {
                // Pengecekan apakah Jabatan ini sudah digunakan di Pegawai
                $isUsedInPegawai = Pegawai::where('ms_jabatan_id', $this->ms_jabatan_id)->exists();

                if ($isUsedInPegawai) {
                    // Jika sudah digunakan, tampilkan pesan error
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jabatan tidak dapat dihapus, sudah digunakan di pegawai.']);
                } else {
                    // Jika belum digunakan, hapus Jabatan
                    $jabatan->delete();
                    $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalDeleteJabatan']);
                    $this->emit('JabatanIndex'); // Refresh data di komponen Index
                    $this->emit('PegawaiIndex'); // Refresh data di komponen Index
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Jabatan berhasil dihapus.']);
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jabatan tidak ditemukan.']);
            }
        }
    }
    public function render()
    {
        return view('livewire.jabatan.delete');
    }
}
