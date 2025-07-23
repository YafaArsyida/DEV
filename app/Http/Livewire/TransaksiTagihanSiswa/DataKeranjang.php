<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\EduPaySiswa;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use App\Models\TransaksiTagihanSiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Exception;

class DataKeranjang extends Component
{
    public $ms_penempatan_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $nama_siswa;

    public $deskripsi;
    public $metode_pembayaran = 'Teller Tunai';

    public $totalKeranjang = 0;

    public $siswaSelected = false; // Status apakah siswa sudah dipilih

    public $currentTransaksiId;

    protected $listeners = [
        'siswaSelected', // Listener untuk parameter siswa yang dipilih
        'keranjangUpdated',
    ];

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;
        $penempatanSiswa = PenempatanSiswa::find($ms_penempatan_siswa_id);

        if ($penempatanSiswa) {
            $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id; // Dapatkan jenjang dari data penempatan
            $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id; // Dapatkan jenjang dari data penempatan
            $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa;

            $this->siswaSelected = true; // Tandai bahwa siswa telah dipilih
        } else {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data penempatan siswa tidak ditemukan.']);
            $this->siswaSelected = false; // Reset jika data tidak valid
        }
    }

    public function keranjangUpdated()
    {
        $this->emitSelf('$refresh'); //lebih ringan
        $this->currentTransaksiId = null;
    }

    public function hapusKeranjang($ms_keranjang_tagihan_siswa_id)
    {
        // Cari keranjang berdasarkan ID
        $keranjang = KeranjangTagihanSiswa::find($ms_keranjang_tagihan_siswa_id);

        // Validasi apakah keranjang ditemukan
        if (!$keranjang) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Keranjang tidak ditemukan.']);
            return;
        }

        // Hapus data keranjang
        $keranjang->delete();

        // Cari tagihan terkait berdasarkan ID
        $tagihan = TagihanSiswa::find($keranjang->ms_tagihan_siswa_id);

        // Validasi apakah tagihan ditemukan
        if ($tagihan) {
            $jumlah_sudah_dibayar = $tagihan->jumlah_sudah_dibayar(); // Hitung jumlah yang sudah dibayarkan

            // Tentukan status baru berdasarkan jumlah yang sudah dibayarkan
            $statusBaru = $jumlah_sudah_dibayar > 0 ? 'Masih Dicicil' : 'Belum Dibayar';

            // Update status dan deskripsi tagihan
            $tagihan->update([
                'status' => $statusBaru, // Update status
                'deskripsi' => $jumlah_sudah_dibayar > 0
                    ? 'Transaksi dibatalkan, tagihan masih memiliki pembayaran sebagian.'
                    : 'Transaksi dibatalkan, tagihan kembali menjadi belum dibayar.',
            ]);
        }

        // Emit refresh ke komponen Livewire
        $this->emitSelf('$refresh');
        $this->emit('tagihanUpdated'); // Emit event ke komponen Livewire terkait

        // Tampilkan notifikasi sukses
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil dihapus dari keranjang.']);
    }

    public function simpanTransaksi()
    {
        try {
            $ms_pengguna_id = Auth::id();

            $keranjang = KeranjangTagihanSiswa::where('ms_pengguna_id', $ms_pengguna_id)
                ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                ->get();

            if ($keranjang->isEmpty()) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Keranjang kosong, tidak ada transaksi yang bisa disimpan.']);
                return;
            }

            DB::beginTransaction();

            $detailTagihan = collect($keranjang)->map(function ($item) {
                return $item->nama_jenis_tagihan_siswa();
            })->join(', ');

            $deskripsiJurnal = "Pembayaran tagihan " . $this->nama_siswa . ", $this->metode_pembayaran terdiri dari: " . $detailTagihan . ".";

            $kode_rekening_kas = 11001;
            $kode_rekening_bank = 11002;
            $kode_rekening_piutang = 12001;
            $kode_rekening_edupay_siswa = 22002;

            if ($this->metode_pembayaran === 'Teller Tunai') {
                $debitAkunId = $kode_rekening_kas; // ID Akun Kas
            } elseif ($this->metode_pembayaran === 'Transfer ke Rekening Sekolah') {
                $debitAkunId = $kode_rekening_bank; // ID Akun Bank
            } elseif ($this->metode_pembayaran === 'EduPay') {
                $debitAkunId = $kode_rekening_edupay_siswa;
            } else {
                throw new Exception('Metode pembayaran tidak valid.');
            }

            // Insert ke Jurnal Detail - Debit (Kas)
            $jurnalDetailDebit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $debitAkunId,
                'posisi' => 'debit',
                'nominal' => $this->totalKeranjang, // Nominal pembayaran
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ]);

            // Insert ke Jurnal Detail - Kredit (Piutang Siswa)
            $jurnalDetailKredit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $kode_rekening_piutang,
                'posisi' => 'kredit',
                'nominal' => $this->totalKeranjang, // Nominal pembayaran
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ]);

            // Jika metode pembayaran adalah EduPay
            if ($this->metode_pembayaran == 'EduPay') {
                $penempatanSiswa = PenempatanSiswa::find($this->ms_penempatan_siswa_id);

                if (!$penempatanSiswa) {
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data penempatan siswa tidak ditemukan.']);
                    return;
                }

                $totalBayar = $this->totalKeranjang;
                $saldoEduPay = $penempatanSiswa->ms_siswa->saldo_edupay_siswa();

                if ($saldoEduPay < $totalBayar) {
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo EduPay tidak cukup.']);
                    return;
                }

                $deskripsiEduPay = $keranjang->map(function ($item) {
                    return $item->nama_jenis_tagihan_siswa();
                })->join(', ');

                $this->simpanTransaksiEduPay($penempatanSiswa->ms_siswa_id, $this->ms_penempatan_siswa_id, $totalBayar, $deskripsiEduPay, $jurnalDetailDebit->akuntansi_jurnal_detail_id, $jurnalDetailKredit->akuntansi_jurnal_detail_id);
            }

            $transaksi = TransaksiTagihanSiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'tanggal_transaksi' => now(),
                'metode_pembayaran' => $this->metode_pembayaran,
                'deskripsi' => $this->deskripsi,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDetailDebit->akuntansi_jurnal_detail_id,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalDetailKredit->akuntansi_jurnal_detail_id,
            ]);

            $this->currentTransaksiId = $transaksi->ms_transaksi_tagihan_siswa_id;

            foreach ($keranjang as $item) {
                $tagihan = $item->ms_tagihan_siswa;

                $sisa_tagihan = $tagihan->jumlah_tagihan_siswa - ($item->jumlah_bayar + $tagihan->jumlah_sudah_dibayar());
                $status = $sisa_tagihan > 0 ? 'Masih Dicicil' : 'Lunas';
                $deskripsi = $status === 'Lunas'
                    ? "Tagihan telah lunas sebesar Rp {$tagihan->jumlah_tagihan_siswa}."
                    : "Pembayaran cicilan sebesar Rp {$item->jumlah_bayar}. Riwayat Rp {$tagihan->jumlah_sudah_dibayar()} Sisa tagihan Rp {$sisa_tagihan}.";

                DetailTransaksiTagihanSiswa::create([
                    'ms_transaksi_tagihan_siswa_id' => $transaksi->ms_transaksi_tagihan_siswa_id,
                    'ms_tagihan_siswa_id' => $item->ms_tagihan_siswa_id,
                    'jumlah_bayar' => $item->jumlah_bayar,
                    'deskripsi' => $deskripsi,
                ]);

                $tagihan->update([
                    'status' => $status,
                    'deskripsi' => $deskripsi,
                ]);
            }
            KeranjangTagihanSiswa::where('ms_pengguna_id', $ms_pengguna_id)
                ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                ->delete();

            $this->metode_pembayaran = 'Teller Tunai';
            $this->deskripsi = '';

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi berhasil disimpan.']);
            $this->emit('refreshSaldo');
            $this->emit('tagihanUpdated');
            $this->emitSelf('$refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function simpanTransaksiEduPay($ms_siswa_id, $ms_penempatan_siswa_id, $totalBayar, $deskripsiEduPay, $akuntansi_jurnal_detail_debit_id, $akuntansi_jurnal_detail_kredit_id)
    {
        // Simpan transaksi EduPay dengan jenis transaksi 'pembayaran'
        EduPaySiswa::create([
            'ms_siswa_id' => $ms_siswa_id,
            'ms_penempatan_siswa_id' => $ms_penempatan_siswa_id,
            'ms_pengguna_id' => Auth::id(),
            'jenis_transaksi' => 'pembayaran',
            'nominal' => $totalBayar,
            'tanggal' => now(),
            'akuntansi_jurnal_detail_debit_id' => $akuntansi_jurnal_detail_debit_id,
            'akuntansi_jurnal_detail_kredit_id' => $akuntansi_jurnal_detail_kredit_id,
            'deskripsi' => $deskripsiEduPay, // Atur deskripsi sesuai dengan pembayaran
        ]);
    }

    public function cetakTransaksi($currentTransaksiId)
    {
        // Dispatch event alertify sukses
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kuitansi sedang diproses.']);

        // Menggunakan route untuk mengarahkan ke controller cetak
        $url = route('transaksi.tagihan-siswa.kuitansiPDF', [
            'selectedJenjang' => $this->ms_jenjang_id,
            'transaksiId' => $currentTransaksiId
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
        $this->currentTransaksiId = null;
    }

    public function render()
    {
        $keranjangs = [];
        $this->totalKeranjang = 0;

        if ($this->siswaSelected) {
            $ms_pengguna_id = auth()->id();

            // Ambil data keranjang berdasarkan ms_pengguna_id dan ms_penempatan_siswa_id
            $keranjangs = KeranjangTagihanSiswa::where('ms_pengguna_id', $ms_pengguna_id)
                ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                ->get();
            // Hitung total jumlah_bayar
            $this->totalKeranjang = $keranjangs->sum('jumlah_bayar');
        }

        return view('livewire.transaksi-tagihan-siswa.data-keranjang', [
            'keranjangs' => $keranjangs,
            'totalKeranjang' => $this->totalKeranjang,
        ]);
    }
}
