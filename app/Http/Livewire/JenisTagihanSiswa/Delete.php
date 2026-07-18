<?php

namespace App\Http\Livewire\JenisTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\TagihanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ms_jenis_tagihan_siswa_id;

    protected $listeners = [
        'confirmDeleteJenis'
    ];

    public function confirmDeleteJenis($id)
    {
        $this->ms_jenis_tagihan_siswa_id = $id;
    }

    public function deleteData()
    {
        DB::beginTransaction();

        try {
            if (!$this->ms_jenis_tagihan_siswa_id) {
                throw new \Exception('Data tidak valid');
            }

            $jenis = JenisTagihanSiswa::find($this->ms_jenis_tagihan_siswa_id);

            if (!$jenis) {
                throw new \Exception('Data tidak ditemukan');
            }

            // 🔥 VALIDASI RELASI (blocking rule)
            $isUsed = TagihanSiswa::where(
                'ms_jenis_tagihan_siswa_id',
                $this->ms_jenis_tagihan_siswa_id
            )->exists();

            if ($isUsed) {
                throw new \Exception('Data tidak dapat dihapus karena sudah digunakan');
            }

            // ✅ delete
            $jenis->delete();

            DB::commit();

            // ✅ SUCCESS FLOW (konsisten)
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Data berhasil dihapus'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeleteJenisTagihan'
            ]);

            $this->ms_jenis_tagihan_siswa_id = null;

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
        return view('livewire.jenis-tagihan-siswa.delete');
    }
}
