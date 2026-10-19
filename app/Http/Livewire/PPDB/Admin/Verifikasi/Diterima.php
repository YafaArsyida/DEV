<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use Livewire\Component;

class Diterima extends Component
{
    public $pendaftaranId = null;

    protected $listeners = [
        'bukaDiterima' => 'bukaDiterima',
    ];

    /**
     * Menerima event dari Livewire Detail.
     */
    public function bukaDiterima($id)
    {
        $this->pendaftaranId = $id;

        $pendaftaran = PPDBPendaftaran::find($id);

        if (
            !$pendaftaran ||
            !in_array($pendaftaran->status, ['pengecekan', 'perbaikan'])
        ) {
            $this->reset(['pendaftaranId']);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pendaftaran tidak tersedia untuk diterima.',
            ]);

            return;
        }
    }

    /**
     * Memproses penerimaan pendaftaran.
     */
    public function terimaPendaftaran()
    {
        $this->validate([
            'pendaftaranId' => ['required', 'integer'],
        ], [
            'pendaftaranId.required' =>
                'Pendaftaran belum dipilih.',
            'pendaftaranId.integer' =>
                'ID pendaftaran tidak valid.',
        ]);

        $pendaftaran = PPDBPendaftaran::find(
            $this->pendaftaranId
        );

        if (
            !$pendaftaran ||
            !in_array($pendaftaran->status, ['pengecekan', 'perbaikan'])
        ) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Status pendaftaran sudah berubah atau data tidak ditemukan.',
            ]);

            return;
        }

        $pendaftaran->update([
            'status' => 'diterima',
            'catatan_perbaikan' => null,
            'current_step' => 8
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalKonfirmasiDiterima',
        ]);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Pendaftaran berhasil diterima.',
        ]);

        $this->reset([
            'pendaftaranId',
        ]);

        $this->resetValidation();

        $this->emit('pendaftaranDiperbarui');
    }

    public function render()
    {
        return view('livewire.p-p-d-b.admin.verifikasi.diterima');
    }
}
