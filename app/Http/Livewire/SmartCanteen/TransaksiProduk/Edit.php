<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\SaldoEduPay;
use App\Models\SmartCanteen\DetailTransaksiSmartCanteen;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TransaksiEduPay;
use App\Services\AccountingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $transaksi;

    public $nama_user;

    public $detailKoreksi = [];

    protected $listeners = [
        'loadKoreksiTransaksi',
    ];

    public function loadKoreksiTransaksi($id)
    {
        $transaksi = TransaksiSmartCanteen::with([
            'ms_siswa',
            'ms_pegawai',
            'dt_transaksi_kantin.ms_produk_kantin',
        ])->find($id);

        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Transaksi tidak ditemukan!'
            ]);

            return;
        }

        // Hanya transaksi hari ini
        if (!Carbon::parse($transaksi->tanggal_transaksi)->isToday()) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Transaksi hanya dapat dikoreksi pada hari transaksi.'
            ]);

            return;
        }

        // Hanya yang belum settlement
        if (!in_array($transaksi->status_settlement, ['belum', null], true)) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Transaksi sudah settlement dan tidak dapat dikoreksi.'
            ]);

            return;
        }

        $this->resetErrorBag();
        $this->resetValidation();

        $this->transaksi = $transaksi;

        if ($transaksi->user_type === 'siswa') {

            $this->nama_user = $transaksi->ms_siswa->nama_siswa ?? 'Siswa';

        } elseif ($transaksi->user_type === 'pegawai') {

            $this->nama_user = $transaksi->ms_pegawai->nama_pegawai ?? 'Pegawai';

        } else {

            $this->nama_user = 'Umum';
        }

        $this->detailKoreksi = $transaksi->dt_transaksi_kantin
            ->map(function ($detail) {
                $harga = $detail->ms_produk_kantin->harga ?? 0;
                $jumlah = $detail->jumlah_produk;

                return [
                    'id' => $detail->dt_transaksi_kantin_id,
                    'produk_id' => $detail->ms_produk_kantin_id,
                    'produk' => $detail->ms_produk_kantin->nama_produk_kantin ?? 'Produk',
                    'harga' => $harga,
                    'jumlah_awal' => $jumlah,
                    'jumlah' => $jumlah,
                    'subtotal' => $harga * $jumlah,
                ];
            })
            ->toArray();

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi dimuat untuk dikoreksi.'
        ]);
    }

    public function getTotalAwalProperty()
    {
        if (!$this->transaksi) {
            return 0;
        }

        return $this->transaksi->total_transaksi;
    }

    public function getTotalKoreksiProperty()
    {
        return collect($this->detailKoreksi)->sum(function ($detail) {
            return ($detail['harga'] ?? 0) * ($detail['jumlah'] ?? 0);
        });
    }
    
    public function getSelisihProperty()
    {
        return $this->totalKoreksi - $this->totalAwal;
    }

    protected $rules = [
        'detailKoreksi.*.jumlah' => 'required|integer|min:0',
    ];

    protected $messages = [
        'detailKoreksi.*.jumlah.required' => 'Jumlah produk wajib diisi.',
        'detailKoreksi.*.jumlah.integer' => 'Jumlah harus berupa angka.',
        'detailKoreksi.*.jumlah.min' => 'Jumlah tidak boleh kurang dari 0.',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }
    
    public function incrementQty($index)
    {
        if (!isset($this->detailKoreksi[$index])) {
            return;
        }

        $this->detailKoreksi[$index]['jumlah']++;

        $this->detailKoreksi[$index]['subtotal'] =
            $this->detailKoreksi[$index]['jumlah'] *
            $this->detailKoreksi[$index]['harga'];

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Berhasil menambah produk'
        ]);
    }

    public function decrementQty($index)
    {
        if (!isset($this->detailKoreksi[$index])) {
            return;
        }

        if ($this->detailKoreksi[$index]['jumlah'] > 1) {

            $this->detailKoreksi[$index]['jumlah']--;

            $this->detailKoreksi[$index]['subtotal'] =
                $this->detailKoreksi[$index]['jumlah'] *
                $this->detailKoreksi[$index]['harga'];

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil mengurangi produk'
            ]);

        } else {

            unset($this->detailKoreksi[$index]);

            // Reindex array setelah produk dihapus
            $this->detailKoreksi = array_values($this->detailKoreksi);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Produk dihapus dari transaksi'
            ]);
        }
    }

    public function simpanKoreksi()
    {
        try {

            if (!$this->transaksi) {
                throw new \Exception('Transaksi tidak ditemukan.');
            }

            $isPembatalan = empty($this->detailKoreksi);

            if ($isPembatalan) {
                $this->batalkanTransaksi();
            } else {
                $this->prosesKoreksi();
            }

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => $isPembatalan
                    ? 'Transaksi berhasil dibatalkan.'
                    : 'Koreksi transaksi berhasil disimpan.'
            ]);
            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'modalKoreksi'
            ]);

            $this->emit('koreksiBerhasil');

        } catch (\Throwable $e) {

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage()
            ]);
        }
    }

    public function prosesKoreksi()
    {
        DB::transaction(function () {

            // * LOCK TRANSAKSI
            $transaksi = TransaksiSmartCanteen::lockForUpdate()
                ->findOrFail(
                    $this->transaksi->ms_transaksi_kantin_id
                );

            // * VALIDASI
            if (!Carbon::parse($transaksi->tanggal_transaksi)->isToday()) {
                throw new \Exception(
                    'Transaksi hanya dapat dikoreksi pada hari transaksi.'
                );
            }

            if (!in_array(
                $transaksi->status_settlement, ['belum', null], true)) {
                throw new \Exception(
                    'Transaksi sudah settlement dan tidak dapat dikoreksi.'
                );
            }

            // * VALIDASI DETAIL
            $this->validate();

            if (empty($this->detailKoreksi)) {
                throw new \Exception(
                    'Detail kosong. Gunakan proses pembatalan.'
                );
            }

            // * HITUNG TOTAL BARU
            $totalBaru = collect($this->detailKoreksi)
                ->sum(function ($item) {
                    return ($item['harga'] ?? 0)  * ($item['jumlah'] ?? 0);
                });

            if ($totalBaru <= 0) {
                throw new \Exception(
                    'Total transaksi harus lebih dari Rp 0.'
                );
            }

            // * DESKRIPSI
            $detailProduk = collect($this->detailKoreksi)
                ->map(function ($item) {
                    return "{$item['produk']} x{$item['jumlah']}";
                })
                ->join(', ');

            $deskripsiJurnal =
                'Koreksi pembelian kantin ' . ($this->nama_user ? : 'Umum') .
                " metode {$transaksi->metode_pembayaran}: " .
                $detailProduk;

            // * MAPPING AKUN
            switch ($transaksi->metode_pembayaran) {

                case 'Tunai':
                    $akunDebit = 11003;
                    $akunKredit = 41002;

                    break;

                case 'QRIS':
                case 'Transfer':
                    $akunDebit = 11004;
                    $akunKredit = 41002;

                    break;

                case 'EduPay':

                    $akunDebit = $transaksi->user_type === 'siswa'
                        ? 22002
                        : 22005;

                    $akunKredit = 21001.01;

                    break;

                default:

                    throw new \Exception(
                        'Metode pembayaran transaksi tidak valid.'
                    );
            }

            // * UPDATE JURNAL
            AccountingService::update(
                $transaksi->akuntansi_jurnal_id,
                [
                    'tanggal' => $transaksi->tanggal_transaksi,
                    'deskripsi' => $deskripsiJurnal,
                    'detail' => [
                        [
                            'kode_rekening' => $akunDebit,
                            'posisi' => 'debit',
                            'nominal' => $totalBaru,
                        ],
                        [
                            'kode_rekening' => $akunKredit,
                            'posisi' => 'kredit',
                            'nominal' => $totalBaru,
                        ],
                    ],
                ]
            );

            // * KOREKSI SALDO EDUPAY
            $totalLama = $transaksi->total_transaksi;
            $selisih = $totalBaru - $totalLama;

            if ($transaksi->metode_pembayaran === 'EduPay') {
                $saldo = SaldoEduPay::where(
                    'user_id', $transaksi->user_id
                )
                    ->where('user_type', $transaksi->user_type)
                    ->lockForUpdate()
                    ->first();

                if (!$saldo) {
                    throw new \Exception(
                        'Saldo EduPay tidak ditemukan.'
                    );
                }

                /* Transaksi bertambah */
                if ($selisih > 0) {
                    
                    if ($saldo->saldo_edupay < $selisih) {
                        throw new \Exception(
                            'Saldo EduPay tidak cukup untuk koreksi transaksi.'
                        );
                    }

                    $saldo->decrement(
                        'saldo_edupay', $selisih
                    );
                }

                /* Transaksi berkurang */
                elseif ($selisih < 0) {

                    $saldo->increment(
                        'saldo_edupay', abs($selisih)
                    );
                }

                // Update transaksi EduPay
                $transaksiEduPay = TransaksiEduPay::where('akuntansi_jurnal_id', $transaksi->akuntansi_jurnal_id)
                    ->lockForUpdate()
                    ->first();

                if (!$transaksiEduPay) {
                    throw new \Exception(
                        'Data transaksi EduPay tidak ditemukan.'
                    );
                }

                $transaksiEduPay->update([
                    'nominal' => $totalBaru,
                    'deskripsi' => $deskripsiJurnal,
                ]);
            }

            // * UPDATE TRANSAKSI KANTIN
            $transaksi->update([
                'total_transaksi' => $totalBaru,
                'deskripsi' => $deskripsiJurnal,
            ]);

            // * HAPUS DETAIL LAMA
            $transaksi->dt_transaksi_kantin()->delete();

            // * INSERT DETAIL BARU
            $detailInsert = [];

            foreach ($this->detailKoreksi as $item) {

                $jumlah = $item['jumlah'];
                $harga = $item['harga'];

                $detailInsert[] = [
                    'ms_transaksi_kantin_id'=> $transaksi->ms_transaksi_kantin_id,
                    'ms_produk_kantin_id'   => $item['produk_id'],
                    'jumlah_produk'         => $jumlah,
                    'jumlah_bayar'          => $harga * $jumlah,
                    'deskripsi'             => "Koreksi {$item['produk']} x{$jumlah}",
                ];
            }

            DetailTransaksiSmartCanteen::insert(
                $detailInsert
            );
        });
    }

    public function batalkanTransaksi()
    {
        DB::transaction(function () {

            // * LOCK TRANSAKSI
            $transaksi = TransaksiSmartCanteen::lockForUpdate()
                ->findOrFail(
                    $this->transaksi->ms_transaksi_kantin_id
                );

            // * VALIDASI
            if (!Carbon::parse($transaksi->tanggal_transaksi)->isToday()) {
                throw new \Exception(
                    'Transaksi hanya dapat dibatalkan pada hari transaksi.'
                );
            }

            if ($transaksi->status_transaksi === 'dibatalkan') {
                throw new \Exception(
                    'Transaksi sudah dibatalkan dan tidak dapat dikoreksi.'
                );
            }

            if (!in_array($transaksi->status_settlement, ['belum', null], true)) {
                throw new \Exception(
                    'Transaksi sudah settlement dan tidak dapat dibatalkan.'
                );
            }

            // * TOTAL TRANSAKSI
            $totalTransaksi = $transaksi->total_transaksi;

            if ($totalTransaksi <= 0) {
                throw new \Exception(
                    'Nominal transaksi tidak valid.'
                );
            }

            // * DESKRIPSI
            $deskripsiJurnal =
                'Pembatalan transaksi, '.
                ($transaksi->deskripsi ?? '');

            // * MAPPING AKUN PEMBALIK
            switch ($transaksi->metode_pembayaran) {

                case 'Tunai':
                    // Awal:
                    // Debit  11003
                    // Kredit 41002

                    // Pembalik:
                    // Debit  41002
                    // Kredit 11003

                    $akunDebit = 41002;
                    $akunKredit = 11003;

                    break;

                case 'QRIS':
                case 'Transfer':
                    // Awal:
                    // Debit  11004
                    // Kredit 41002

                    // Pembalik:
                    // Debit  41002
                    // Kredit 11004

                    $akunDebit = 41002;
                    $akunKredit = 11004;

                    break;

                case 'EduPay':
                    // Awal:
                    // Debit siswa/pegawai 22002 / 22005
                    // Kredit 21001.01

                    // Pembalik:
                    // Debit  21001.01
                    // Kredit siswa/pegawai 22002 / 22005

                    $akunDebit = 21001.01;

                    $akunKredit = $transaksi->user_type === 'siswa'
                        ? 22002
                        : 22005;

                    break;

                default:
                    throw new \Exception(
                        'Metode pembayaran transaksi tidak valid.'
                    );
            }

            // * BUAT JURNAL PEMBALIK
            $jurnalPembatalan = AccountingService::create([
                'tanggal' => now(),

                'deskripsi' => $deskripsiJurnal,

                'ms_pengguna_id' => Auth::user()->ms_pengguna_id,

                'ms_tahun_ajaran_id' => null,

                'ms_jenjang_id' => $transaksi->ms_jenjang_id,

                'ms_departemen_id' => 'KANTIN',

                'detail' => [
                    [
                        'kode_rekening' => $akunDebit,
                        'posisi' => 'debit',
                        'nominal' => $totalTransaksi,
                    ],
                    [
                        'kode_rekening' => $akunKredit,
                        'posisi' => 'kredit',
                        'nominal' => $totalTransaksi,
                    ],
                ],
            ]);

            // * KEMBALIKAN SALDO EDUPAY
            if ($transaksi->metode_pembayaran === 'EduPay') {

                $saldo = SaldoEduPay::where('user_id', $transaksi->user_id)
                    ->where('user_type', $transaksi->user_type)
                    ->lockForUpdate()
                    ->first();

                if (!$saldo) {
                    throw new \Exception(
                        'Saldo EduPay tidak ditemukan.'
                    );
                }

                $saldo->increment(
                    'saldo_edupay', $totalTransaksi
                );
            }

            // * UPDATE TRANSAKSI EDU PAY
            // * Tidak perlu menghapus TransaksiEduPay.
            // * Histori transaksi tetap ada.
            if ($transaksi->metode_pembayaran === 'EduPay') {
                TransaksiEduPay::where(
                    'akuntansi_jurnal_id', $transaksi->akuntansi_jurnal_id
                )
                    ->lockForUpdate()
                    ->update([
                        'status_transaksi' => 'dibatalkan',
                        'akuntansi_jurnal_reversal_id' => $jurnalPembatalan->akuntansi_jurnal_id,
                        // 'deskripsi' => 'Dibatalkan - ' .($transaksi->deskripsi ?? ''),
                    ]);
            }

            // * UPDATE STATUS TRANSAKSI
            $transaksi->update([
                'status_transaksi' => 'dibatalkan',
                'akuntansi_jurnal_reversal_id' => $jurnalPembatalan->akuntansi_jurnal_id,
            ]);
            // Update seluruh detail transaksi
            $transaksi->dt_transaksi_kantin()->update([
                'status_transaksi' => 'dibatalkan',
            ]);

        });
    }

    public function render()
    {
        return view('livewire.smart-canteen.transaksi-produk.edit');
    }
}
