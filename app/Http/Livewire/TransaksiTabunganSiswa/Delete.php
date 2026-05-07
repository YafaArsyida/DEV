<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\SaldoTabungan;
use App\Models\TransaksiTabungan;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ms_transaksi_tabungan_id;

    protected $listeners = [
        'confirmDeleteTabungan'
    ];

    public function confirmDeleteTabungan($id)
    {
        $transaksi = TransaksiTabungan::find($id);

        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi dimuat'
        ]);

        $this->ms_transaksi_tabungan_id = $id;
    }

    protected function validateSaldoDelete($transaksi, $saldo)
    {
        $saldoValue = $saldo->saldo_tabungan;

        $saldoSetelahHapus = $transaksi->jenis_transaksi === 'setoran'
            ? $saldoValue - $transaksi->nominal
            : $saldoValue + $transaksi->nominal;

        if ($saldoSetelahHapus < 0) {
            throw new \Exception('Saldo sudah digunakan');
        }
    }

    protected function processDelete($transaksi, $saldo)
    {
        // update saldo dulu
        if ($transaksi->jenis_transaksi === 'setoran') {
            $saldo->decrement('saldo_tabungan', $transaksi->nominal);
        } else {
            $saldo->increment('saldo_tabungan', $transaksi->nominal);
        }

        // soft delete jurnal (1 query)
        AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', [
            $transaksi->akuntansi_jurnal_detail_debit_id,
            $transaksi->akuntansi_jurnal_detail_kredit_id,
        ])->delete();

        // delete transaksi
        $transaksi->delete();
    }

    protected function afterDeleteSuccess()
    {
        $this->emit('successTransaksiTabungan');

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalDeleteTabungan'
        ]);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi berhasil dihapus.'
        ]);
    }

    public function deleteTabungan()
    {
        DB::beginTransaction();

        try {
            
            $transaksi = TransaksiTabungan::lockForUpdate()
                ->find($this->ms_transaksi_tabungan_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            $saldo = SaldoTabungan::where('user_id', $transaksi->user_id)
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
        return view('livewire.transaksi-tabungan-siswa.delete');
    }
}
