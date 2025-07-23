<?php

namespace App\Http\Livewire\Pengguna;

use App\Models\EduPay;
use App\Models\Tabungan;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\User;
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
        if ($this->ms_pengguna_id) {
            $pengguna = User::find($this->ms_pengguna_id);

            if ($pengguna) {
                // Validasi hubungan dengan model lain
                $relatedDataExists = EduPay::where('ms_pengguna_id', $this->ms_pengguna_id)->exists() ||
                    Tabungan::where('ms_pengguna_id', $this->ms_pengguna_id)->exists() ||
                    Transaksi::where('ms_pengguna_id', $this->ms_pengguna_id)->exists() ||
                    Tagihan::where('ms_pengguna_id', $this->ms_pengguna_id)->exists();

                if ($relatedDataExists) {
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak dapat dihapus, memiliki transaksi terkait.']);
                    return;
                }

                // Hapus semua akses jenjang pengguna
                $pengguna->ms_akses_jenjang()->delete();

                // Hapus pengguna jika tidak ada data terkait
                $pengguna->delete();

                // Emit event untuk memberikan feedback di index
                $this->emit('refreshPengguna');
                $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pengguna berhasil dihapus']);
            }
        }

        // Reset properti dan tutup modal
        $this->reset(['ms_pengguna_id']);
        $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeletePengguna']);
    }

    public function render()
    {
        return view('livewire.pengguna.delete');
    }
}
