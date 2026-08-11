<?php

namespace App\Http\Livewire\TransaksiPengeluaran;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiPengeluaran;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $transaksi_pengeluaran_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'confirmDeletePengeluaran'
    ];

    public function confirmDeletePengeluaran($transaksi_pengeluaran_id)
    {
        $transaksi = TransaksiPengeluaran::findOrFail($transaksi_pengeluaran_id);

        $this->ms_jenjang_id = $transaksi->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $transaksi->ms_tahun_ajar_id ?? null;

        $this->transaksi_pengeluaran_id = $transaksi_pengeluaran_id;
    }

    public function deletePengeluaran()
    {
        DB::beginTransaction();

        try {
            // ==========================================
            // VALIDASI ID
            // ==========================================
            if (!$this->transaksi_pengeluaran_id) {
                throw new \Exception('Transaksi tidak ditemukan.');
            }

            // ==========================================
            // AMBIL TRANSAKSI
            // ==========================================
            $transaksi = TransaksiPengeluaran::lockForUpdate()
                ->find($this->transaksi_pengeluaran_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan.');
            }

            // ==========================================
            // AUDIT TRANSAKSI
            // ==========================================
            $transaksi->update([
                'deskripsi' => $transaksi->deskripsi .
                    " (Dihapus oleh petugas {$this->nama_petugas})",
            ]);

            // ==========================================
            // HAPUS JURNAL
            // ==========================================
            if ($transaksi->akuntansi_jurnal_id) {
                AccountingService::delete(
                    $transaksi->akuntansi_jurnal_id
                );
            }

            // ==========================================
            // SOFT DELETE TRANSAKSI
            // ==========================================
            $transaksi->delete();

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            // ==========================================
            // REFRESH UI
            // ==========================================
            $this->emit('refreshTransaksi');
            $this->emit('refreshSaldo');

            $this->dispatchBrowserEvent(
                'hide-modal', ['modalId' => 'deletePengeluaran']
            );

            $this->dispatchBrowserEvent(
                'alertify-success',
                ['message' => 'Transaksi berhasil dihapus.']
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent(
                'alertify-error',
                [
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]
            );
        }
    }

    public function render()
    {
        return view('livewire.transaksi-pengeluaran.delete');
    }
}
