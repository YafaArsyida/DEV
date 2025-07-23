<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\EduPaySiswa;
use App\Models\PenempatanSiswa;
use App\Models\TransaksiTagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Exception;

class DeleteTransaksi extends Component
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
        $transaksi = TransaksiTagihanSiswa::findOrFail($ms_transaksi_tagihan_siswa_id);
        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
            return;
        }
        $penempatanSiswa = $transaksi->ms_penempatan_siswa;

        $this->ms_penempatan_siswa_id = $transaksi->ms_penempatan_siswa_id;
        $this->ms_siswa_id = $penempatanSiswa->ms_siswa_id;

        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id ?? null;
        $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa ?? null;

        $this->ms_transaksi_tagihan_siswa_id = $ms_transaksi_tagihan_siswa_id;
    }

    public function deleteTransaksi()
    {
        try {
            // Validasi jika ID transaksi tidak ditemukan
            $transaksi = TransaksiTagihanSiswa::find($this->ms_transaksi_tagihan_siswa_id);
            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan.');
            }

            // Hapus jurnal
            $jurnalIds = [
                $transaksi->akuntansi_jurnal_detail_debit_id,
                $transaksi->akuntansi_jurnal_detail_kredit_id,
            ];

            // Validasi dan soft delete jurnal
            AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)->get()->each(function ($jurnal) {
                $jurnal->delete();
            });


            // Ambil detail transaksi yang terkait
            $detailTransaksi = DetailTransaksiTagihanSiswa::where('ms_transaksi_tagihan_siswa_id', $this->ms_transaksi_tagihan_siswa_id)->get();

            // Ambil nama jenis tagihan untuk deskripsi pengembalian dana
            $namaJenisTagihan = $detailTransaksi->map->nama_jenis_tagihan_siswa()->unique()->implode(', ');

            $penempatanSiswa = PenempatanSiswa::find($transaksi->ms_penempatan_siswa_id);

            $totalPengembalian = $detailTransaksi->sum('jumlah_bayar');

            // Hapus semua detail transaksi terlebih dahulu
            foreach ($detailTransaksi as $detail) {
                $detail->deskripsi = "Transaksi dihapus oleh petugas {$this->nama_petugas}"; // Set deskripsi
                $detail->save();

                $detail->delete();
            }

            // Perbarui status tagihan setelah transaksi dihapus
            foreach ($detailTransaksi as $detail) {
                $tagihan = $detail->ms_tagihan_siswa;
                if ($tagihan) {
                    $jumlah_sudah_dibayar = $tagihan->jumlah_sudah_dibayar();

                    // Tentukan status tagihan setelah penghapusan transaksi
                    $status = 'Belum Dibayar';
                    if ($jumlah_sudah_dibayar > 0 && $jumlah_sudah_dibayar < $tagihan->jumlah_tagihan_siswa) {
                        $status = 'Masih Dicicil';
                    } elseif ($jumlah_sudah_dibayar >= $tagihan->jumlah_tagihan_siswa) {
                        $status = 'Lunas';
                    }

                    // Update status tagihan
                    $tagihan->update([
                        'status' => $status,
                        'deskripsi' => "Status diperbarui setelah penghapusan transaksi oleh petugas {$this->nama_petugas}",
                    ]);
                }
            }

            // Perbarui deskripsi transaksi utama sebelum dihapus
            $transaksi->update([
                'deskripsi' => $transaksi->deskripsi . "Transaksi dihapus oleh petugas {$this->nama_petugas}",
            ]);


            // Periksa apakah metode pembayaran adalah EduPay
            if ($transaksi->metode_pembayaran === 'Transfer ke Rekening Sekolah') {
                if ($penempatanSiswa && $penempatanSiswa->ms_siswa) {

                    $ms_pengguna_id = Auth::id();
                    // simpan jurnal
                    $kode_rekening_bank = 11002;
                    $kode_rekening_edupay_siswa = 22002;
                    $deskripsiJurnal = "Pengembalian Dana Transfer Rp {$totalPengembalian} siswa {$this->nama_siswa}, {$namaJenisTagihan}";

                    // Data untuk jurnal debit
                    $jurnalDebit = [
                        'kode_rekening' => $kode_rekening_bank,
                        'posisi' => 'debit',
                        'nominal' => $totalPengembalian,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $this->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsiJurnal,
                    ];
                    $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

                    // Data untuk jurnal kredit
                    $jurnalKredit = [
                        'kode_rekening' => $kode_rekening_edupay_siswa,
                        'posisi' => 'kredit',
                        'nominal' => $totalPengembalian,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $this->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsiJurnal,
                    ];
                    $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

                    EduPaySiswa::create([
                        'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                        'ms_siswa_id'            => $this->ms_siswa_id,
                        'ms_pengguna_id'         => $ms_pengguna_id,
                        'jenis_transaksi'        => 'pengembalian dana',
                        'nominal'                => $totalPengembalian,
                        'deskripsi'              => $deskripsiJurnal,
                        'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                        'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                        'tanggal'                => now(),
                    ]);
                }
            }

            // hapus riwayat ms edupay
            if ($transaksi->metode_pembayaran === 'EduPay') {
                EduPaySiswa::where('akuntansi_jurnal_detail_debit_id', $transaksi->akuntansi_jurnal_detail_debit_id)
                    ->where('akuntansi_jurnal_detail_kredit_id', $transaksi->akuntansi_jurnal_detail_kredit_id)
                    ->delete();
            }

            // Hapus transaksi utama
            $transaksi->delete();

            // Emit event untuk refresh data di tabel atau tampilan
            $this->emit('tagihanUpdated');
            $this->emit('historiUpdated');
            $this->emit('refreshSaldo');

            // Tampilkan notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi dan detail transaksi berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            // Tampilkan pesan error jika terjadi masalah
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }

        // Sembunyikan modal setelah penghapusan
        $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteTransaksi']);
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.delete-transaksi');
    }
}
