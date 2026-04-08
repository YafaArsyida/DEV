<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\SaldoEduPay;
use App\Models\TransaksiEduPay;
use App\Models\TransaksiTagihanSiswa;
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

        if ($total <= 0) return;

        $namaJenis = $detailTransaksi
            ->map(fn($d) => $d->nama_jenis_tagihan_siswa())
            ->unique()
            ->implode(', ');

        $deskripsi = "Pengembalian dana Rp {$total} siswa {$this->nama_siswa}, {$namaJenis}";

        // 🔥 BASE DATA JURNAL
        $base = [
            'nominal' => $total,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => auth()->id(),
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'is_canceled' => 'active',
            'deskripsi' => $deskripsi,
        ];

        // 🔥 JURNAL DEBIT (BANK)
        $debitId = AkuntansiJurnalDetail::create([
            ...$base,
            'kode_rekening' => 11002,
            'posisi' => 'debit',
        ])->akuntansi_jurnal_detail_id;

        // 🔥 JURNAL KREDIT (EDUPAY SISWA)
        $kreditId = AkuntansiJurnalDetail::create([
            ...$base,
            'kode_rekening' => 22002,
            'posisi' => 'kredit',
        ])->akuntansi_jurnal_detail_id;

        // 🔥 SIMPAN KE EDUPAY
        TransaksiEduPay::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_pengguna_id' => auth()->id(),
            'jenis_transaksi' => 'pengembalian dana',
            'nominal' => $total,
            'tanggal' => now(),
            'akuntansi_jurnal_detail_debit_id' => $debitId,
            'akuntansi_jurnal_detail_kredit_id' => $kreditId,
            'deskripsi' => $deskripsi,
        ]);

        $saldo = SaldoEduPay::getSaldo($this->ms_siswa_id, 'siswa');

        if ($saldo) {
            $saldo->increment('saldo_edupay', $total);
            $this->emit('successTransaksiEduPay');
        }
    }

    protected function deleteTransaksiEduPay($transaksi)
    {
        $edupay = TransaksiEduPay::where([
            'akuntansi_jurnal_detail_debit_id' => $transaksi->akuntansi_jurnal_detail_debit_id,
            'akuntansi_jurnal_detail_kredit_id' => $transaksi->akuntansi_jurnal_detail_kredit_id,
        ])->first();

        if (!$edupay) return;

        $total = $edupay->nominal;

        // 🔥 hapus transaksi edupay
        $edupay->delete();

        // 🔥 update saldo
        $saldo = SaldoEduPay::getSaldo($this->ms_siswa_id, 'siswa');

        if ($saldo) {
            $saldo->increment('saldo_edupay', $total);
            $this->emit('successTransaksiEduPay');
        }
    }

    protected function afterDeleteSuccess()
    {
        $this->emit('refreshTagihanSiswa');

        // Tampilkan notifikasi sukses
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi dan detail transaksi berhasil dihapus.',
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalDeleteTransaksi'
        ]);
    }

    public function deleteTransaksi()
    {
        DB::beginTransaction();

        try {
            // Validasi jika ID transaksi tidak ditemukan
            $transaksi = TransaksiTagihanSiswa::lockForUpdate()
                ->find($this->ms_transaksi_tagihan_siswa_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            // Hapus jurnal
            $jurnalIds = [
                $transaksi->akuntansi_jurnal_detail_debit_id,
                $transaksi->akuntansi_jurnal_detail_kredit_id,
            ];

            AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)->get()->each(function ($jurnal) {
                $jurnal->delete();
            });
            // hapus jurnal

            // Ambil detail transaksi yang terkait
            $detailTransaksi = DetailTransaksiTagihanSiswa::where(
                'ms_transaksi_tagihan_siswa_id',
                $transaksi->ms_transaksi_tagihan_siswa_id
            )->get();

            // ini dulu baru perbarui status agar dibayrkannya berkurang
            // update dan hapus detail transaksi terlebih dahulu
            foreach ($detailTransaksi as $detail) {
                $detail->update([
                    'deskripsi' => "Dihapus oleh {$this->nama_petugas}"
                ]);

                $detail->delete();
            }

            // Perbarui status tagihan setelah transaksi dihapus
            foreach ($detailTransaksi as $detail) {
                $tagihan = $detail->ms_tagihan_siswa;

                if ($tagihan) {
                    $dibayar = $tagihan->jumlah_sudah_dibayar();

                    $status = 'Belum Dibayar';

                    if ($dibayar > 0 && $dibayar < $tagihan->jumlah_tagihan_siswa) {
                        $status = 'Masih Dicicil';
                    } elseif ($dibayar >= $tagihan->jumlah_tagihan_siswa) {
                        $status = 'Lunas';
                    }

                    $tagihan->update([
                        'status' => $status,
                        'deskripsi' => "Update setelah delete oleh {$this->nama_petugas}"
                    ]);
                }
            }

            // Refund atau hapus dana edupay
            if ($transaksi->metode_pembayaran === 'Transfer ke Rekening Sekolah') {
                $this->handleRefund($transaksi, $detailTransaksi);
            }

            if ($transaksi->metode_pembayaran === 'EduPay') {
                $this->deleteTransaksiEduPay($transaksi);
            }

            // Hapus transaksi
            $transaksi->update([
                'deskripsi' => $transaksi->deskripsi . "Transaksi dihapus oleh petugas {$this->nama_petugas}",
            ]);

            $transaksi->delete();
            // Hapus transaksi

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
