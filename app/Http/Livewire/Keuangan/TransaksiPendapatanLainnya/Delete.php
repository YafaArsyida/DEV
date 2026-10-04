<?php

namespace App\Http\Livewire\Keuangan\TransaksiPendapatanLainnya;

use App\Models\TransaksiPendapatanLainnya;
use App\Services\AccountingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $transaksi_pendapatan_lainnya_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'confirmDeletePendapatanLainnya'
    ];

    public function confirmDeletePendapatanLainnya($transaksi_pendapatan_lainnya_id)
    {
        $transaksi = TransaksiPendapatanLainnya::findOrFail($transaksi_pendapatan_lainnya_id);

        $this->ms_jenjang_id = $transaksi->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $transaksi->ms_tahun_ajar_id ?? null;

        $this->transaksi_pendapatan_lainnya_id = $transaksi_pendapatan_lainnya_id;
    }

    public function deletePendapatanLainnya()
    {
        DB::beginTransaction();

        try {
            // ==========================================
            // VALIDASI ID
            // ==========================================
            if (!$this->transaksi_pendapatan_lainnya_id) {
                throw new \Exception('Transaksi tidak ditemukan.');
            }

            // ==========================================
            // AMBIL TRANSAKSI
            // ==========================================
            $transaksi = TransaksiPendapatanLainnya::lockForUpdate()
                ->find($this->transaksi_pendapatan_lainnya_id);

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
                        'Pembatalan transaksi pendapatan lainnya '
                        . '- ' . $transaksi->deskripsi,
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

            $this->dispatchBrowserEvent('hide-modal',[
                    'modalId' => 'deletePendapatanLainnya'
                ]
            );

            $this->dispatchBrowserEvent('alertify-success',[
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
        return view('livewire.keuangan.transaksi-pendapatan-lainnya.delete');
    }
}
