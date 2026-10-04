<?php

namespace App\Http\Livewire\Keuangan\TransaksiPengeluaran;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiPengeluaran;
use App\Services\AccountingService;
use Carbon\Carbon;
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
            // VALIDASI STATUS
            // ==========================================
            if ($transaksi->status_transaksi === 'dibatalkan') {
                throw new \Exception('Transaksi sudah dibatalkan.');
            }

            if (!Carbon::parse($transaksi->tanggal)->isToday()) {
                throw new \Exception(
                    'Transaksi hanya dapat dibatalkan pada hari transaksi.'
                );
            }

            // ==========================================
            // VALIDASI JURNAL
            // ==========================================
            if (!$transaksi->akuntansi_jurnal_id) {
                throw new \Exception(
                    'Jurnal transaksi tidak ditemukan.'
                );
            }
            // ==========================================
            // BUAT JURNAL REVERSAL
            // ==========================================
            $jurnalPembatalan = AccountingService::reverse(
                $transaksi->akuntansi_jurnal_id,
                [
                    'tanggal' => now(),
                    'deskripsi' =>
                        'Pembatalan transaksi pengeluaran'
                        . ' - ' . $transaksi->deskripsi,

                    'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
                ]
            );

            // ==========================================
            // AUDIT TRANSAKSI
            // ==========================================
            $transaksi->update([
                'status_transaksi' => 'dibatalkan',
                'akuntansi_jurnal_reversal_id' => $jurnalPembatalan->akuntansi_jurnal_id,
                'deskripsi' => $transaksi->deskripsi
                    . " (Dibatalkan oleh petugas {$this->nama_petugas})",
            ]);


            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            // ==========================================
            // REFRESH UI
            // ==========================================
            $this->emit('refreshTransaksi');
            $this->emit('refreshSaldo');

            $this->dispatchBrowserEvent('hide-modal', [
                    'modalId' => 'deletePengeluaran'
                ]
            );

            $this->dispatchBrowserEvent('alertify-success', [
                    'message' => 'Transaksi berhasil dibatalkan.'
                ]
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
        return view('livewire.keuangan.transaksi-pengeluaran.delete');
    }
}
