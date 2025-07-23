<?php

namespace App\Http\Livewire\Jenjang;

use Livewire\Component;

use App\Models\Jenjang as JenjangModel;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;

class Delete extends Component
{
    public $ms_jenjang_id;

    protected $listeners = ['confirmDelete' => 'setJenjangId'];

    public function setJenjangId($id)
    {
        $this->ms_jenjang_id = $id;
        $this->dispatchBrowserEvent('show-delete-modal'); // Tampilkan modal
    }

    public function deleteJenjang()
    {
        // Validasi apakah id tersedia
        if ($this->ms_jenjang_id) {
            $jenjang = JenjangModel::find($this->ms_jenjang_id);

            if ($jenjang) {
                // Pengecekan apakah Jenjang ini sudah digunakan di PenempatanSiswa atau Kelas
                $isUsedInPenempatanSiswa = PenempatanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)->exists();
                $isUsedInKelas = Kelas::where('ms_jenjang_id', $this->ms_jenjang_id)->exists();

                if ($isUsedInPenempatanSiswa || $isUsedInKelas) {
                    // Jika sudah digunakan, tampilkan pesan error
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang tidak dapat dihapus karena sudah digunakan di penempatan.']);
                } else {
                    // Jika belum digunakan, hapus Jenjang
                    $jenjang->delete();
                    $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteJenjang']);
                    $this->emit('refreshJenjangs'); // Refresh data di komponen Index
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Jenjang berhasil dihapus.']);
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang tidak ditemukan.']);
            }
        }
    }


    public function render()
    {
        return view('livewire.jenjang.delete');
    }
}
