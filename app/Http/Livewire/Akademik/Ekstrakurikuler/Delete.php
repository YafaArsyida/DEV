<?php

namespace App\Http\Livewire\Akademik\Ekstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\PenempatanEkstrakurikuler;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();

        try {
            if (!$this->ms_ekstrakurikuler_id) {
                throw new \Exception('Data tidak valid');
            }

            $ekstrakurikuler = Ekstrakurikuler::find($this->ms_ekstrakurikuler_id);

            if (!$ekstrakurikuler) {
                throw new \Exception('Data tidak ditemukan');
            }

            // 🔥 VALIDASI RELASI (blocking rule)
            $isUsed = PenempatanEkstrakurikuler::where(
                'ms_ekstrakurikuler_id',
                $this->ms_ekstrakurikuler_id
            )->exists();

            if ($isUsed) {
                throw new \Exception('Ekstrakurikuler tidak dapat dihapus karena sudah digunakan');
            }

            // ✅ Delete
            $ekstrakurikuler->delete();

            DB::commit();

            // ✅ SUCCESS FLOW
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Ekstrakurikuler berhasil dihapus'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'deleteEkstrakurikuler'
            ]);

            $this->ms_ekstrakurikuler_id = null;

            $this->emit('refreshEkstrakurikuler');
            $this->emit('refreshSiswas');

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.akademik.ekstrakurikuler.delete');
    }
}
