<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiSmartCanteen;
use App\Models\KeranjangSmartCanteen;
use App\Models\TransaksiEduPay;
use App\Models\TransaksiSmartCanteen;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class KeranjangProduk extends Component
{
    public $totalKeranjang = 0;

    public $user_type;
    public $user_id;
    public $ms_penempatan_siswa_id;
    public $nama;
    public $nama_kelas;
    public $educard;
    public $saldo_edupay;

    public $nama_jabatan;

    public $metode_pembayaran = 'EduPay';

    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    protected $listeners = [
        'scanSuccess',
        'tambahKeranjang',
    ];

    public function scanSuccess($data)
    {
        $this->user_type                = $data['user_type'];   // 'siswa' atau 'pegawai'
        $this->user_id                  = $data['user_id'];     // ms_siswa_id atau ms_pegawai_id
        $this->ms_penempatan_siswa_id   = $data['ms_penempatan_siswa_id'];     // ms_siswa_id atau ms_pegawai_id
        $this->nama                     = $data['nama'];        // nama siswa/pegawai
        $this->nama_kelas               = $data['nama_kelas'];        // nama siswa/pegawai
        $this->educard                  = $data['educard'];
        $this->saldo_edupay             = $data['saldo_edupay'];

        $this->ms_jenjang_id            = $data['ms_jenjang_id'];
        $this->ms_tahun_ajar_id         = $data['ms_tahun_ajar_id'];

        $this->nama_jabatan               = $data['nama_jabatan'];        // nama siswa/pegawai
    }

    public function openScanModal()
    {
        $this->reset([
            'user_type',
            'user_id',
            'ms_penempatan_siswa_id',
            'nama',
            'nama_kelas',
            'educard',
            'saldo_edupay',

            'ms_jenjang_id',
            'ms_tahun_ajar_id',

            'nama_jabatan',
        ]);
    }

    public function tambahKeranjang($produkId)
    {
        if (!$this->user_id || !$this->user_type) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal, Scan EduCard']);
            return;
        }

        // cek apakah produk sudah ada di keranjang
        $item = KeranjangSmartCanteen::where('user_type', $this->user_type)
            ->where('user_id', $this->user_id)
            ->where('ms_produk_kantin_id', $produkId)
            ->first();

        if ($item) {
            // jika sudah ada, update jumlah
            $item->increment('jumlah_produk');
        } else {
            // jika belum ada, buat baru
            KeranjangSmartCanteen::create([
                'user_type' => $this->user_type,
                'user_id' => $this->user_id,
                'ms_produk_kantin_id' => $produkId,
                'ms_pengguna_id' => auth()->id(),
                'jumlah_produk' => 1,
            ]);
        }

        // reload keranjang
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah produk']);
        $this->emitSelf('$refresh'); // Memicu render ulang komponen sendiri
    }

    public function incrementQty($ms_keranjang_kantin_id)
    {
        $ms_pengguna_id = auth()->id();

        $item = KeranjangSmartCanteen::where('ms_keranjang_kantin_id', $ms_keranjang_kantin_id)
            ->where('user_id', $this->user_id)
            ->where('ms_pengguna_id', $ms_pengguna_id)
            ->first();

        if ($item) {
            $item->increment('jumlah_produk');
            $this->emitSelf('$refresh');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah produk']);
        }
    }

    public function decrementQty($ms_keranjang_kantin_id)
    {
        $ms_pengguna_id = auth()->id();

        $item = KeranjangSmartCanteen::where('ms_keranjang_kantin_id', $ms_keranjang_kantin_id)
            ->where('user_id', $this->user_id)
            ->where('ms_pengguna_id', $ms_pengguna_id)
            ->first();

        if ($item) {
            if ($item->jumlah_produk > 1) {
                $item->decrement('jumlah_produk');
                $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengurangi produk']);
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Produk dihapus dari keranjang']);
                $item->delete();
            }
            $this->emitSelf('$refresh');
        }
    }

    public function hapusKeranjang($ms_keranjang_kantin_id)
    {
        $ms_pengguna_id = auth()->id();

        $item = KeranjangSmartCanteen::where('ms_keranjang_kantin_id', $ms_keranjang_kantin_id)
            ->where('user_id', $this->user_id)
            ->where('ms_pengguna_id', $ms_pengguna_id)
            ->first();

        if ($item) {
            $item->delete();
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Produk dihapus dari keranjang']);
            $this->emitSelf('$refresh');
        } else {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Produk tidak ditemukan']);
        }
    }

    public function simpanTransaksiKantin()
    {
        try {
            $ms_pengguna_id = Auth::id();

            $keranjang = KeranjangSmartCanteen::with('ms_produk_kantin')
                ->where('user_id', $this->user_id)
                ->where('ms_pengguna_id', $ms_pengguna_id)
                ->get();

            if ($keranjang->isEmpty()) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Keranjang kosong, tidak ada transaksi yang bisa disimpan.']);
                return;
            }

            DB::beginTransaction();

            // Deskripsi untuk jurnal
            $detailProduk = $keranjang->map(function ($item) {
                return $item->ms_produk_kantin->nama_produk_kantin . ' x' . $item->jumlah_produk;
            })->join(', ');

            $deskripsiJurnal = "Pembelian kantin {$this->nama} dengan metode {$this->metode_pembayaran}: $detailProduk.";

            // Kode rekening
            $kode_rekening_kas = 11001;
            $kode_rekening_edupay_siswa = 22002;
            $kode_rekening_pendapatan_kantin = 41002;

            // Pilih debit akun berdasarkan metode pembayaran
            if ($this->metode_pembayaran == 'Tunai') {
                $debitAkunId = $kode_rekening_kas;
            } elseif ($this->metode_pembayaran == 'EduPay') {
                $debitAkunId = $kode_rekening_edupay_siswa;
            } else {
                throw new Exception('Metode pembayaran tidak valid.');
            }

            // Total transaksi
            $totalBayar = $this->totalKeranjang;

            // Jurnal - Debit
            $jurnalDetailDebit = AkuntansiJurnalDetail::create([
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

            // Jurnal - Kredit
            $jurnalDetailKredit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $kode_rekening_pendapatan_kantin,
                'posisi' => 'kredit',
                'nominal' => $totalBayar,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ]);

            // Jika metode pembayaran adalah EduPay
            if ($this->metode_pembayaran == 'EduPay') {
                if (!$this->user_id) {
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Scan kartu siswa']);
                    return;
                }

                $totalBayar = $this->totalKeranjang;
                $saldoEduPay = $this->saldo_edupay;

                if ($saldoEduPay < $totalBayar) {
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo EduPay tidak cukup.']);
                    return;
                }

                $deskripsiEduPay = $keranjang->map(function ($item) {
                    return $item->ms_produk_kantin->nama_produk_kantin . ' x' . $item->jumlah_produk;
                })->join(', ');

                $this->simpanTransaksiEduPay($totalBayar, $deskripsiEduPay, $jurnalDetailDebit->akuntansi_jurnal_detail_id, $jurnalDetailKredit->akuntansi_jurnal_detail_id);
            }

            // Insert transaksi utama
            $transaksi = TransaksiSmartCanteen::create([
                'user_type' => $this->user_type,
                'user_id' => $this->user_id,
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'tanggal_transaksi' => now(),
                'total_transaksi' => $totalBayar,
                'metode_pembayaran' => $this->metode_pembayaran,
                'deskripsi' => $deskripsiJurnal,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDetailDebit->akuntansi_jurnal_detail_id,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalDetailKredit->akuntansi_jurnal_detail_id,
            ]);

            // Insert detail transaksi
            foreach ($keranjang as $item) {
                DetailTransaksiSmartCanteen::create([
                    'ms_transaksi_kantin_id' => $transaksi->ms_transaksi_kantin_id,
                    'ms_produk_kantin_id' => $item->ms_produk_kantin_id,
                    'jumlah_produk' => $item->jumlah_produk,
                    'jumlah_bayar' => ($item->ms_produk_kantin->harga ?? 0) * $item->jumlah_produk,
                    'deskripsi' => "Pembelian {$item->ms_produk_kantin->nama_produk_kantin} x{$item->jumlah_produk}",
                ]);
            }

            // Kosongkan keranjang
            KeranjangSmartCanteen::where('ms_pengguna_id', $ms_pengguna_id)
                ->where('user_type', $this->user_type)
                ->where('user_id', $this->user_id)
                ->delete();

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi kantin berhasil disimpan.']);
            $this->emitSelf('$refresh');
            $this->emit('openScanModal');
            $this->openScanModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    public function simpanTransaksiEduPay($totalBayar, $deskripsiEduPay, $akuntansi_jurnal_detail_debit_id, $akuntansi_jurnal_detail_kredit_id)
    {
        // Simpan transaksi EduPay dengan jenis transaksi 'pembayaran'
        TransaksiEduPay::create([
            'user_type' => $this->user_type,
            'user_id' => $this->user_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_pengguna_id' => Auth::id(),
            'jenis_transaksi' => 'kantin',
            'nominal' => $totalBayar,
            'tanggal' => now(),
            'akuntansi_jurnal_detail_debit_id' => $akuntansi_jurnal_detail_debit_id,
            'akuntansi_jurnal_detail_kredit_id' => $akuntansi_jurnal_detail_kredit_id,
            'deskripsi' => $deskripsiEduPay, // Atur deskripsi sesuai dengan pembayaran
        ]);
    }

    public function render()
    {
        // default collection kosong
        $keranjang = collect();
        $this->totalKeranjang = 0;
        if ($this->user_id) {
            $ms_pengguna_id = auth()->id();
            $keranjang = KeranjangSmartCanteen::with('ms_produk_kantin')
                ->where('user_id', $this->user_id)
                ->where('ms_pengguna_id', $ms_pengguna_id)
                ->get();

            // Hitung total keranjang
            $this->totalKeranjang = $keranjang->sum(function ($item) {
                return ($item->ms_produk_kantin->harga ?? 0) * $item->jumlah_produk;
            });
        }

        return view('livewire.smart-canteen.transaksi-produk.keranjang-produk', [
            'keranjang' => $keranjang,
            'totalKeranjang' => $this->totalKeranjang,
        ]);
    }
}
