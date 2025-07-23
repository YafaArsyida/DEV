<?php

namespace App\Http\Livewire\Kelas;

use App\Models\AktifitasPengguna;
use App\Models\Kelas as KelasModel;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Delete extends Component
{
    public $ms_kelas_id;

    protected $listeners = ['confirmDeleteKelas' => 'setKelasId'];

    public function setKelasId($id)
    {
        $this->ms_kelas_id = $id;
    }

    public function deleteKelas()
    {
        // Validasi apakah id tersedia
        if ($this->ms_kelas_id) {
            $kelas = KelasModel::find($this->ms_kelas_id);

            if ($kelas) {
                // Pengecekan apakah kelas ini sudah digunakan di PenempatanSiswa
                $isUsedInPenempatan = PenempatanSiswa::where('ms_kelas_id', $this->ms_kelas_id)->exists();

                if ($isUsedInPenempatan) {
                    // Jika sudah digunakan, beri peringatan
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Kelas tidak dapat dihapus karena sudah digunakan di Penempatan Siswa.']);
                } else {
                    // Simpan nama kelas untuk log aktivitas sebelum dihapus
                    $namaKelas = $kelas->nama_kelas;

                    // Hapus kelas
                    $kelas->delete();
                    $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteKelas']);
                    $this->emit('refreshKelass'); // Refresh data di komponen Index
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kelas berhasil dihapus.']);
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Kelas tidak ditemukan.']);
            }
        }
    }

    public function render()
    {
        return view('livewire.kelas.delete');
    }
}
