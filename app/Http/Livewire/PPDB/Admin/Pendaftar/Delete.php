<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use App\Models\PPDBPendaftaran;
use App\Services\PPDB\PPDBPendaftaranService;
use Livewire\Component;

class Delete extends Component
{
    public $ppdb_pendaftaran_id = null;

    protected $listeners = [
        'confirmDeletePendaftaran' => 'setPendaftaranId',
    ];

    public function setPendaftaranId($id): void
    {
        $this->ppdb_pendaftaran_id = (int) $id;
    }

    public function deletePendaftaran(): void
    {
        if (! $this->ppdb_pendaftaran_id) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pilih pendaftaran yang akan dihapus.',
            ]);

            return;
        }

        try {
            $pendaftaran = PPDBPendaftaran::query()
                ->lockForUpdate()
                ->find($this->ppdb_pendaftaran_id);

            if (! $pendaftaran) {

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Pendaftaran tidak ditemukan atau sudah dihapus.',
                ]);

                $this->reset('ppdb_pendaftaran_id');

                return;
            }

            $nomorPendaftaran = $pendaftaran->nomor_pendaftaran;

            PPDBPendaftaranService::delete($pendaftaran);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalDeletePendaftaran',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Pendaftaran {$nomorPendaftaran} berhasil dihapus.",
            ]);

            $this->reset('ppdb_pendaftaran_id');

            // Jika komponen memiliki filter atau pagination,
            $this->emit('pendaftaranTersimpan');

            $this->emitSelf('$refresh');
        } catch (\Throwable $e) {
            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pendaftaran gagal dihapus. Silakan coba kembali.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.p-p-d-b.admin.pendaftar.delete');
    }
}
