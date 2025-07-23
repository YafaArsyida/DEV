<?php

namespace App\Http\Livewire\KategoriTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Livewire\Component;

class Delete extends Component
{
    public $ms_kategori_tagihan_siswa_id;

    protected $listeners = ['confirmDelete' => 'setKategoriId'];

    public function setKategoriId($id)
    {
        $this->ms_kategori_tagihan_siswa_id = $id;
    }

    public function deleteKategori()
    {
        // Validasi apakah id tersedia
        if ($this->ms_kategori_tagihan_siswa_id) {
            $kategori = KategoriTagihanSiswa::find($this->ms_kategori_tagihan_siswa_id);

            if ($kategori) {
                // Pengecekan apakah Kategori ini sudah digunakan di JenisTagihan
                $isUsed = JenisTagihanSiswa::where('ms_kategori_tagihan_siswa_id', $this->ms_kategori_tagihan_siswa_id)->exists();

                if ($isUsed) {
                    // Jika sudah digunakan, beri peringatan
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Kategori tidak dapat dihapus karena sudah digunakan di Jenis Tagihan Siswa.']);
                } else {
                    // Jika belum digunakan, hapus Kategori
                    $kategori->delete();
                    $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteKategoriTagihan']);
                    $this->emit('refreshKategoriTagihans');
                    $this->emit('refreshJenisTagihans');
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Katgeori berhasil dihapus.']);
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Kategori tidak ditemukan.']);
            }
        }
    }

    public function render()
    {
        return view('livewire.kategori-tagihan-siswa.delete');
    }
}
