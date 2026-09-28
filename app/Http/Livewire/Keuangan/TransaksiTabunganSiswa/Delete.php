<?php

namespace App\Http\Livewire\Keuangan\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\SaldoTabungan;
use App\Models\TransaksiTabungan;
use App\Services\AccountingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ms_transaksi_tabungan_id;
    public $nama_petugas;
    public $nama_user = null;

    protected $listeners = [
        'confirmDeleteTabungan'
    ];

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    public function confirmDeleteTabungan($id)
    {
        $transaksi = TransaksiTabungan::find($id);

        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }

        $this->ms_transaksi_tabungan_id = $id;

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
        // =========================================================
        // 1. VALIDASI JURNAL ASLI
        // =========================================================
        if (!$transaksi->akuntansi_jurnal_id) {
            throw new \Exception(
                'Jurnal asli transaksi tidak ditemukan.'
            );
        }

        // =========================================================
        // 2. DESKRIPSI JURNAL REVERSAL
        // =========================================================
        $deskripsiJurnal = sprintf(
            'Pembatalan %s Tabungan Rp %s - %s',
            ucfirst($transaksi->jenis_transaksi),
            number_format($transaksi->nominal, 0, ',', '.'),
            $this->nama_user
        );

        // =========================================================
        // 3. BUAT JURNAL REVERSAL
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
        // 4. KOREKSI SALDO TABUNGAN
        // =========================================================
        if ($transaksi->jenis_transaksi === 'setoran') {

            // Setoran dibatalkan → saldo dikurangi
            $saldo->decrement(
                'saldo_tabungan', $transaksi->nominal
            );

        } elseif ($transaksi->jenis_transaksi === 'penarikan') {

            // Penarikan dibatalkan → saldo dikembalikan
            $saldo->increment(
                'saldo_tabungan', $transaksi->nominal
            );

        } else {

            throw new \Exception(
                'Jenis transaksi tabungan tidak valid.'
            );
        }

        // =========================================================
        // 5. UPDATE STATUS TRANSAKSI
        // =========================================================
        $transaksi->update([
            'status_transaksi' => 'dibatalkan',

            'akuntansi_jurnal_reversal_id' =>
                $jurnalPembatalan->akuntansi_jurnal_id,
        ]);
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
            // =====================================================
            // 1. LOCK TRANSAKSI
            // =====================================================
            $transaksi = TransaksiTabungan::lockForUpdate()
                ->find($this->ms_transaksi_tabungan_id);

            if (!$transaksi) {
                throw new \Exception(
                    'Transaksi tidak ditemukan!'
                );
            }

            // =====================================================
            // 2. VALIDASI 
            // =====================================================
            if ($transaksi->status_transaksi === 'dibatalkan') {
                throw new \Exception(
                    'Transaksi sudah dibatalkan.'
                );
            }

            if (!Carbon::parse($transaksi->tanggal)->isToday()) {
                throw new \Exception(
                    'Transaksi hanya dapat dibatalkan pada hari transaksi.'
                );
            }

            // =====================================================
            // 3. LOCK SALDO
            // =====================================================
            $saldo = SaldoTabungan::where('user_id', $transaksi->user_id)
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
            $this->processDelete(
                $transaksi, $saldo
            );

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
        return view('livewire.keuangan.transaksi-tabungan-siswa.delete');
    }
}
