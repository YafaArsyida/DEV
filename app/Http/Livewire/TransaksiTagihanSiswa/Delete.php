<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\SaldoEduPay;
use App\Models\TagihanSiswa;
use App\Models\TransaksiEduPay;
use App\Models\TransaksiTagihanSiswa;
use App\Services\AccountingService;
use Livewire\Component;
use Exception;
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
        // BUAT JURNAL REFUND
        // =========================================================
        $jurnal = AccountingService::create([
            'tanggal' => now(),
            'deskripsi' => $deskripsi,
            'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_departemen_id' => 'SEKOLAH',

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

        // SIMPAN TRANSAKSI EDUPAY
        TransaksiEduPay::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
            'jenis_transaksi' => 'pengembalian dana',
            'nominal' => $total,
            'tanggal' => now(),
            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
            'deskripsi' => $deskripsi,
        ]);

        // =========================================================
        // TAMBAH SALDO EDUPAY
        // =========================================================
        $saldo = SaldoEduPay::getSaldo(
            $this->ms_siswa_id, 'siswa'
        );

        if ($saldo) {
            $saldo->increment(
                'saldo_edupay', $total
            );

            $this->emit('successTransaksiEduPay');
        }
    }

    protected function deleteTransaksiEduPay($transaksi)
    {
        if (!$transaksi->akuntansi_jurnal_id) {
            return;
        }

        $edupay = TransaksiEduPay::where(
            'akuntansi_jurnal_id', $transaksi->akuntansi_jurnal_id
        )->first();

        if (!$edupay) {
            return;
        }

        $total = $edupay->nominal;

        // Hapus transaksi EduPay
        $edupay->delete();

        // Kembalikan saldo EduPay
        $saldo = SaldoEduPay::getSaldo($this->ms_siswa_id, 'siswa');

        if ($saldo) {
            $saldo->increment(
                'saldo_edupay', $total
            );

            $this->emit('successTransaksiEduPay');
        }
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
            $transaksi = TransaksiTagihanSiswa::lockForUpdate()
                ->find($this->ms_transaksi_tagihan_siswa_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            // Ambil detail transaksi sebelum dihapus
            $detailTransaksi = DetailTransaksiTagihanSiswa::where(
                'ms_transaksi_tagihan_siswa_id',
                $transaksi->ms_transaksi_tagihan_siswa_id
            )->get();

            if ($detailTransaksi->isEmpty()) {
                throw new \Exception('Detail transaksi tidak ditemukan!');
            }

            // =========================================================
            // REFUND / KEMBALIKAN SALDO EDUPAY
            // =========================================================
            // Untuk transaksi Transfer ke Rekening Sekolah,
            // dana dikembalikan ke saldo EduPay.
            if ($transaksi->metode_pembayaran === 'Transfer ke Rekening Sekolah') {
                $this->handleRefund($transaksi, $detailTransaksi);
            }

            // Untuk transaksi EduPay,
            // saldo yang sebelumnya dipotong dikembalikan.
            if ($transaksi->metode_pembayaran === 'EduPay') {
                $this->deleteTransaksiEduPay($transaksi);
            }

            // HAPUS JURNAL TRANSAKSI
            if ($transaksi->akuntansi_jurnal_id) {
                AccountingService::delete(
                    $transaksi->akuntansi_jurnal_id
                );
            }

            // SIMPAN TAGIHAN YANG TERDAMPAK
            $tagihanIds = $detailTransaksi
                ->pluck('ms_tagihan_siswa_id')
                ->unique();

            // HAPUS DETAIL TRANSAKSI
            foreach ($detailTransaksi as $detail) {

                $detail->update([
                    'deskripsi' => "Dihapus oleh {$this->nama_petugas}",
                ]);

                $detail->delete();
            }

            // =========================================================
            // UPDATE STATUS TAGIHAN
            // =========================================================
            foreach ($tagihanIds as $tagihanId) {

                $tagihan = TagihanSiswa::find($tagihanId);

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
                    'deskripsi' => "Update setelah delete transaksi oleh {$this->nama_petugas}",
                ]);
            }

            // =========================================================
            // HAPUS TRANSAKSI UTAMA
            // =========================================================
            $transaksi->update([
                'deskripsi' => $transaksi->deskripsi . " Transaksi dihapus oleh petugas {$this->nama_petugas}",
            ]);

            $transaksi->delete();

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
        return view('livewire.transaksi-tagihan-siswa.delete');
    }
}
