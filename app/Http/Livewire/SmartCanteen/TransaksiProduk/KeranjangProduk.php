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
    public $user_type = 'umum';
    public $user_id;
    public $ms_penempatan_siswa_id;
    public $nama;
    public $nama_kelas;
    public $educard;
    public $saldo_edupay;

    public $nama_jabatan;

    public $metode_pembayaran = 'Tunai';

    public $ms_kantin_id;
    public $ms_tahun_ajar_id;
    public $ms_jenjang_id = null;

    public $keranjang = []; // IN-MEMORY CART

    protected $listeners = [
        'parameterUpdated',

        'scanSuccess',
        'resetKeranjang',
        'tambahKeranjang',
    ];

    public function parameterUpdated($kantin, $tahunAjar)
    {
        // Update nilai selectedKantin dan selectedTahunAjar
        $this->ms_kantin_id = $kantin;
        $this->ms_tahun_ajar_id = $tahunAjar;

        $this->user_type = 'umum';
    }

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

        $this->metode_pembayaran      = 'EduPay';

        // reset transaksi lama
        $this->keranjang = [];
    }

    public function resetKeranjang()
    {
        $this->reset([
            'user_id',
            'ms_penempatan_siswa_id',
            'nama',
            'nama_kelas',
            'educard',
            'saldo_edupay',

            // 'ms_kantin_id',
            'nama_jabatan',
        ]);

        $this->user_type             = 'umum';
        $this->metode_pembayaran     = 'Tunai';

        // reset transaksi lama
        $this->keranjang = [];
    }

    public function tambahKeranjang($produkId)
    {
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
                    "Pembelian kantin "
                    . ($this->nama ?: 'Umum')
                    . " metode {$this->metode_pembayaran}: "
                    . $detailProduk;

                // default

                $saldo = null;

                $akunDebit = null;
                $akunKredit = null;

                $statusSettlement = null;

                // mapping akun
                switch ($this->metode_pembayaran) {
                    case 'Tunai':

                        // Debit Kas Kantin
                        $akunDebit = 11003;

                        // Kredit Pendapatan Kantin
                        $akunKredit = 41002;

                        $statusSettlement = null;

                        break;

                    case 'QRIS':

                        // Debit Bank Kantin
                        $akunDebit = 11004;

                        // Kredit Pendapatan Kantin
                        $akunKredit = 41002;

                        $statusSettlement = null;

                        break;

                    case 'Transfer':

                        // Debit Bank Kantin
                        $akunDebit = 11004;

                        // Kredit Pendapatan Kantin
                        $akunKredit = 41002;

                        $statusSettlement = null;

                        break;

                    case 'EduPay':

                        if (!$this->user_id) {
                            throw new \Exception(
                                'Scan kartu EduPay terlebih dahulu.'
                            );
                        }

                        $saldo = SaldoEduPay::where('user_id', $this->user_id)
                            ->where('user_type', $this->user_type)
                            ->lockForUpdate()
                            ->first();

                        if (!$saldo) {
                            throw new \Exception(
                                'Saldo EduPay tidak ditemukan.'
                            );
                        }

                        if ($saldo->saldo_edupay < $totalBayar) {
                            throw new \Exception(
                                'Saldo EduPay tidak cukup.'
                            );
                        }

                        // Debit saldo edupay
                        $akunDebit = $this->user_type === 'siswa' ? 22002 : 22005;

                        // Kredit hutang kantin
                        $akunKredit = 21001.01;

                        $statusSettlement = 'belum';

                        break;

                    default:
                        throw new \Exception(
                            'Metode pembayaran tidak valid.'
                        );
                }

                // jurnal debit

                $jurnalDebit = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $akunDebit,
                    'posisi' => 'debit',
                    'nominal' => $totalBayar,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'ms_departemen_id' => 'KANTIN',
                    'deskripsi' => $deskripsiJurnal,
                ]);

                // jurnal kredit

                $jurnalKredit = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $akunKredit,
                    'posisi' => 'kredit',
                    'nominal' => $totalBayar,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'ms_departemen_id' => 'KANTIN',
                    'deskripsi' => $deskripsiJurnal,
                ]);

                // hanya jika metode edupay

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

                    // 🔥 anti race condition
                    $saldo->decrement('saldo_edupay', $totalBayar);
                }

                // transaksi kantin

                $transaksi = TransaksiSmartCanteen::create([
                    'user_type' => $this->user_type,
                    'user_id' => $this->user_id,
                    'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'ms_kantin_id' => $this->ms_kantin_id,
                    'tanggal_transaksi' => now(),
                    'total_transaksi' => $totalBayar,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'deskripsi' => $deskripsiJurnal,
                    'akuntansi_jurnal_detail_debit_id' => $jurnalDebit->akuntansi_jurnal_detail_id,
                    'akuntansi_jurnal_detail_kredit_id' => $jurnalKredit->akuntansi_jurnal_detail_id,
                    'status_settlement' => $statusSettlement,
                ]);

                // DT Transaksi kantin

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

            $this->resetKeranjang();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi berhasil disimpan.'
            ]);

            $this->emit('resetScan');
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
