<?php

namespace App\Http\Livewire\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\PenempatanEkstrakurikuler;
use Livewire\Component;

class Delete extends Component
{
    public $ms_ekstrakurikuler_id;

    protected $listeners = [
        'confirmDelete'
    ];

    public function confirmDelete($ms_ekstrakurikuler_id)
    {
        $this->ms_ekstrakurikuler_id = $ms_ekstrakurikuler_id;
    }

    public function deleteEkstrakurikuler()
    {
        // Validasi apakah id tersedia
        if ($this->ms_ekstrakurikuler_id) {
            $ekstrakurikuler = Ekstrakurikuler::find($this->ms_ekstrakurikuler_id);

            if ($ekstrakurikuler) {
                // Pengecekan apakah Ekstrakurikuler ini sudah digunakan di PenempatanSiswa
                $isUsedInPenempatan = PenempatanEkstrakurikuler::where('ms_ekstrakurikuler_id', $this->ms_ekstrakurikuler_id)->exists();

                if ($isUsedInPenempatan) {
                    // Jika sudah digunakan, beri peringatan
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Ekstrakurikuler tidak dapat dihapus karena sudah digunakan di Penempatan.']);
                } else {
                    // Hapus Ekstrakurikuler
                    $ekstrakurikuler->delete();
                    $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'deleteEkstrakurikuler']);
                    $this->emit('refreshEkstrakurikuler'); // Refresh data di komponen Index
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Ekstrakurikuler berhasil dihapus.']);
                }
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Ekstrakurikuler tidak ditemukan.']);
            }
        }
    }

    public function render()
    {
        return view('livewire.ekstrakurikuler.delete');
    }
}
