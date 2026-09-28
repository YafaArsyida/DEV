<?php

namespace App\Http\Livewire\Keuangan\TransaksiEduPaySiswa;

use App\Models\SaldoEduPay;
use App\Models\TransaksiEduPay;
use App\Services\AccountingService;
use Carbon\Carbon;
use Livewire\Component;

use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    public $ms_transaksi_edupay_id;
    public $nama_petugas;
    public $nama_user = null;

    protected $listeners = [
        'confirmDeleteEduPay'
    ];

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    public function confirmDeleteEduPay($id)
    {
        // 🔥 Lock transaksi
        $transaksi = TransaksiEduPay::find($id);

        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }
        
        $this->ms_transaksi_edupay_id = $id;
        if ($transaksi->user_type === 'siswa') {
            $this->nama_user = $transaksi->ms_siswa?->nama_siswa ?? '-';
        } elseif ($transaksi->user_type === 'pegawai') {
            $this->nama_user = $transaksi->ms_pegawai?->nama_pegawai ?? '-';
        } else {
            $this->nama_user = '-';
        }
    }
    
    protected function validateSaldoDelete($transaksi, $saldo)
    {
        $saldoValue = $saldo->saldo_edupay;

        if (in_array($transaksi->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
            $saldoSetelah = $saldoValue - $transaksi->nominal;
        } elseif (in_array($transaksi->jenis_transaksi, ['penarikan', 'pembayaran', 'kantin'])) {
            $saldoSetelah = $saldoValue + $transaksi->nominal;
        } else {
            $saldoSetelah = $saldoValue;
        }

        if ($saldoSetelah < 0) {
            throw new \Exception('Saldo sudah digunakan');
        }
    }

    protected function processPembatalan($transaksi, $saldo)
    {
        // =========================================================
        // 1. VALIDASI JURNAL ASLI
        // =========================================================
        if (!$transaksi->akuntansi_jurnal_id) {
            throw new \Exception(
                'Jurnal asli transaksi tidak ditemukan.'
            );
        }

        // =========================================================
        // 2. TENTUKAN NAMA TRANSAKSI
        // =========================================================
        $namaTransaksi = match ($transaksi->jenis_transaksi) {
            'topup tunai'  => 'Top Up Tunai EduPay',
            'topup online' => 'Top Up Online EduPay',
            'penarikan'    => 'Penarikan EduPay',
            default        => throw new \Exception(
                'Jenis transaksi EduPay tidak dapat dibatalkan.'
            ),
        };

        // =========================================================
        // 3. DESKRIPSI JURNAL REVERSAL
        // =========================================================
        $deskripsiJurnal = sprintf(
            'Pembatalan %s Rp %s - %s',
            $namaTransaksi,
            number_format($transaksi->nominal, 0, ',', '.'),
            $this->nama_user
        );

        // =========================================================
        // 4. BUAT JURNAL REVERSAL
        // =========================================================
        $jurnalPembatalan = AccountingService::reverse(
            $transaksi->akuntansi_jurnal_id,
            [
                'tanggal' => now(),
                'deskripsi' => $deskripsiJurnal,
                'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
            ]
        );

        // =========================================================
        // 5. KOREKSI SALDO EDUPAY
        // =========================================================
        if (in_array($transaksi->jenis_transaksi, [
            'topup tunai',
            'topup online',
        ])) {

            // Top Up dibatalkan
            // Saldo EduPay dikurangi kembali
            $saldo->decrement(
                'saldo_edupay', $transaksi->nominal
            );

        } elseif ($transaksi->jenis_transaksi === 'penarikan') {

            // Penarikan dibatalkan
            // Saldo EduPay dikembalikan
            $saldo->increment(
                'saldo_edupay', $transaksi->nominal
            );
        }


        // =========================================================
        // 6. UPDATE TRANSAKSI EDUPAY
        // =========================================================
        $transaksi->update([
            'status_transaksi' => 'dibatalkan',
            'akuntansi_jurnal_reversal_id' => $jurnalPembatalan->akuntansi_jurnal_id,
        ]);
    }

    protected function afterDeleteSuccess()
    {
        $this->emit('successTransaksiEduPay');

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalDeleteEduPay'
        ]);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi EduPay berhasil dihapus.'
        ]);
    }

    public function deleteEduPay()
    {
        DB::beginTransaction();

        try {
            // =====================================================
            // 1. LOCK TRANSAKSI
            // =====================================================
            $transaksi = TransaksiEduPay::lockForUpdate()
                ->find($this->ms_transaksi_edupay_id);

            if (!$transaksi) {
                throw new \Exception(
                    'Transaksi tidak ditemukan!'
                );
            }

            // =====================================================
            // 2. VALIDASI
            // =====================================================
             if (!in_array($transaksi->jenis_transaksi, [
                'topup tunai',
                'topup online',
                'penarikan',
            ])) {
                throw new \Exception(
                    'Jenis transaksi EduPay ini tidak dapat dibatalkan.'
                );
            }

            if (!Carbon::parse($transaksi->tanggal)->isToday()) {
                throw new \Exception(
                    'Transaksi hanya dapat dibatalkan pada hari transaksi.'
                );
            }

            if ($transaksi->status_transaksi === 'dibatalkan') {
                throw new \Exception(
                    'Transaksi sudah dibatalkan.'
                );
            }

            // =====================================================
            // 3. LOCK SALDO
            // =====================================================
            $saldo = SaldoEduPay::where('user_id', $transaksi->user_id)
                ->where('user_type', $transaksi->user_type)
                ->lockForUpdate()
                ->first();

            if (!$saldo) {
                throw new \Exception(
                    'Data saldo tidak ditemukan!'
                );
            }

            // =====================================================
            // 4. VALIDASI SALDO
            // =====================================================
            $this->validateSaldoDelete(
                $transaksi, $saldo
            );

            // =====================================================
            // 5. PROSES PEMBATALAN
            // =====================================================
            $this->processPembatalan($transaksi, $saldo);

            DB::commit();

            // =====================================================
            // 6. SUCCESS
            // =====================================================
            $this->afterDeleteSuccess();

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage()
                    ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.keuangan.transaksi-edu-pay-siswa.delete');
    }
}
