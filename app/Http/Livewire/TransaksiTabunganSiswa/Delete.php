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
        $this->ms_transaksi_tabungan_id = $id;
    }

    protected function validateSaldoDelete($transaksi, $saldo)
    {
        $saldoValue = $saldo->saldo_tabungan;

        $saldoSetelahHapus = $transaksi->jenis_transaksi === 'setoran'
            ? $saldoValue - $transaksi->nominal
            : $saldoValue + $transaksi->nominal;

        if ($saldoSetelahHapus < 0) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Saldo sudah digunakan'
            ]);
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
            
            $transaksi = TransaksiTabungan::find($this->ms_transaksi_tabungan_id);

            if (!$transaksi) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Transaksi tidak ditemukan!'
                ]);
                return;
            }

            $saldo = SaldoTabungan::getSaldo(
                $transaksi->user_id,
                $transaksi->user_type
            );

            $this->validateSaldoDelete($transaksi, $saldo);

            $this->processDelete($transaksi, $saldo);

            DB::commit();

            $this->afterDeleteSuccess();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.transaksi-tabungan-siswa.delete');
    }
}
