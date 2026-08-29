<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

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
        // 1. AMBIL JURNAL ASLI BESERTA DETAILNYA
        $jurnalAsli = $transaksi->akuntansi_jurnal;

        if (!$jurnalAsli) {
            throw new \Exception(
                'Jurnal asli transaksi tidak ditemukan.'
            );
        }

        $detailAsli = $jurnalAsli->akuntansi_jurnal_detail;

        if ($detailAsli->isEmpty()) {
            throw new \Exception(
                'Detail jurnal asli tidak ditemukan.'
            );
        }

        // 2. VALIDASI DETAIL JURNAL
        foreach ($detailAsli as $detail) {
            if (!in_array($detail->posisi, ['debit', 'kredit'])) {
                throw new \Exception(
                    'Posisi jurnal tidak valid pada rekening '
                    . $detail->kode_rekening
                );
            }

            if ($detail->nominal <= 0) {
                throw new \Exception(
                    'Nominal jurnal tidak valid pada rekening '
                    . $detail->kode_rekening
                );
            }
        }

        // 3. BUAT DETAIL JURNAL REVERSAL
        //    Debit  -> Kredit
        //    Kredit -> Debit
        $detailReversal = $detailAsli
            ->map(function ($detail) {
                return [
                    'kode_rekening' => $detail->kode_rekening,
                    'posisi' => $detail->posisi === 'debit'
                        ? 'kredit'
                        : 'debit',
                    'nominal' => $detail->nominal,
                ];
            })
            ->values()
            ->toArray();

        // 4. DESKRIPSI JURNAL REVERSAL
        $deskripsiJurnal = sprintf(
            'Pembatalan Top Up Tunai EduPay Rp %s - %s',
            number_format($transaksi->nominal, 0, ',', '.'),
            $this->nama_user
        );

        // 6. BUAT JURNAL REVERSAL
        $jurnalPembatalan = AccountingService::create([
            'tanggal' => now(),
            'deskripsi' => $deskripsiJurnal,
            'ms_pengguna_id' => auth()->user()->ms_pengguna_id,

            'ms_tahun_ajaran_id' => $jurnalAsli->ms_tahun_ajaran_id,

            'ms_jenjang_id' => $jurnalAsli->ms_jenjang_id,

            'ms_departemen_id' => $jurnalAsli->ms_departemen_id,

            'detail' => $detailReversal,
        ]);

        // 7. VALIDASI SALDO EDU PAY
        if ($saldo->saldo_edupay < $transaksi->nominal) {
            throw new \Exception(
                'Saldo EduPay tidak cukup untuk membatalkan top up.'
            );
        }

        // 8. KEMBALIKAN SALDO DENGAN MENGURANGI SALDO EDUPAY
        $saldo->decrement(
            'saldo_edupay', $transaksi->nominal
        );

        // 9. UPDATE TRANSAKSI EDUPAY
        $transaksi->update([
            'status_transaksi' => 'dibatalkan',
            'akuntansi_jurnal_reversal_id' =>
                $jurnalPembatalan->akuntansi_jurnal_id,
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
            // LOCK TRANSAKSI
            $transaksi = TransaksiEduPay::lockForUpdate()
                ->find($this->ms_transaksi_edupay_id);

            if (!$transaksi) {
                throw new \Exception(
                    'Transaksi tidak ditemukan!'
                );
            }

            // VALIDASI
            if ($transaksi->jenis_transaksi !== 'topup tunai') {
                throw new \Exception(
                    'Hanya Top Up Tunai yang dapat dibatalkan.'
                );
            }

            if (!Carbon::parse($transaksi->tanggal)->isToday()) {
                throw new \Exception(
                    'Top Up Tunai hanya dapat dibatalkan pada hari transaksi.'
                );
            }

            if ($transaksi->status_transaksi === 'dibatalkan') {
                throw new \Exception(
                    'Transaksi sudah dibatalkan.'
                );
            }

            // LOCK SALDO
            $saldo = SaldoEduPay::where('user_id', $transaksi->user_id)
                ->where('user_type', $transaksi->user_type)
                ->lockForUpdate()
                ->first();

            if (!$saldo) {
                throw new \Exception(
                    'Data saldo tidak ditemukan!'
                );
            }

            // PROSES PEMBATALAN
            $this->processPembatalan($transaksi, $saldo);

            DB::commit();

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
        return view('livewire.transaksi-edu-pay-siswa.delete');
    }
}
