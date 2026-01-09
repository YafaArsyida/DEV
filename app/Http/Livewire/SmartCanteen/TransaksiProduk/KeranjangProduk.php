<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\AkuntansiJurnalDetail;
use App\Models\SmartCanteen\DetailTransaksiSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TransaksiEduPay;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class KeranjangProduk extends Component
{
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

    public $keranjang = []; // IN-MEMORY CART

    protected $listeners = [
        'scanSuccess',
        'resetScan',
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

        // reset transaksi lama
        $this->keranjang = [];
    }

    public function resetScan()
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

        // reset transaksi lama
        $this->keranjang = [];
    }

    public function tambahKeranjang($produkId)
    {
        if (!$this->user_id || !$this->user_type) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal, Scan EduCard'
            ]);
            return;
        }

        // Ambil produk (lookup SAJA)
        $produk = ProdukSmartCanteen::find($produkId);

        if (!$produk) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Produk tidak ditemukan'
            ]);
            return;
        }

        // Cek apakah produk sudah ada di keranjang
        foreach ($this->keranjang as $index => $item) {
            if ($item['produk_id'] == $produk->ms_produk_kantin_id) {

                $this->keranjang[$index]['jumlah'] += 1;
                $this->keranjang[$index]['subtotal'] =
                    $this->keranjang[$index]['jumlah'] * $this->keranjang[$index]['harga'];

                $this->dispatchBrowserEvent('alertify-success', [
                    'message' => 'Jumlah produk ditambah'
                ]);
                return;
            }
        }

        // Produk baru
        $this->keranjang[] = [
            'produk_id' => $produk->ms_produk_kantin_id,
            'nama'      => $produk->nama_produk_kantin,
            'harga'     => $produk->harga,
            'jumlah'    => 1,
            'subtotal'  => $produk->harga,
        ];

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Produk ditambahkan'
        ]);
    }

    public function incrementQty($index)
    {
        if (!isset($this->keranjang[$index])) return;

        $this->keranjang[$index]['jumlah']++;
        $this->keranjang[$index]['subtotal'] = $this->keranjang[$index]['jumlah'] * $this->keranjang[$index]['harga'];

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah produk']);
    }

    public function decrementQty($index)
    {
        if (!isset($this->keranjang[$index])) return;

        if ($this->keranjang[$index]['jumlah'] > 1) {
            $this->keranjang[$index]['jumlah']--;
            $this->keranjang[$index]['subtotal'] = $this->keranjang[$index]['jumlah'] * $this->keranjang[$index]['harga'];

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengurangi produk']);
        } else {
            unset($this->keranjang[$index]);
            $this->keranjang = array_values($this->keranjang); // reindex

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Produk dihapus dari keranjang']);
        }
    }

    public function hapusKeranjang($index)
    {
        if (isset($this->keranjang[$index])) {
            unset($this->keranjang[$index]);
            $this->keranjang = array_values($this->keranjang);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Produk dihapus'
            ]);
        }
    }

    public function getTotalKeranjangProperty()
    {
        return collect($this->keranjang)->sum('subtotal');
    }


    public function simpanTransaksiKantin()
    {
        try {
            $ms_pengguna_id = Auth::id();

            // 1️⃣ VALIDASI KERANJANG
            if (empty($this->keranjang)) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Keranjang kosong, tidak ada transaksi yang bisa disimpan.'
                ]);
                return;
            }

            DB::beginTransaction();

            // 2️⃣ DESKRIPSI PRODUK (DARI ARRAY)
            $detailProduk = collect($this->keranjang)->map(function ($item) {
                return $item['nama'] . ' x' . $item['jumlah'];
            })->join(', ');

            $deskripsiJurnal = "Pembelian kantin {$this->nama} dengan metode {$this->metode_pembayaran}: $detailProduk.";

            // 3️⃣ KODE REKENING
            $kode_rekening_kas = 11001;
            $kode_rekening_edupay_siswa = 22002;
            $kode_rekening_edupay_pegawai = 22005;
            $kode_rekening_hutang_kantin = 21001.01;

            if ($this->metode_pembayaran === 'Tunai') {
                $debitAkunId = $kode_rekening_kas;
            } elseif ($this->metode_pembayaran === 'EduPay') {
                $debitAkunId = $this->user_type === 'siswa'
                    ? $kode_rekening_edupay_siswa
                    : $kode_rekening_edupay_pegawai;
            } else {
                throw new \Exception('Metode pembayaran tidak valid.');
            }

            // 4️⃣ TOTAL TRANSAKSI (🔥 COMPUTED PROPERTY)
            $totalBayar = $this->totalKeranjang;

            // 5️⃣ JURNAL DEBIT
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

            // 6️⃣ JURNAL KREDIT
            $jurnalDetailKredit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $kode_rekening_hutang_kantin,
                'posisi' => 'kredit',
                'nominal' => $totalBayar,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ]);

            // 7️⃣ EDU PAY (LOCK SALDO)
            if ($this->metode_pembayaran === 'EduPay') {
                if (!$this->user_id) {
                    throw new \Exception('Scan kartu siswa terlebih dahulu.');
                }

                if ($this->saldo_edupay < $totalBayar) {
                    throw new \Exception('Saldo EduPay tidak cukup.');
                }

                $this->simpanTransaksiEduPay(
                    $totalBayar,
                    $deskripsiJurnal,
                    $jurnalDetailDebit->akuntansi_jurnal_detail_id,
                    $jurnalDetailKredit->akuntansi_jurnal_detail_id
                );
            }

            // 8️⃣ TRANSAKSI UTAMA
            $transaksi = TransaksiSmartCanteen::create([
                'user_type' => $this->user_type,
                'user_id' => $this->user_id,
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'tanggal_transaksi' => now(),
                'total_transaksi' => $totalBayar,
                'metode_pembayaran' => $this->metode_pembayaran,
                'deskripsi' => $deskripsiJurnal,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDetailDebit->akuntansi_jurnal_detail_id,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalDetailKredit->akuntansi_jurnal_detail_id,
                'is_settled' => 'belum',
            ]);

            // 9️⃣ DETAIL TRANSAKSI
            foreach ($this->keranjang as $item) {
                DetailTransaksiSmartCanteen::create([
                    'ms_transaksi_kantin_id' => $transaksi->ms_transaksi_kantin_id,
                    'ms_produk_kantin_id' => $item['produk_id'],
                    'jumlah_produk' => $item['jumlah'],
                    'jumlah_bayar' => $item['subtotal'],
                    'deskripsi' => "Pembelian {$item['nama']} x{$item['jumlah']}",
                ]);
            }

            // 🔟 RESET STATE
            $this->keranjang = [];

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi kantin berhasil disimpan.'
            ]);

            $this->emit('resetScan');
            $this->resetScan();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
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
        return view('livewire.smart-canteen.transaksi-produk.keranjang-produk');
    }
}
