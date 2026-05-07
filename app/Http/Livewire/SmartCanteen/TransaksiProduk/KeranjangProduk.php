<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\AkuntansiJurnalDetail;
use App\Models\SaldoEduPay;
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

    public $ms_kantin_id;
    public $ms_tahun_ajar_id;
    public $ms_jenjang_id;

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
        $this->ms_jenjang_id            = $data['ms_jenjang_id'];     // ms_siswa_id atau ms_pegawai_id
        
        $this->nama                     = $data['nama'];        // nama siswa/pegawai
        $this->nama_kelas               = $data['nama_kelas'];        // nama siswa/pegawai
        $this->nama_jabatan               = $data['nama_jabatan'];        // nama siswa/pegawai
        
        $this->educard                  = $data['educard'];
        $this->saldo_edupay             = $data['saldo_edupay'];

        $this->ms_kantin_id            = $data['ms_kantin_id'];
        $this->ms_tahun_ajar_id         = $data['ms_tahun_ajar_id'];

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

            // 'ms_kantin_id',
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

            if (empty($this->keranjang)) {
                throw new \Exception('Keranjang kosong.');
            }

            DB::transaction(function () {

                $ms_pengguna_id = Auth::id();

                $totalBayar = $this->totalKeranjang;

                $detailProduk = collect($this->keranjang)
                    ->map(fn($item) => "{$item['nama']} x{$item['jumlah']}")
                    ->join(', ');

                $deskripsiJurnal =
                    "Pembelian kantin {$this->nama} "
                    . "dengan metode {$this->metode_pembayaran}: "
                    . $detailProduk;

                /*
                |--------------------------------------------------------------------------
                | LOCK SALDO EDU PAY
                |--------------------------------------------------------------------------
                */

                $saldo = null;

                if ($this->metode_pembayaran === 'EduPay') {

                    if (!$this->user_id) {
                        throw new \Exception('Scan kartu terlebih dahulu.');
                    }

                    $saldo = SaldoEduPay::where('user_id', $this->user_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$saldo) {
                        throw new \Exception('Saldo EduPay tidak ditemukan.');
                    }

                    if ($saldo->saldo_edupay < $totalBayar) {
                        throw new \Exception('Saldo EduPay tidak cukup.');
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | KODE REKENING
                |--------------------------------------------------------------------------
                */

                $kode_rekening_kas = 11001;

                $kode_rekening_edupay = $this->user_type === 'siswa'
                    ? 22002
                    : 22005;

                $kode_rekening_hutang_kantin = 21001.01;

                $akunDebit = $this->metode_pembayaran === 'Tunai'
                    ? $kode_rekening_kas
                    : $kode_rekening_edupay;

                /*
                |--------------------------------------------------------------------------
                | JURNAL
                |--------------------------------------------------------------------------
                */

                $jurnalDebit = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $akunDebit,
                    'posisi' => 'debit',
                    'nominal' => $totalBayar,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'deskripsi' => $deskripsiJurnal,
                ]);

                $jurnalKredit = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $kode_rekening_hutang_kantin,
                    'posisi' => 'kredit',
                    'nominal' => $totalBayar,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'deskripsi' => $deskripsiJurnal,
                ]);

                /*
                |--------------------------------------------------------------------------
                | TRANSAKSI EDUPAY
                |--------------------------------------------------------------------------
                */

                if ($this->metode_pembayaran === 'EduPay') {

                    TransaksiEduPay::create([
                        'user_type' => $this->user_type,
                        'user_id' => $this->user_id,
                        'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                        'ms_pengguna_id' => $ms_pengguna_id,
                        'jenis_transaksi' => 'kantin',
                        'nominal' => $totalBayar,
                        'tanggal' => now(),
                        'akuntansi_jurnal_detail_debit_id' => $jurnalDebit->akuntansi_jurnal_detail_id,

                        'akuntansi_jurnal_detail_kredit_id' => $jurnalKredit->akuntansi_jurnal_detail_id,

                        'deskripsi' => $deskripsiJurnal,
                    ]);

                    // 🔥 pakai object lock yg sama
                    $saldo->decrement('saldo_edupay', $totalBayar);
                }

                /*
                |--------------------------------------------------------------------------
                | TRANSAKSI KANTIN
                |--------------------------------------------------------------------------
                */

                $transaksi = TransaksiSmartCanteen::create([
                    'user_type'                   => $this->user_type,
                    'user_id'                     => $this->user_id,
                    'ms_penempatan_siswa_id'      => $this->ms_penempatan_siswa_id,
                    'ms_pengguna_id'              => $ms_pengguna_id,
                    'ms_kantin_id'                => $this->ms_kantin_id,
                    'tanggal_transaksi'           => now(),
                    'total_transaksi'             => $totalBayar,
                    'metode_pembayaran'           => $this->metode_pembayaran,
                    'deskripsi'                   => $deskripsiJurnal,
                    'akuntansi_jurnal_detail_debit_id'  => $jurnalDebit->akuntansi_jurnal_detail_id,

                    'akuntansi_jurnal_detail_kredit_id' => $jurnalKredit->akuntansi_jurnal_detail_id,

                    'status_settlement'           => 'belum',
                ]);

                /*
                |--------------------------------------------------------------------------
                | DETAIL TRANSAKSI
                |--------------------------------------------------------------------------
                */

                $detailInsert = [];

                foreach ($this->keranjang as $item) {

                    $detailInsert[] = [
                        'ms_transaksi_kantin_id' => $transaksi->ms_transaksi_kantin_id,

                        'ms_produk_kantin_id' => $item['produk_id'],
                        'jumlah_produk' => $item['jumlah'],
                        'jumlah_bayar' => $item['subtotal'],
                        'deskripsi' => "Pembelian {$item['nama']} x{$item['jumlah']}",
                    ];
                }

                DetailTransaksiSmartCanteen::insert($detailInsert);
            });

            $this->keranjang = [];

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi berhasil disimpan.'
            ]);

            $this->emit('resetScan');

            $this->resetScan();
        } catch (\Exception $e) {

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.smart-canteen.transaksi-produk.keranjang-produk');
    }
}
