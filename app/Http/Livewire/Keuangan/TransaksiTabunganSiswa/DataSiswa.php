<?php

namespace App\Http\Livewire\Keuangan\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\PenempatanSiswa;
use App\Models\SaldoTabungan;
use App\Models\TransaksiTabungan;
use App\Services\AccountingService;
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
    public $nominal_kredit = 0;
    public $deskripsi_kredit = null;

    // TRANSAKSI DEBIT
    public $nominal_debit = 0;
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
        $ms_pengguna_id = auth()->user()->ms_pengguna_id;

        $kode_rekening_kas = 11001;
        $kode_rekening_tabungan = 22001;

        $deskripsiJurnal = sprintf(
            'Setoran Tunai Tabungan Rp %s siswa %s',
            number_format($this->nominal_kredit, 0, ',', '.'),
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

                // Debit Kas
                [
                    'kode_rekening' => $kode_rekening_kas,
                    'posisi' => 'debit',
                    'nominal' => $this->nominal_kredit,
                ],

                // Kredit Tabungan Siswa
                [
                    'kode_rekening' => $kode_rekening_tabungan,
                    'posisi' => 'kredit',
                    'nominal' => $this->nominal_kredit,
                ],

            ],
        ]);

        // =========================================================
        // SIMPAN TRANSAKSI TABUNGAN
        // =========================================================
        TransaksiTabungan::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_pengguna_id' => $ms_pengguna_id,
            'jenis_transaksi' => 'setoran',
            'nominal' => $this->nominal_kredit,
            'tanggal' => now(),
            'deskripsi' => $this->deskripsi_kredit,

            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
        ]);

        // =========================================================
        // UPDATE SALDO TABUNGAN
        // =========================================================
        $saldo->increment('saldo_tabungan', $this->nominal_kredit);
    }

    protected function processDebit($saldo)
    {
        $ms_pengguna_id = auth()->user()->ms_pengguna_id;

        $kode_rekening_kas = 11001;
        $kode_rekening_tabungan = 22001;

        $deskripsiJurnal = sprintf(
            'Penarikan Tunai Tabungan Rp %s siswa %s',
            number_format($this->nominal_debit, 0, ',', '.'),
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
                // Debit Saldo Tabungan
                [
                    'kode_rekening' => $kode_rekening_tabungan,
                    'posisi' => 'debit',
                    'nominal' => $this->nominal_debit,
                ],

                // Kredit Kas
                [
                    'kode_rekening' => $kode_rekening_kas,
                    'posisi' => 'kredit',
                    'nominal' => $this->nominal_debit,
                ],
            ],
        ]);

        // =========================================================
        // SIMPAN TRANSAKSI TABUNGAN
        // =========================================================
        TransaksiTabungan::create([
            'user_type' => 'siswa',
            'user_id' => $this->ms_siswa_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_pengguna_id' => $ms_pengguna_id,
            'jenis_transaksi' => 'penarikan',
            'nominal' => $this->nominal_debit,
            'tanggal' => now(),
            'deskripsi' => $this->deskripsi_debit,
            'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
        ]);

        // =========================================================
        // UPDATE SALDO TABUNGAN
        // =========================================================
        $saldo->decrement(
            'saldo_tabungan',
            $this->nominal_debit
        );
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

            if (!$this->ms_siswa_id) {
                throw new \Exception('Siswa tidak ditemukan!');
            }
            $saldo = SaldoTabungan::where('user_id', $this->ms_siswa_id)
                ->where('user_type', 'siswa')
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

            if (!$this->ms_siswa_id) {
                throw new \Exception('Siswa tidak ditemukan!');
            }

            $saldo = SaldoTabungan::where('user_id', $this->ms_siswa_id)
                ->where('user_type', 'siswa')
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
        return view('livewire.keuangan.transaksi-tabungan-siswa.data-siswa');
    }
    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
