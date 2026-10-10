<?php

namespace App\Http\Livewire\PPDB\Periode;

use App\Models\PPDBPeriode;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ppdb_periode_id;

    protected $listeners = [
        'confirmDeletePeriode' => 'setPeriodeId',
    ];

    public function setPeriodeId($id)
    {
        $this->ppdb_periode_id = $id;
    }

    public function deletePeriode()
    {
        if (!$this->ppdb_periode_id) {
            return;
        }

        DB::beginTransaction();

        try {
            $periode = PPDBPeriode::lockForUpdate()
                ->find($this->ppdb_periode_id);

            // VALIDASI DATA
            if (!$periode) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Periode PPDB tidak ditemukan.',
                ]);

                return;
            }

            // VALIDASI BISNIS
            if ($periode->ppdb_gelombang()->exists()) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Periode tidak bisa dihapus karena masih memiliki gelombang pendaftaran.',
                ]);

                return;
            }

            $namaPeriode = $periode->nama_periode;

            $periode->delete();

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeletePeriode',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Periode {$namaPeriode} berhasil dihapus.",
            ]);

            $this->reset('ppdb_periode_id');

            $this->emit('refreshPeriodes');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Periode gagal dihapus. Periksa relasi data atau coba kembali.',
            ]);
        }
    }
    public function render()
    {
        return view('livewire.p-p-d-b.periode.delete');
    }
}
