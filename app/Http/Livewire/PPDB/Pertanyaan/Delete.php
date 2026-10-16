<?php

namespace App\Http\Livewire\PPDB\Pertanyaan;

use App\Models\PPDBPertanyaan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class Delete extends Component
{
    public $ppdb_pertanyaan_id;

    protected $listeners = [
        'confirmDeletePertanyaan' => 'setPertanyaanId',
    ];

    /**
     * Menyiapkan ID pertanyaan yang akan dihapus.
     */
    public function setPertanyaanId($id)
    {
        $this->ppdb_pertanyaan_id = $id;
    }

    /**
     * Hapus pertanyaan jika belum digunakan untuk menyimpan jawaban.
     */
    public function deletePertanyaan()
    {
        if (!$this->ppdb_pertanyaan_id) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pilih pertanyaan yang akan dihapus.',
            ]);

            return;
        }

        DB::beginTransaction();

        try {
            $pertanyaan = PPDBPertanyaan::where(
                'ppdb_pertanyaan_id',
                $this->ppdb_pertanyaan_id
            )->lockForUpdate()->first();

            if (!$pertanyaan) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Data pertanyaan tidak ditemukan.',
                ]);

                $this->reset('ppdb_pertanyaan_id');

                return;
            }

            /*
             * Pertanyaan tidak boleh dihapus jika sudah memiliki jawaban.
             * Sesuaikan nama tabel dan foreign key jika migrasi berbeda.
             */
            $memilikiJawaban = DB::table('ppdb_jawaban')
                ->where(
                    'ppdb_pertanyaan_id',
                    $pertanyaan->ppdb_pertanyaan_id
                )
                ->exists();

            if ($memilikiJawaban) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Pertanyaan tidak dapat dihapus karena sudah memiliki jawaban pendaftar.',
                ]);

                return;
            }

            $teksPertanyaan = $pertanyaan->pertanyaan;

            $pertanyaan->delete();

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeletePertanyaan',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Pertanyaan \"{$teksPertanyaan}\" berhasil dihapus.",
            ]);

            $this->reset('ppdb_pertanyaan_id');

            $this->emit('refreshPertanyaans');
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pertanyaan gagal dihapus. Periksa relasi data atau coba kembali.',
            ]);
        }
    }
    public function render()
    {
        return view('livewire.p-p-d-b.pertanyaan.delete');
    }
}
