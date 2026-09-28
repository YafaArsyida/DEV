<?php

namespace App\Http\Livewire\Keuangan\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\SaldoEduPay;
use App\Models\TagihanSiswa;
use App\Models\TransaksiEduPay;
use App\Models\TransaksiTagihanSiswa;
use App\Services\AccountingService;
use Livewire\Component;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    public $ms_transaksi_tagihan_siswa_id;
    public $ms_penempatan_siswa_id;
    public $ms_siswa_id;

    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    public $nama_siswa;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'loadTransaksiDelete'
    ];

    public function loadTransaksiDelete($ms_transaksi_tagihan_siswa_id)
    {
        $transaksi = TransaksiTagihanSiswa::find($ms_transaksi_tagihan_siswa_id);
        
        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi dimuat'
        ]);

        $penempatanSiswa = $transaksi->ms_penempatan_siswa;

        $this->ms_penempatan_siswa_id = $transaksi->ms_penempatan_siswa_id;
        $this->ms_siswa_id = $penempatanSiswa->ms_siswa_id;

        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id ?? null;
        $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa ?? null;

        $this->ms_transaksi_tagihan_siswa_id = $ms_transaksi_tagihan_siswa_id;
    }

    protected function handleRefund($transaksi, $detailTransaksi)
    {
        $total = $detailTransaksi->sum('jumlah_bayar');

        if ($total <= 0) {
            return;
        }

        // =========================================================
        // 1. DESKRIPSI REFUND
        // =========================================================
        $namaJenis = $detailTransaksi
            ->map(fn($d) => $d->nama_jenis_tagihan_siswa())
            ->unique()
            ->implode(', ');

        $deskripsi = sprintf(
            'Pengembalian dana Rp %s siswa %s, %s',
            number_format($total, 0, ',', '.'),
            $this->nama_siswa,
            $namaJenis
        );

        // =========================================================
        // 2. BUAT JURNAL PENGEMBALIAN DANA
        // =========================================================
        $jurnal = AccountingService::create([
            'tanggal' => now(),
            'deskripsi' => $deskripsi,
            'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
            'ms_tahun_ajaran_id' => $transaksi->akuntansi_jurnal->ms_tahun_ajaran_id ?? $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $transaksi->akuntansi_jurnal->ms_jenjang_id ?? $this->ms_jenjang_id,
            'ms_departemen_id' => $transaksi->akuntansi_jurnal->ms_departemen_id ?? 'SEKOLAH',

            'detail' => [
                // Debit Bank Sekolah
                [
                    'kode_rekening' => 11002,
                    'posisi' => 'debit',
                    'nominal' => $total,
                ],

                // Kredit Saldo EduPay Siswa
                [
                    'kode_rekening' => 22002,
                    'posisi' => 'kredit',
                    'nominal' => $total,
                ],
            ],
        ]);

        // =========================================================
        // 3. SIMPAN TRANSAKSI EDUPAY
        // =========================================================
        TransaksiEduPay::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
            'jenis_transaksi' => 'pengembalian dana',
            'nominal' => $total,
            'tanggal' => now(),
            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
            'status_transaksi' => 'aktif',
            'deskripsi' => $deskripsi,
        ]);

        // =========================================================
        // 4. TAMBAH SALDO EDUPAY
        // =========================================================
        $saldo = SaldoEduPay::lockForUpdate()
            ->where('user_id', $this->ms_siswa_id)
            ->where('user_type', 'siswa')
            ->first();

        if (!$saldo) {
            throw new \Exception(
                'Saldo EduPay siswa tidak ditemukan.'
            );
        }

        $saldo->increment('saldo_edupay', $total);

        $this->emit('successTransaksiEduPay');
    }

    protected function deleteTransaksiEduPay($transaksi, $jurnalReversalId)
    {
        if (!$transaksi->akuntansi_jurnal_id) {
            return;
        }

        $edupay = TransaksiEduPay::lockForUpdate()
            ->where(
                'akuntansi_jurnal_id',
                $transaksi->akuntansi_jurnal_id
            )
            ->first();

        if (!$edupay) {
            return;
        }

        // =========================================================
        // 1. KEMBALIKAN SALDO EDUPAY
        // =========================================================
        $total = $edupay->nominal;

        $saldo = SaldoEduPay::lockForUpdate()
            ->where('user_id', $edupay->user_id)
            ->where('user_type', 'siswa')
            ->first();

        if (!$saldo) {
            throw new \Exception(
                'Saldo EduPay siswa tidak ditemukan.'
            );
        }

        $saldo->increment('saldo_edupay', $total);

        // =========================================================
        // 2. TANDAI TRANSAKSI EDUPAY SEBAGAI DIBATALKAN
        // =========================================================
        $edupay->update([
            'status_transaksi' => 'dibatalkan',
            'akuntansi_jurnal_reversal_id' => $jurnalReversalId,
            'deskripsi' => $edupay->deskripsi
                . " Transaksi dibatalkan oleh petugas {$this->nama_petugas}",
        ]);

        $this->emit('successTransaksiEduPay');
    }

    protected function afterDeleteSuccess()
    {
        $this->emit('refreshTagihanSiswa');

        // Tampilkan notifikasi sukses
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi berhasil dihapus.',
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalDeleteTransaksi'
        ]);
    }

    public function deleteTransaksi()
    {
        DB::beginTransaction();

        try {
            // =========================================================
            // 1. AMBIL TRANSAKSI + LOCK
            // =========================================================
            $transaksi = TransaksiTagihanSiswa::lockForUpdate()
                ->find($this->ms_transaksi_tagihan_siswa_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            // Jangan boleh membatalkan transaksi yang sudah dibatalkan
            if ($transaksi->status_transaksi === 'dibatalkan') {
                throw new \Exception('Transaksi sudah dibatalkan.');
            }

            // =========================================================
            // 2. AMBIL DETAIL TRANSAKSI + LOCK
            // =========================================================
            $detailTransaksi = DetailTransaksiTagihanSiswa::where(
                'ms_transaksi_tagihan_siswa_id',
                $transaksi->ms_transaksi_tagihan_siswa_id
            )
                ->lockForUpdate()
                ->get();

            if ($detailTransaksi->isEmpty()) {
                throw new \Exception('Detail transaksi tidak ditemukan!');
            }

            // =========================================================
            // 3. REVERSAL JURNAL
            // =========================================================
            if ($transaksi->akuntansi_jurnal_id) {

                $jurnalReversal = AccountingService::reverse(
                    $transaksi->akuntansi_jurnal_id,
                    [
                        'tanggal' => now(),
                        'deskripsi' => sprintf(
                            'Pembatalan pembayaran tagihan %s, %s oleh %s',
                            $this->nama_siswa,
                            $transaksi->metode_pembayaran,
                            $this->nama_petugas
                        ),
                        'ms_pengguna_id' => Auth::user()->ms_pengguna_id,
                    ]
                );

                $jurnalReversalId = $jurnalReversal->akuntansi_jurnal_id;

            } else {
                throw new \Exception(
                    'Jurnal transaksi tidak ditemukan. Pembatalan dibatalkan.'
                );
            }

            // =========================================================
            // 4. REFUND / KEMBALIKAN SALDO EDUPAY
            // =========================================================

            // Transfer ke Rekening Sekolah:
            // dana dikembalikan ke saldo EduPay.
            if ($transaksi->metode_pembayaran === 'Transfer ke Rekening Sekolah') {
                $this->handleRefund($transaksi, $detailTransaksi);
            }

            // EduPay:
            // saldo yang sebelumnya dipotong dikembalikan.
            //
            // Catatan:
            // Jurnal pembayaran sudah di-reverse DI ATAS.
            // Jangan melakukan AccountingService::reverse lagi di sini.
            if ($transaksi->metode_pembayaran === 'EduPay') {
                $this->deleteTransaksiEduPay(
                    $transaksi, $jurnalReversalId
                );
            }

            // =========================================================
            // 5. SIMPAN TAGIHAN YANG TERDAMPAK
            // =========================================================
            $tagihanIds = $detailTransaksi
                ->pluck('ms_tagihan_siswa_id')
                ->unique();

            // =========================================================
            // 6. TANDAI DETAIL SEBAGAI DIBATALKAN
            // =========================================================
            foreach ($detailTransaksi as $detail) {
                $detail->update([
                    'status_transaksi' => 'dibatalkan',
                    'deskripsi' => "Dibatalkan oleh {$this->nama_petugas}",
                ]);
            }

            // =========================================================
            // 7. UPDATE STATUS TAGIHAN
            // =========================================================
            foreach ($tagihanIds as $tagihanId) {

                $tagihan = TagihanSiswa::lockForUpdate()
                    ->find($tagihanId);

                if (!$tagihan) {
                    continue;
                }

                $dibayar = $tagihan->jumlah_sudah_dibayar();

                if ($dibayar <= 0) {
                    $status = 'Belum Dibayar';
                } elseif ($dibayar < $tagihan->jumlah_tagihan_siswa) {
                    $status = 'Masih Dicicil';
                } else {
                    $status = 'Lunas';
                }

                $tagihan->update([
                    'status' => $status,
                    'deskripsi' => "Update setelah pembatalan transaksi oleh {$this->nama_petugas}",
                ]);
            }

            // =========================================================
            // 8. TANDAI TRANSAKSI UTAMA SEBAGAI DIBATALKAN
            // =========================================================
            $transaksi->update([
                'status_transaksi' => 'dibatalkan',
                'akuntansi_jurnal_reversal_id' => $jurnalReversalId,
                'deskripsi' => $transaksi->deskripsi
                    . " Transaksi dibatalkan oleh petugas {$this->nama_petugas}",
            ]);

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
        return view('livewire.keuangan.transaksi-tagihan-siswa.delete');
    }
}
