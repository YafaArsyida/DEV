<?php

namespace App\Http\Livewire\SmartCanteen\Kantin;

use App\Models\SmartCanteen\Kantin;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ms_kantin_id;

    protected $listeners = [
        'confirmDeleteKantin' => 'setKantinId'
    ];

    public function setKantinId($id)
    {
        $this->ms_kantin_id = $id;
    }

    public function deleteKantin()
    {
        if (!$this->ms_kantin_id) {
            return;
        }

        DB::beginTransaction();

        try {
            $kantin = Kantin::with('ms_pengguna') // 🔥 cek relasi
                ->lockForUpdate()
                ->find($this->ms_kantin_id);

            // 🔥 VALIDASI DATA
            if (!$kantin) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Kantin tidak ditemukan'
                ]);
                return;
            }

            // 🔥 VALIDASI BISNIS (tidak boleh ada petugas)
            if ($kantin->ms_pengguna()->exists()) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Kantin tidak bisa dihapus karena masih memiliki petugas'
                ]);
                return;
            }

            $namaKantin = $kantin->nama_kantin;

            $kantin->delete();

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeleteKantin'
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Kantin {$namaKantin} berhasil dihapus"
            ]);

            $this->reset('ms_kantin_id');

            $this->emit('refreshKantin');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.smart-canteen.kantin.delete');
    }
}
