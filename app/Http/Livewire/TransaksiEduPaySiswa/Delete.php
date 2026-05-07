<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\SaldoEduPay;
use App\Models\TransaksiEduPay;
use Livewire\Component;

use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    public $ms_transaksi_edupay_id;
    public $nama_petugas;

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

    protected function processDelete($transaksi, $saldo)
    {
        // 1. Update saldo dulu (biar aman secara data)
        if (in_array($transaksi->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
            $saldo->decrement('saldo_edupay', $transaksi->nominal);
        } elseif (in_array($transaksi->jenis_transaksi, ['penarikan', 'pembayaran', 'kantin'])) {
            $saldo->increment('saldo_edupay', $transaksi->nominal);
        }

        // 2. Tambahkan jejak audit (lebih aman setelah saldo berhasil)
        $transaksi->deskripsi .= " (Dihapus oleh {$this->nama_petugas})";
        $transaksi->save();

        // Soft delete jurnal (1 query, efisien)
        AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', [
            $transaksi->akuntansi_jurnal_detail_debit_id,
            $transaksi->akuntansi_jurnal_detail_kredit_id,
        ])->delete();

        // Delete transaksi
        $transaksi->delete();
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
            // 🔥 Lock transaksi
            $transaksi = TransaksiEduPay::lockForUpdate()
                ->find($this->ms_transaksi_edupay_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            // 🔥 Lock saldo (WAJIB untuk uang)
            $saldo = SaldoEduPay::where('user_id', $transaksi->user_id)
                ->where('user_type', $transaksi->user_type)
                ->lockForUpdate()
                ->first();

            if (!$saldo) {
                throw new \Exception('Data saldo tidak ditemukan!');
            }

            $this->validateSaldoDelete($transaksi, $saldo);

            $this->processDelete($transaksi, $saldo);

            DB::commit();

            $this->afterDeleteSuccess();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-edu-pay-siswa.delete');
    }
}
