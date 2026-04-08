<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\SaldoEduPay;
use App\Models\TagihanSiswa;
use App\Models\TransaksiEduPay;
use App\Models\TransaksiTagihanSiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Exception;

class DataKeranjang extends Component
{
    public $keranjangs;
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
        'keranjangUpdated' => 'loadKeranjang',
    ];

    public function loadKeranjang()
    {
        if (!$this->siswaSelected) {
            $this->keranjangs = collect();
            $this->totalKeranjang = 0;
            return;
        }

        $this->keranjangs = KeranjangTagihanSiswa::with([
            'ms_tagihan_siswa:ms_tagihan_siswa_id,ms_jenis_tagihan_siswa_id,jumlah_tagihan_siswa',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa:ms_jenis_tagihan_siswa_id,nama_jenis_tagihan_siswa'
        ])
            ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->where('ms_pengguna_id', auth()->id())
            ->get([
                'ms_keranjang_tagihan_siswa_id',
                'ms_tagihan_siswa_id',
                'jumlah_bayar'
            ]);

        $this->totalKeranjang = $this->keranjangs->sum('jumlah_bayar');
    }
    
    public function siswaSelected($ms_penempatan_siswa_id)
    {
        $penempatanSiswa = PenempatanSiswa::with('ms_siswa')
            ->find($ms_penempatan_siswa_id);

        if (!$penempatanSiswa) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Data penempatan siswa tidak ditemukan.'
            ]);
            $this->siswaSelected = false;
            return;
        }

        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;
        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id;
        $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa;
        $this->siswaSelected = true;

        // 🔥 load keranjang sekali
        $this->loadKeranjang();
    }

    public function hapusKeranjang($id)
    {
        DB::beginTransaction();

        try {
            // 🔒 Ambil + lock keranjang
            $keranjang = KeranjangTagihanSiswa::lockForUpdate()
                ->find($id);

            if (!$keranjang) {
                throw new \Exception('Keranjang tidak ditemukan.');
            }

            // 🔒 Ambil + lock tagihan + sum pembayaran
            $tagihan = TagihanSiswa::lockForUpdate()
                ->withSum('dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar', 'jumlah_bayar')
                ->find($keranjang->ms_tagihan_siswa_id);

            // 🔥 Hapus keranjang
            $keranjang->delete();

            if ($tagihan) {
                $jumlahSudahDibayar = $tagihan->jumlah_sudah_dibayar ?? 0;

                $statusBaru = $jumlahSudahDibayar > 0
                    ? 'Masih Dicicil'
                    : 'Belum Dibayar';

                $tagihan->update([
                    'status' => $statusBaru,
                    'deskripsi' => $jumlahSudahDibayar > 0
                        ? 'Transaksi dibatalkan, masih ada pembayaran sebagian.'
                        : 'Transaksi dibatalkan, kembali belum dibayar.',
                ]);
            }

            DB::commit();

            // 🔥 Update local state 
            $this->loadKeranjang();

            // 🔥 Emit event ringan
            $this->emit('reloadTagihanSiswa');

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil hapus dari keranjang.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function simpanTransaksi()
    {
        DB::beginTransaction();

        try {
            $ms_pengguna_id = Auth::id();

            // 🔒 Ambil keranjang + lock
            $keranjang = KeranjangTagihanSiswa::lockForUpdate()
                ->with([
                    'ms_tagihan_siswa' => function ($q) {
                        $q->lockForUpdate()
                            ->withSum('dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar', 'jumlah_bayar');
                    }
                ])
                ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                // ->where('ms_pengguna_id', $ms_pengguna_id)
                ->get();

            if ($keranjang->isEmpty()) {
                throw new \Exception('Keranjang kosong.');
            }

            $totalBayar = $keranjang->sum('jumlah_bayar');

            // 🔥 Validasi metode pembayaran
            $mapAkun = [
                'Teller Tunai' => 11001,
                'Transfer ke Rekening Sekolah' => 11002,
                'EduPay' => 22002,
            ];

            if (!isset($mapAkun[$this->metode_pembayaran])) {
                throw new \Exception('Metode pembayaran tidak valid.');
            }

            $debitAkunId = $mapAkun[$this->metode_pembayaran];
            $kode_rekening_piutang = 12001;

            // 🔥 Deskripsi
            $detailTagihan = $keranjang->map(function ($item) {
                return $item->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? '-';
            })->join(', ');

            $deskripsiJurnal = "Pembayaran tagihan {$this->nama_siswa}, {$this->metode_pembayaran}: {$detailTagihan}";

            // 🔥 Insert jurnal
            $jurnalDebit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $debitAkunId,
                'posisi' => 'debit',
                'nominal' => $totalBayar,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ]);

            $jurnalKredit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $kode_rekening_piutang,
                'posisi' => 'kredit',
                'nominal' => $totalBayar,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ]);

            // 🔥 EduPay validation (WAJIB throw, bukan return)
            if ($this->metode_pembayaran === 'EduPay') {
                $penempatan = PenempatanSiswa::lockForUpdate()->find($this->ms_penempatan_siswa_id);

                if (!$penempatan) {
                    throw new \Exception('Penempatan siswa tidak ditemukan.');
                }

                $saldo = SaldoEduPay::lockForUpdate()
                    ->where('user_id', $penempatan->ms_siswa_id)
                    ->where('user_type', 'siswa')
                    ->first();

                if (!$saldo) {
                    throw new \Exception('Saldo EduPay tidak ditemukan.');
                }

                if ($saldo->saldo_edupay < $totalBayar) {
                    throw new \Exception('Saldo EduPay tidak cukup.');
                }

                TransaksiEduPay::create([
                    'user_type' => 'siswa',
                    'user_id' => $penempatan->ms_siswa_id,
                    'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'jenis_transaksi' => 'pembayaran',
                    'nominal' => $totalBayar,
                    'tanggal' => now(),
                    'akuntansi_jurnal_detail_debit_id' => $jurnalDebit->akuntansi_jurnal_detail_id,
                    'akuntansi_jurnal_detail_kredit_id' => $jurnalKredit->akuntansi_jurnal_detail_id,
                    'deskripsi' => $deskripsiJurnal,
                ]);

                $saldo->decrement('saldo_edupay', $totalBayar);
                $this->emit('successTransaksiEduPay');
            }

            // 🔥 Simpan transaksi utama
            $transaksi = TransaksiTagihanSiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'tanggal_transaksi' => now(),
                'metode_pembayaran' => $this->metode_pembayaran,
                'deskripsi' => $this->deskripsi,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebit->akuntansi_jurnal_detail_id,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKredit->akuntansi_jurnal_detail_id,
            ]);

            // 🔥 Loop TANPA query tambahan
            foreach ($keranjang as $item) {
                $tagihan = $item->ms_tagihan_siswa;

                $sudah = $tagihan->jumlah_sudah_dibayar ?? 0;
                $sisa = $tagihan->jumlah_tagihan_siswa - ($sudah + $item->jumlah_bayar);

                $status = $sisa > 0 ? 'Masih Dicicil' : 'Lunas';

                DetailTransaksiTagihanSiswa::create([
                    'ms_transaksi_tagihan_siswa_id' => $transaksi->ms_transaksi_tagihan_siswa_id,
                    'ms_tagihan_siswa_id' => $item->ms_tagihan_siswa_id,
                    'jumlah_bayar' => $item->jumlah_bayar,
                    'deskripsi' => $status,
                ]);

                $tagihan->update([
                    'status' => $status,
                ]);
            }

            // 🔥 Hapus keranjang
            KeranjangTagihanSiswa::where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                // ->where('ms_pengguna_id', $ms_pengguna_id)
                ->delete();

            DB::commit();

            // 🔥 Reset local state
            $this->loadKeranjang();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi berhasil disimpan.'
            ]);

            $this->emit('refreshTagihanSiswa');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
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
        return view('livewire.transaksi-tagihan-siswa.data-keranjang', [
            'keranjangs' => $this->keranjangs ?? [],
            'totalKeranjang' => $this->totalKeranjang ?? 0,
        ]);
    }
}
