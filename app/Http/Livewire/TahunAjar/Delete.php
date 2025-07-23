<?php

namespace App\Http\Livewire\TahunAjar;

use Livewire\Component;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar as TahunAjarModel;

class Delete extends Component
{
    public $ms_tahun_ajar_id;

    protected $listeners = ['confirmDelete' => 'setTahunAjarId'];

    public function setTahunAjarId($id)
    {
        $this->ms_tahun_ajar_id = $id;
        $this->dispatchBrowserEvent('show-delete-modal'); // Tampilkan modal
    }

    public function deleteTahunAjar()
    {
        // Validasi apakah id tersedia
        if ($this->ms_tahun_ajar_id) {
            $tahunAjar = TahunAjarModel::find($this->ms_tahun_ajar_id);

            if ($tahunAjar) {
                // Pengecekan apakah Tahun Ajar ini sudah digunakan di PenempatanSiswa atau Kelas
                $isUsedInPenempatanSiswa = PenempatanSiswa::where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)->exists();
                $isUsedInKelas = Kelas::where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)->exists();

                if ($isUsedInPenempatanSiswa || $isUsedInKelas) {
                    // Jika sudah digunakan, tampilkan pesan error
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tahun Ajar tidak dapat dihapus karena sudah digunakan di penempatan.']);
                } else {
                    // Jika belum digunakan, hapus Tahun Ajar
                    $tahunAjar->delete();
                    $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteTahunAjar']);
                    $this->emit('refreshTahunAjars'); // Refresh data di komponen Index
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tahun Ajar berhasil dihapus.']);
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tahun Ajar tidak ditemukan.']);
            }
        }
    }


    public function render()
    {
        return view('livewire.tahun-ajar.delete');
    }
}
