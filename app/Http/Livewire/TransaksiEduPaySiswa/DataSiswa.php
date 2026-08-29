<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\PenempatanSiswa;
use App\Models\SaldoEduPay;
use App\Models\TransaksiEduPay;
use App\Services\AccountingService;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DataSiswa extends Component
{
    // IDENTITAS SISWA
    public $ms_siswa_id = null;
    public $ms_penempatan_siswa_id = null;
    public $nama_siswa = null;
    public $nama_kelas = null;
    public $educard = null;
    public $nisn = null;
    public $tanggal_lahir = null;
    public $alamat = null;
    public $telepon = null;

    // FILTER / RELASI
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    // SALDO & REKAP EDUPAY
    public $saldoEduPaySiswa;

    // TRANSAKSI TOPUP
    public $jenis_transaksi_topup = 'topup tunai';
    public $nominal_topup = 0;
    public $deskripsi_topup = null;

    // TRANSAKSI PENARIKAN
    public $nominal_penarikan = 0;
    public $deskripsi_penarikan = null;

    protected $listeners = [
        'siswaSelected',

        'siswaUpdated' => 'siswaUpdated',

        'successTransaksiEduPay'
    ];

    protected function fillSiswaData($siswa): void
    {
        $this->ms_penempatan_siswa_id = $siswa->ms_penempatan_siswa_id;
        $this->ms_siswa_id = $siswa->ms_siswa_id;
        $this->ms_jenjang_id = $siswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id;

        $this->nama_siswa = $siswa->ms_siswa->nama_siswa ?? null;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas ?? null;

        $this->educard = $siswa->ms_siswa->ms_educard->kode_kartu ?? null;
        $this->nisn = $siswa->ms_siswa->nisn ?? null;
        $this->tanggal_lahir = $siswa->ms_siswa->tanggal_lahir ?? null;
        $this->alamat = $siswa->ms_siswa->alamat ?? null;
        $this->telepon = $siswa->ms_siswa->telepon ?? null;
    }

    public function successTransaksiEduPay()
    {
        if ($this->ms_siswa_id) {
            $this->updateSaldoEdupay();
        }
    }

    protected function updateSaldoEdupay()
    {
        $saldo = SaldoEduPay::getSaldo($this->ms_siswa_id, 'siswa');

        $this->saldoEduPaySiswa = $saldo->saldo_edupay;
    }


    public function siswaSelected($id)
    {
        $siswa = PenempatanSiswa::with('ms_siswa', 'ms_kelas', 'ms_jenjang', 'ms_tahun_ajar')
            ->findOrFail($id);

        $this->fillSiswaData($siswa);

        // 🔥 modular update
        $this->updateSaldoEdupay();
    }

    public function siswaUpdated()
    {
        if (!$this->ms_penempatan_siswa_id) return;

        $siswa = PenempatanSiswa::with([
            'ms_siswa.ms_educard',
            'ms_jenjang',
            'ms_tahun_ajar',
            'ms_kelas'
        ])->find($this->ms_penempatan_siswa_id);

        if (!$siswa) return;

        $this->fillSiswaData($siswa); // 🔥 hanya ini
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
            '%s EduPay Rp %s siswa %s',
            $this->jenis_transaksi_topup,
            number_format($this->nominal_topup, 0, ',', '.'),
            $this->nama_siswa
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
        // SIMPAN TRANSAKSI EDUPAY
        // =========================================================
        TransaksiEduPay::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_pengguna_id' => $ms_pengguna_id,
            'jenis_transaksi' => $this->jenis_transaksi_topup,
            'nominal' => $this->nominal_topup,

            'tanggal' => now(),

            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
            'deskripsi' => $this->deskripsi_topup,
        ]);

        // =========================================================
        // UPDATE SALDO EDUPAY
        // =========================================================
        $saldo->increment(
            'saldo_edupay', $this->nominal_topup
        );
    }
    
    protected function processPenarikan($saldo)
    {
        $ms_pengguna_id = auth()->user()->ms_pengguna_id;

        // Kode rekening
        $kode_rekening_kas = 11001;
        $kode_rekening_edupay = 22002;

        $deskripsiJurnal = sprintf(
            'Penarikan Tunai EduPay Rp %s siswa %s',
            number_format($this->nominal_penarikan, 0, ',', '.'),
            $this->nama_siswa
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
        // SIMPAN TRANSAKSI EDUPAY
        // =========================================================
        TransaksiEduPay::create([
            'user_type' => 'siswa',

            'user_id' => $this->ms_siswa_id,

            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,

            'ms_pengguna_id' => $ms_pengguna_id,
           
            'jenis_transaksi' => 'penarikan',
           
            'nominal' => $this->nominal_penarikan,

            'tanggal' => now(),

            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,

            'deskripsi' => $this->deskripsi_penarikan,
        ]);

        // =========================================================
        // UPDATE SALDO EDUPAY
        // =========================================================
        $saldo->decrement(
            'saldo_edupay',
            $this->nominal_penarikan
        );
    }

    protected function afterSuccess()
    {
        $this->reset([
            'deskripsi_topup',
            'nominal_topup',
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

            if (!$this->ms_siswa_id) {
                throw new \Exception('Siswa tidak ditemukan!');
            }

            // 🔥 Ambil saldo + lock (penting untuk uang)
            $saldo = SaldoEduPay::getSaldo(
                $this->ms_siswa_id, 'siswa'
            );

            $saldo = SaldoEduPay::where('ms_saldo_edupay_id', $saldo->ms_saldo_edupay_id)
                ->lockForUpdate()
                ->first();                  

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

            if (!$this->ms_siswa_id) {
                throw new \Exception('Siswa tidak ditemukan!');
            }

            // 🔥 Ambil saldo + lock (penting untuk uang)
            $saldo = SaldoEduPay::where('user_id', $this->ms_siswa_id)
                ->where('user_type', 'siswa')
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
        return view('livewire.transaksi-edu-pay-siswa.data-siswa');
    }
    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
