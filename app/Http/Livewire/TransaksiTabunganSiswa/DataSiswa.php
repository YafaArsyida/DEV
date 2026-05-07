<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\PenempatanSiswa;
use App\Models\SaldoTabungan;
use App\Models\TransaksiTabungan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DataSiswa extends Component
{
    // IDENTITAS SISWA
    public $ms_siswa_id = null;
    public $ms_penempatan_siswa_id = null;
    public $nama_siswa = null;
    public $nama_kelas = null;
    public $nisn = null;
    public $tanggal_lahir = null;
    public $alamat = null;
    public $telepon = null;

    // FILTER / RELASI
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    // SALDO
    public $saldoTabunganSiswa;

    // TRANSAKSI KREDIT
    public $nominal_kredit = null;
    public $deskripsi_kredit = null;

    // TRANSAKSI DEBIT
    public $nominal_debit = null;
    public $deskripsi_debit = null;

    protected $listeners = [
        'siswaSelected',

        'siswaUpdated' => 'siswaUpdated',

        'successTransaksiTabungan'
    ];

    protected function fillSiswaData($siswa): void
    {
        $this->ms_penempatan_siswa_id = $siswa->ms_penempatan_siswa_id;
        $this->ms_siswa_id = $siswa->ms_siswa_id;
        $this->ms_jenjang_id = $siswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id;

        $this->nama_siswa = $siswa->ms_siswa->nama_siswa ?? null;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas ?? null;

        $this->nisn = $siswa->ms_siswa->nisn ?? null;
        $this->tanggal_lahir = $siswa->ms_siswa->tanggal_lahir ?? null;
        $this->alamat = $siswa->ms_siswa->alamat ?? null;
        $this->telepon = $siswa->ms_siswa->telepon ?? null;
    }

    public function successTransaksiTabungan()
    {
        if ($this->ms_siswa_id) {
            $this->updateSaldoTabungan();
        }
    }

    protected function updateSaldoTabungan()
    {
        $saldo = SaldoTabungan::getSaldo($this->ms_siswa_id, 'siswa');

        $this->saldoTabunganSiswa = $saldo->saldo_tabungan;
    }

    public function siswaSelected($id)
    {
        $siswa = PenempatanSiswa::with('ms_siswa', 'ms_kelas', 'ms_jenjang', 'ms_tahun_ajar')
            ->findOrFail($id);

        if (!$siswa) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Siswa tidak ditemukan.']);
            return;
        }
        
        $this->fillSiswaData($siswa);

        // 🔥 ambil saldo sekali
        $this->updateSaldoTabungan();
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

    protected function processKredit($saldo)
    {
        $ms_pengguna_id = Auth::id();

        $kode_rekening_kas = 11001;
        $kode_rekening_tabungan = 22001;

        $deskripsiJurnal = "Setoran Tunai Tabungan Rp {$this->nominal_kredit} siswa {$this->nama_siswa}";

        // Debit (Kas)
        $jurnalDebitId = AkuntansiJurnalDetail::create([
            'kode_rekening' => $kode_rekening_kas,
            'posisi' => 'debit',
            'nominal' => $this->nominal_kredit,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'is_canceled' => 'active',
            'deskripsi' => $deskripsiJurnal
        ])->akuntansi_jurnal_detail_id;

        // Kredit (Tabungan)
        $jurnalKreditId = AkuntansiJurnalDetail::create([
            'kode_rekening' => $kode_rekening_tabungan,
            'posisi' => 'kredit',
            'nominal' => $this->nominal_kredit,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'is_canceled' => 'active',
            'deskripsi' => $deskripsiJurnal
        ])->akuntansi_jurnal_detail_id;

        // Simpan transaksi
        TransaksiTabungan::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
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
        $kode_rekening_tabungan = 22001;

        $deskripsiJurnal = "Penarikan Tunai Tabungan Rp {$this->nominal_debit} siswa {$this->nama_siswa}";

        $jurnalDebitId = AkuntansiJurnalDetail::create([
            'kode_rekening' => $kode_rekening_tabungan,
            'posisi' => 'debit',
            'nominal' => $this->nominal_debit,
            'tanggal_transaksi' => now(),
            'ms_pengguna_id' => $ms_pengguna_id,
            'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
            'ms_jenjang_id' => $this->ms_jenjang_id,
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
            'is_canceled' => 'active',
            'deskripsi' => $deskripsiJurnal,
        ])->akuntansi_jurnal_detail_id;

        TransaksiTabungan::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
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
                'nominal_kredit', 'deskripsi_kredit',
                'nominal_debit', 'deskripsi_debit'
            ]);

        // ✅ cukup update saldo saja
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
            $this->validate([
                'nominal_kredit' => 'required|numeric|min:1000',
                'deskripsi_kredit' => 'nullable|string|max:255',
            ], [
                'nominal_kredit.required' => 'Nominal kredit harus diisi.',
                'nominal_kredit.numeric' => 'Nominal kredit harus berupa angka.',
                'nominal_kredit.min' => 'Nominal kredit minimal 1000.',
                'deskripsi_kredit.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            if (!$this->ms_siswa_id) {
                throw new \Exception('Siswa tidak ditemukan!');
            }
            $saldo = SaldoTabungan::where('user_id', $this->ms_siswa_id)
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
            $this->validate([
                'nominal_debit' => 'required|numeric|min:1000',
                'deskripsi_debit' => 'nullable|string|max:255',
            ], [
                'nominal_debit.required' => 'Nominal debit harus diisi.',
                'nominal_debit.numeric' => 'Nominal debit harus berupa angka.',
                'nominal_debit.min' => 'Nominal debit minimal 1000.',
                'deskripsi_debit.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            if (!$this->ms_siswa_id) {
                throw new \Exception('Siswa tidak ditemukan!');
            }

            $saldo = SaldoTabungan::where('user_id', $this->ms_siswa_id)
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
        return view('livewire.transaksi-tabungan-siswa.data-siswa');
    }
}
