<?php

namespace App\Http\Livewire\Pegawai;

use App\Models\EduCard;
use App\Models\EduPay;
use App\Models\Pegawai;
use Livewire\Component;

class Delete extends Component
{
    public $ms_pegawai_id;

    protected $listeners = [
        'confirmDeletePegawai'
    ];

    public function confirmDeletePegawai($ms_pegawai_id)
    {
        $this->ms_pegawai_id = $ms_pegawai_id;
    }

    public function deletePegawai()
    {
        // Validasi apakah ID tersedia
        if (!$this->ms_pegawai_id) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'ID pegawai tidak valid.']);
            return;
        }

        $pegawai = Pegawai::find($this->ms_pegawai_id);

        if (!$pegawai) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pegawai tidak ditemukan.']);
            return;
        }

        // Pengecekan apakah Pegawai digunakan di EduCard
        $isUsedInEducard = EduCard::where('ms_pegawai_id', $this->ms_pegawai_id)->exists();

        if ($isUsedInEducard) {
            // Tampilkan pesan jika Pegawai terdaftar di EduCard
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pegawai terdaftar di EduCard.']);
            return;
        }

        try {
            // Hapus pegawai
            $pegawai->delete();

            // Tutup modal dan refresh data
            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'deletePegawai']);
            $this->emit('refreshPegawais');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pegawai berhasil dihapus.']);
        } catch (\Exception $e) {
            // Penanganan error jika penghapusan gagal
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal menghapus pegawai: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pegawai.delete');
    }
}
