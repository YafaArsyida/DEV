<?php

namespace App\Http\Livewire\TransaksiTabunganPegawai;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Pegawai;
use App\Models\SaldoTabungan;
use App\Models\TransaksiTabungan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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

    public $saldoTabunganPegawai;

    // save kredit
    public $nominal_kredit = 0;
    public $deskripsi_kredit;

    // save debit
    public $nominal_debit = 0;
    public $deskripsi_debit;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',

        'pegawaiSelected',
        
        'refreshTabungans'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
    }

    protected function fillPegawaiData($pegawai): void
    {
        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->nama_pegawai = $pegawai->nama_pegawai;
        $this->educard_pegawai = $pegawai->ms_educard->kode_kartu ?? null;
        $this->jabatan = $pegawai->ms_jabatan->nama_jabatan ?? null;
        $this->telepon_pegawai = $pegawai->telepon ?? null;
        $this->alamat_pegawai = $pegawai->alamat ?? null;
    }

    public function refreshTabungans()
    {
        if ($this->ms_pegawai_id) {
            $this->updateSaldoTabungan();
        }
    }

    protected function updateSaldoTabungan()
    {
        $saldo = SaldoTabungan::getSaldo($this->ms_pegawai_id, 'pegawai');

        $this->saldoTabunganPegawai = $saldo->saldo_tabungan;
    }

    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard', 'ms_transaksi_tabungan')
            ->findOrFail($ms_pegawai_id);

        if (!$pegawai) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pegawai tidak ditemukan.']);
            return;
        }

        $this->fillPegawaiData($pegawai);

        // 🔥 ambil saldo sekali
        $this->updateSaldoTabungan();
    }

    protected function processKredit($saldo)
    {
        $ms_pengguna_id = Auth::id();

        $kode_rekening_kas = 11001;
        $rekening_tabungan_pegawai = 22004;

        $deskripsiJurnal = "Setoran Tunai Tabungan Rp {$this->nominal_kredit} pegawai {$this->nama_pegawai}";

        // Debit (Kas)
        $jurnalDebitId = AkuntansiJurnalDetail::create([
            'kode_rekening' => $kode_rekening_kas,
            'posisi' => 'debit',
            'nominal' => $this->nominal_kredit,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_departemen_id' => 'SEKOLAH',
            'is_canceled' => 'active',
            'deskripsi' => $deskripsiJurnal
        ])->akuntansi_jurnal_detail_id;

        // Kredit (Tabungan)
        $jurnalKreditId = AkuntansiJurnalDetail::create([
            'kode_rekening' => $rekening_tabungan_pegawai,
            'posisi' => 'kredit',
            'nominal' => $this->nominal_kredit,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_departemen_id' => 'SEKOLAH',
            'is_canceled' => 'active',
            'deskripsi' => $deskripsiJurnal
        ])->akuntansi_jurnal_detail_id;

        // Simpan transaksi
        TransaksiTabungan::create([
            'user_type' => 'pegawai',
            'user_id' => $this->ms_pegawai_id,
            'ms_pengguna_id' => $ms_pengguna_id,
            'jenis_transaksi' => 'setoran',
            'nominal' => $this->nominal_kredit,
            'tanggal' => now(),
            'deskripsi' => $this->deskripsi_kredit,
            'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
            'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
        ]);

        // 🔥 update saldo (pakai object yg sama)
        $saldo->increment('saldo_tabungan', $this->nominal_kredit);
    }

    protected function processDebit($saldo)
    {
        $ms_pengguna_id = Auth::id();

        $kode_rekening_kas = 11001;
        $rekening_tabungan_pegawai = 22004;

        $deskripsiJurnal = "Penarikan Tunai Tabungan Rp {$this->nominal_debit} pegawai {$this->nama_pegawai}";

        $jurnalDebitId = AkuntansiJurnalDetail::create([
            'kode_rekening' => $rekening_tabungan_pegawai,
            'posisi' => 'debit',
            'nominal' => $this->nominal_debit,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_departemen_id' => 'SEKOLAH',
            'is_canceled' => 'active',
            'deskripsi' => $deskripsiJurnal,
        ])->akuntansi_jurnal_detail_id;

        $jurnalKreditId = AkuntansiJurnalDetail::create([
            'kode_rekening' => $kode_rekening_kas,
            'posisi' => 'kredit',
            'nominal' => $this->nominal_debit,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_departemen_id' => 'SEKOLAH',
            'is_canceled' => 'active',
            'deskripsi' => $deskripsiJurnal,
        ])->akuntansi_jurnal_detail_id;

        TransaksiTabungan::create([
            'user_type' => 'pegawai',
            'user_id' => $this->ms_pegawai_id,
            'ms_pengguna_id' => $ms_pengguna_id,
            'jenis_transaksi' => 'penarikan',
            'nominal' => $this->nominal_debit,
            'tanggal' => now(),
            'deskripsi' => $this->deskripsi_debit,
            'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
            'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
        ]);

        // 🔥 penting: pakai object yang sama (anti race condition ringan)
        $saldo->decrement('saldo_tabungan', $this->nominal_debit);
    }

    protected function afterSuccess()
    {
        $this->reset([
            'nominal_kredit',
            'deskripsi_kredit',
            'nominal_debit',
            'deskripsi_debit'
        ]);

        // cukup update saldo saja
        $this->updateSaldoTabungan();

        // kalau ada table transaksi → emit khusus
        $this->emit('successTransaksiTabungan');

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi berhasil disimpan.'
        ]);
    }

    public function simpanKredit()
    {
        DB::beginTransaction();

        try {
            $this->nominal_kredit = $this->normalizeAmount($this->nominal_kredit);

            $this->validate([
                'nominal_kredit' => 'required|numeric|min:1000',
                'deskripsi_kredit' => 'nullable|string|max:255',
            ], [
                'nominal_kredit.required' => 'Nominal kredit harus diisi.',
                'nominal_kredit.numeric' => 'Nominal kredit harus berupa angka.',
                'nominal_kredit.min' => 'Nominal kredit minimal 1000.',
                'deskripsi_kredit.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            if (!$this->ms_pegawai_id) {
                throw new \Exception('Pegawai tidak ditemukan!');
            }

            $saldo = SaldoTabungan::where('user_id', $this->ms_pegawai_id)
                ->where('user_type', 'pegawai')
                ->lockForUpdate()
                ->first();

            if (!$saldo) {
                throw new \Exception('Data saldo tidak ditemukan!');
            }

            $this->processKredit($saldo);

            DB::commit();

            $this->afterSuccess();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function simpanDebit()
    {
        DB::beginTransaction();

        try {
            $this->nominal_debit = $this->normalizeAmount($this->nominal_debit);

            $this->validate([
                'nominal_debit' => 'required|numeric|min:1000',
                'deskripsi_debit' => 'nullable|string|max:255',
            ], [
                'nominal_debit.required' => 'Nominal debit harus diisi.',
                'nominal_debit.numeric' => 'Nominal debit harus berupa angka.',
                'nominal_debit.min' => 'Nominal debit minimal 1000.',
                'deskripsi_debit.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            if (!$this->ms_pegawai_id) {
                throw new \Exception('Pegawai tidak ditemukan!');
            }

            $saldo = SaldoTabungan::where('user_id', $this->ms_pegawai_id)
                ->where('user_type', 'pegawai')
                ->lockForUpdate()
                ->first();

            if (!$saldo) {
                throw new \Exception('Data saldo tidak ditemukan!');
            }

            if ($this->nominal_debit > $saldo->saldo_tabungan) {
                throw new \Exception('Saldo tidak cukup');
            }

            $this->processDebit($saldo);

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
        return view('livewire.transaksi-tabungan-pegawai.data-pegawai');
    }
    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
