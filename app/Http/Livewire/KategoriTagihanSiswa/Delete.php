<?php

namespace App\Http\Livewire\KategoriTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ms_kategori_tagihan_siswa_id;

    protected $listeners = ['confirmDeleteKategori' => 'setKategoriId'];

    public function setKategoriId($id)
    {
        $this->ms_kategori_tagihan_siswa_id = $id;
    }

    public function deleteKategori()
    {
        DB::beginTransaction();

        try {
            if (!$this->ms_kategori_tagihan_siswa_id) {
                throw new \Exception('Data tidak valid');
            }

            $kategori = KategoriTagihanSiswa::find($this->ms_kategori_tagihan_siswa_id);

            if (!$kategori) {
                throw new \Exception('Kategori tidak ditemukan');
            }

            // 🔥 VALIDASI RELASI
            $isUsed = JenisTagihanSiswa::where(
                'ms_kategori_tagihan_siswa_id',
                $this->ms_kategori_tagihan_siswa_id
            )->exists();

            if ($isUsed) {
                throw new \Exception('Kategori tidak dapat dihapus karena sudah digunakan di Jenis Tagihan Siswa');
            }

            // ✅ delete
            $kategori->delete();

            DB::commit();

            // ✅ SUCCESS FLOW
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Kategori berhasil dihapus'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeleteKategoriTagihan'
            ]);

            $this->ms_kategori_tagihan_siswa_id = null;

            $this->emit('refreshKategoriTagihans');
            $this->emit('refreshJenisTagihans');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.kategori-tagihan-siswa.delete');
    }
}
