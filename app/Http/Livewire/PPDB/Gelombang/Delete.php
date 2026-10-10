<?php

namespace App\Http\Livewire\PPDB\Gelombang;

use App\Models\PPDBGelombang;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ppdb_gelombang_id;

    protected $listeners = [
        'confirmDeleteGelombang' => 'setGelombangId',
    ];

    /**
     * Menyiapkan ID gelombang yang akan dihapus.
     */
    public function setGelombangId($id)
    {
        $this->ppdb_gelombang_id = $id;
    }

    /**
     * Hapus gelombang PPDB.
     */
    public function deleteGelombang()
    {
        if (!$this->ppdb_gelombang_id) {
            return;
        }

        DB::beginTransaction();

        try {
            $gelombang = PPDBGelombang::lockForUpdate()
                ->find($this->ppdb_gelombang_id);

            // VALIDASI DATA
            if (!$gelombang) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Gelombang PPDB tidak ditemukan.',
                ]);

                return;
            }

            // VALIDASI BISNIS
            // Tambahkan pemeriksaan relasi pendaftaran siswa
            // setelah relasi/model pendaftaran tersedia.

            $namaGelombang = $gelombang->nama_gelombang;

            $gelombang->delete();

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeleteGelombang',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Gelombang {$namaGelombang} berhasil dihapus.",
            ]);

            $this->reset('ppdb_gelombang_id');

            $this->emit('refreshGelombangs');
            $this->emit('refreshPeriodes');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' =>
                    'Gelombang gagal dihapus. Periksa relasi data atau coba kembali.',
            ]);
        }
    }
    public function render()
    {
        return view('livewire.p-p-d-b.gelombang.delete');
    }
}
