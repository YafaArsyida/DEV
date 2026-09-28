<?php

namespace App\Http\Livewire\Keuangan\TransaksiEduPayPegawai;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Pegawai;
use App\Models\SaldoEduPay;
use App\Models\TransaksiEduPay;
use App\Services\AccountingService;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DataPegawai extends Component
{
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $ms_pegawai_id = null;
    public $nama_pegawai = null;
    public $educard_pegawai = null;
    public $jabatan = null;
    public $telepon_pegawai = null;
    public $alamat_pegawai = null;

    // saldo edupay pegawai
    public $saldoEduPayPegawai;
    public $total_pemasukan_pegawai;
    public $total_pengeluaran_pegawai;

    // save topup
    public $jenis_transaksi_topup = 'topup tunai';
    public $nominal_topup = 0;
    public $deskripsi_topup;

    // save penarikan
    public $nominal_penarikan = 0;
    public $deskripsi_penarikan;

    protected $listeners = [
        'pegawaiSelected',
        'parameterUpdated' => 'updateParameters',

        'successTransaksiEduPay'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
    }

    public function fillData($pegawai): void
    {
        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->nama_pegawai = $pegawai->nama_pegawai;
        $this->educard_pegawai = $pegawai->ms_educard->kode_kartu ?? null;
        $this->jabatan = $pegawai->ms_jabatan->nama_jabatan ?? null;
        $this->telepon_pegawai = $pegawai->telepon;
        $this->alamat_pegawai = $pegawai->alamat;
    }

    public function successTransaksiEduPay()
    {
        if ($this->ms_pegawai_id) {
            $this->updateSaldoEdupay();
        }
    }

    protected function updateSaldoEdupay()
    {
        $saldo = SaldoEduPay::getSaldo($this->ms_pegawai_id, 'pegawai');

        $this->saldoEduPayPegawai = $saldo->saldo_edupay;
    }

    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard')
            ->findOrFail($ms_pegawai_id);

        if (!$pegawai) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pegawai tidak ditemukan.']);
            return;
        }

        $this->fillData($pegawai);

        $this->updateSaldoEdupay();
    }

    private function processTopUp($saldo)
    {
        $ms_pengguna_id = auth()->user()->ms_pengguna_id;

        $kode_rekening_kas = 11001;
        $kode_rekening_bank = 11002;
        $kode_rekening_edupay = 22002;

        $debitAkunId = match ($this->jenis_transaksi_topup) {
            'topup tunai' => $kode_rekening_kas,
            'topup online' => $kode_rekening_bank,
            default => throw new \Exception('Metode pembayaran tidak valid.'),
        };

        $deskripsiJurnal = sprintf(
            '%s EduPay Rp %s pegawai %s',
            $this->jenis_transaksi_topup,
            number_format($this->nominal_topup, 0, ',', '.'),
            $this->nama_pegawai
        );

        // =========================================================
        // BUAT JURNAL
        // =========================================================
        $jurnal = AccountingService::create([
            'tanggal' => now(),
            'deskripsi' => $deskripsiJurnal,
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_departemen_id' => 'SEKOLAH',
            'detail' => [
                // Debit Kas / Bank
                [
                    'kode_rekening' => $debitAkunId,
                    'posisi' => 'debit',
                    'nominal' => $this->nominal_topup,
                ],

                // Kredit Saldo EduPay
                [
                    'kode_rekening' => $kode_rekening_edupay,
                    'posisi' => 'kredit',
                    'nominal' => $this->nominal_topup,
                ],

            ],
        ]);

        // =========================================================
        // SIMPAN TRANSAKSI EDUPAY PEGAWAI
        // =========================================================
        TransaksiEduPay::create([
            'user_type' => 'pegawai',
            'user_id' => $this->ms_pegawai_id,
            'ms_pengguna_id' => $ms_pengguna_id,
            'jenis_transaksi' => $this->jenis_transaksi_topup,
            'nominal' => $this->nominal_topup,

            'tanggal' => now(),

            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
            'deskripsi' => $this->deskripsi_topup,
        ]);

        // =========================================================
        // UPDATE SALDO EDUPAY PEGAWAI
        // =========================================================
        $saldo->increment(
            'saldo_edupay',
            $this->nominal_topup
        );
    }

    protected function processPenarikan($saldo)
    {
        $ms_pengguna_id = auth()->user()->ms_pengguna_id;

        $kode_rekening_kas = 11001;
        $kode_rekening_edupay = 22002;

        $deskripsiJurnal = sprintf(
            'Penarikan Tunai EduPay Rp %s pegawai %s',
            number_format($this->nominal_penarikan, 0, ',', '.'),
            $this->nama_pegawai
        );

        // =========================================================
        // BUAT JURNAL
        // =========================================================
        $jurnal = AccountingService::create([
            'tanggal' => now(),
            'deskripsi' => $deskripsiJurnal,
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_departemen_id' => 'SEKOLAH',
            'detail' => [

                // Debit EduPay
                [
                    'kode_rekening' => $kode_rekening_edupay,
                    'posisi' => 'debit',
                    'nominal' => $this->nominal_penarikan,
                ],

                // Kredit Kas
                [
                    'kode_rekening' => $kode_rekening_kas,
                    'posisi' => 'kredit',
                    'nominal' => $this->nominal_penarikan,
                ],

            ],
        ]);

        // =========================================================
        // SIMPAN TRANSAKSI EDUPAY PEGAWAI
        // =========================================================
        TransaksiEduPay::create([
            'user_type' => 'pegawai',

            'user_id' => $this->ms_pegawai_id,

            'ms_pengguna_id' => $ms_pengguna_id,

            'jenis_transaksi' => 'penarikan',

            'nominal' => $this->nominal_penarikan,

            'tanggal' => now(),

            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,

            'deskripsi' => $this->deskripsi_penarikan,
        ]);

        // =========================================================
        // KURANGI SALDO EDUPAY
        // =========================================================
        $saldo->decrement(
            'saldo_edupay',
            $this->nominal_penarikan
        );
    }
    protected function afterSuccess()
    {
        $this->reset([
            'nominal_topup',
            'deskripsi_topup',
            'nominal_penarikan',
            'deskripsi_penarikan'
        ]);

        // update saldo & rekap
        $this->updateSaldoEdupay();

        // refresh table / komponen lain
        $this->emit('successTransaksiEduPay');

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi berhasil disimpan.'
        ]);
    }

    public function simpanTopUp()
    {
        DB::beginTransaction();

        try {
            $this->nominal_topup = $this->normalizeAmount($this->nominal_topup);

            $this->validate([
                'nominal_topup' => 'required|numeric|min:1000',
                'deskripsi_topup' => 'nullable|string|max:255',
            ], [
                'nominal_topup.required' => 'Nominal top-up harus diisi.',
                'nominal_topup.numeric' => 'Nominal top-up harus berupa angka.',
                'nominal_topup.min' => 'Nominal top-up minimal 1000.',
                'deskripsi_topup.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            if (!$this->ms_pegawai_id) {
                throw new \Exception('Pegawai tidak ditemukan!');
            }

            // 🔥 Ambil saldo + lock (penting untuk uang)
            $saldo = SaldoEduPay::where('user_id', $this->ms_pegawai_id)
                ->where('user_type', 'pegawai')
                ->lockForUpdate()
                ->first();

            if (!$saldo) {
                throw new \Exception('Data saldo tidak ditemukan!');
            }

            // 🔥 Proses utama
            $this->processTopUp($saldo);

            DB::commit();

            $this->afterSuccess();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function simpanPenarikan()
    {
        DB::beginTransaction();

        try {
            $this->nominal_penarikan = $this->normalizeAmount($this->nominal_penarikan);
            $this->validate([
                'nominal_penarikan' => 'required|numeric|min:1000',
                'deskripsi_penarikan' => 'nullable|string|max:255',
            ], [
                'nominal_penarikan.required' => 'Nominal penarikan harus diisi.',
                'nominal_penarikan.numeric' => 'Nominal penarikan harus berupa angka.',
                'nominal_penarikan.min' => 'Nominal penarikan minimal 1000.',
                'deskripsi_penarikan.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            if (!$this->ms_pegawai_id) {
                throw new \Exception('Pegawai tidak ditemukan!');
            }

            // 🔥 Ambil saldo + lock (penting untuk uang)
            $saldo = SaldoEduPay::where('user_id', $this->ms_pegawai_id)
                ->where('user_type', 'pegawai')
                ->lockForUpdate()
                ->first();

            if (!$saldo) {
                throw new \Exception('Data saldo tidak ditemukan!');
            }

            if ($this->nominal_penarikan > $saldo->saldo_edupay) {
                throw new \Exception('Saldo tidak cukup');
            }

            $this->processPenarikan($saldo);

            DB::commit();

            $this->afterSuccess();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.keuangan.transaksi-edu-pay-pegawai.data-pegawai');
    }
    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
