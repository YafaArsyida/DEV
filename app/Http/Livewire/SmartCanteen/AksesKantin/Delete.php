<?php

namespace App\Http\Livewire\SmartCanteen\AksesKantin;

use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ms_pengguna_id;

    protected $listeners = [
        'deletePengguna' => 'confirmDelete', // Listener untuk menangkap event dari tombol
    ];

    public function confirmDelete($ms_pengguna_id)
    {
        $this->ms_pengguna_id = $ms_pengguna_id;
    }

    public function deletePengguna()
    {
        if (!$this->ms_pengguna_id) {
            return;
        }

        DB::beginTransaction();

        try {

            $pengguna = User::find($this->ms_pengguna_id);

            if (!$pengguna) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Data pengguna tidak ditemukan.'
                ]);

                return;
            }

            // Cek apakah sudah memiliki transaksi kantin
            $relatedDataExists = TransaksiSmartCanteen::where(
                'ms_pengguna_id',
                $this->ms_pengguna_id
            )->exists();

            if ($relatedDataExists) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Tidak dapat dihapus, pengguna memiliki transaksi terkait.'
                ]);

                return;
            }

            // Hapus akses kantin
            $pengguna->ms_kantin()->detach();

            // Hapus pengguna
            $pengguna->delete();

            DB::commit();

            // Refresh index
            $this->emit('refreshPengguna');

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Pengguna berhasil dihapus'
            ]);

            $this->reset(['ms_pengguna_id']);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeletePengguna'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?: 'Terjadi kesalahan saat menghapus pengguna.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.smart-canteen.akses-kantin.delete');
    }
}
