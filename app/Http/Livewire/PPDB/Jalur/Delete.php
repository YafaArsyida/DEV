<?php

namespace App\Http\Livewire\PPDB\Jalur;

use App\Models\PPDBJalur;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ppdb_jalur_id;

    protected $listeners = [
        'confirmDeleteJalur' => 'setJalurId',
    ];

    /**
     * Menyiapkan ID jalur yang akan dihapus.
     */
    public function setJalurId($id)
    {
        $this->ppdb_jalur_id = $id;
    }

    /**
     * Hapus jalur PPDB jika belum digunakan pendaftaran siswa.
     */
    public function deleteJalur()
    {
        if (!$this->ppdb_jalur_id) {
            return;
        }

        DB::beginTransaction();

        try {
            $jalur = PPDBJalur::where(
                'ppdb_jalur_id', $this->ppdb_jalur_id
            )->lockForUpdate()->first();

            if (!$jalur) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Jalur PPDB tidak ditemukan.',
                ]);

                return;
            }

            // Jalur tidak boleh dihapus jika sudah digunakan pendaftaran.
            $memilikiPendaftaran = DB::table('ppdb_pendaftaran')
                ->where('ppdb_jalur_id', $jalur->ppdb_jalur_id)
                ->exists();

            if ($memilikiPendaftaran) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Jalur tidak dapat dihapus karena sudah digunakan dalam pendaftaran siswa.',
                ]);

                return;
            }

            $namaJalur = $jalur->nama_jalur;

            $jalur->delete();

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeleteJalur',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Jalur {$namaJalur} berhasil dihapus.",
            ]);

            $this->reset('ppdb_jalur_id');

            $this->emit('refreshJalurs');
            $this->emit('refreshPeriodes');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jalur gagal dihapus. Periksa relasi data atau coba kembali.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.p-p-d-b.jalur.delete');
    }
}
