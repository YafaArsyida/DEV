<?php

namespace App\Http\Livewire\JenisTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;

class Delete extends Component
{
    public $ms_jenis_tagihan_siswa_id;

    protected $listeners = [
        'confirmDelete'
    ];

    public function confirmDelete($id)
    {
        $this->ms_jenis_tagihan_siswa_id = $id;
    }

    public function deleteJenis()
    {
        // Validasi apakah id tersedia
        if ($this->ms_jenis_tagihan_siswa_id) {
            $jenis = JenisTagihanSiswa::find($this->ms_jenis_tagihan_siswa_id);

            if ($jenis) {
                // Pengecekan apakah Jenis ini sudah digunakan di JenisTagihan
                $isUsed = TagihanSiswa::where('ms_jenis_tagihan_siswa_id', $this->ms_jenis_tagihan_siswa_id)->exists();

                if ($isUsed) {
                    // Jika sudah digunakan, beri peringatan
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak dapat dihapus karena sudah digunakan.']);
                } else {
                    // Jika belum digunakan, hapus Jenis
                    $jenis->delete();
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data berhasil dihapus.']);
                    $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteJenisTagihan']);
                    $this->emit('refreshJenisTagihans');
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak ditemukan.']);
            }
        }
    }
    public function render()
    {
        return view('livewire.jenis-tagihan-siswa.delete');
    }
}
