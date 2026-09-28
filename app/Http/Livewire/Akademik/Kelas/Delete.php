<?php

namespace App\Http\Livewire\Akademik\Kelas;

use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
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
        if (!$this->ms_kelas_id) {
            return;
        }

        DB::beginTransaction();

        try {
            $kelas = Kelas::lockForUpdate()
                ->find($this->ms_kelas_id);

            // 🔥 VALIDASI DATA
            if (!$kelas) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Kelas tidak ditemukan'
                ]);
                return;
            }

            // 🔥 VALIDASI BISNIS
            if ($kelas->ms_penempatan_siswa()->exists()) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Kelas tidak bisa dihapus karena masih digunakan'
                ]);
                return;
            }

            $namaKelas = $kelas->nama_kelas;

            $kelas->delete();

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeleteKelas'
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Kelas {$namaKelas} berhasil dihapus"
            ]);

            $this->reset('ms_kelas_id');
            $this->emit('refreshKelass');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.akademik.kelas.delete');
    }
}
