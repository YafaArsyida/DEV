<?php

namespace App\Http\Livewire\PPDB\Admin\Verifikasi;

use App\Models\PPDBPendaftaran;
use Livewire\Component;

class Perbaikan extends Component
{
    public $pendaftaranId = null;

    public $catatanPerbaikan = '';

    protected $listeners = [
        'bukaPerbaikan' => 'bukaPerbaikan',
    ];

    /**
     * Menerima event dari Livewire Detail.
     */
    public function bukaPerbaikan($id)
    {
        $this->resetValidation();

        $this->catatanPerbaikan = '';

        $this->pendaftaranId = $id;

        $pendaftaran = PPDBPendaftaran::find($id);

        if (!$pendaftaran || $pendaftaran->status !== 'pengecekan') {
            $this->reset(['pendaftaranId', 'catatanPerbaikan']);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pendaftaran tidak tersedia untuk diperiksa.',
            ]);

            return;
        }
    }

    /**
     * Memproses permintaan perbaikan.
     */
    public function mintaPerbaikan()
    {
        $this->validate([
            'pendaftaranId' => ['required', 'integer'],
            'catatanPerbaikan' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'catatanPerbaikan.required' =>
                'Catatan perbaikan wajib diisi.',
            'catatanPerbaikan.max' =>
                'Catatan perbaikan maksimal 2000 karakter.',
        ]);

        $pendaftaran = PPDBPendaftaran::find(
            $this->pendaftaranId
        );

        if (!$pendaftaran || $pendaftaran->status !== 'pengecekan') {
            $this->dispatchBrowserEvent('notifikasi', [
                'type' => 'error',
                'message' => 'Status pendaftaran sudah berubah atau data tidak ditemukan.',
            ]);

            return;
        }

        $pendaftaran->update([
            'status' => 'perbaikan',
            'catatan_perbaikan' => trim(
                $this->catatanPerbaikan
            ),
        ]);


        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalKonfirmasiPerbaikan',
        ]);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Pendaftaran berhasil dikembalikan',
        ]);

        $this->reset([
            'pendaftaranId',
            'catatanPerbaikan',
        ]);

        $this->resetValidation();

        $this->emit('pendaftaranDiperbarui');
    }

    public function render()
    {
        return view('livewire.p-p-d-b.admin.verifikasi.perbaikan');
    }
}
