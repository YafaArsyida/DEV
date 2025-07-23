<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TabunganSiswa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DataSiswa extends Component
{
    public $ms_siswa_id;
    public $ms_penempatan_siswa_id = null, $nama_siswa = null, $nama_kelas = null, $nisn = null, $tanggal_lahir = null, $alamat = null, $telepon = null;

    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $saldo_tabungan_siswa;
    public $total_kredit_tabungan;
    public $total_debit_tabungan;

    // save kredit
    public $nominal_kredit;
    public $deskripsi_kredit;

    // save debit
    public $nominal_debit;
    public $deskripsi_debit;

    protected $listeners = [
        'siswaSelected',
        'refreshTabunganSiswa',
    ];

    public function refreshTabunganSiswa()
    {
        // Jika siswa sedang dipilih, perbarui data siswa
        if ($this->ms_penempatan_siswa_id) {
            $this->siswaSelected($this->ms_penempatan_siswa_id);
        }
    }

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        // Mengambil detail siswa berdasarkan ms_penempatan_siswa_id
        $siswa = PenempatanSiswa::with('ms_siswa', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($ms_penempatan_siswa_id);

        // Mengisi properti dengan data penempatan siswa
        $this->ms_jenjang_id = $siswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id;
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;

        $this->ms_siswa_id = $siswa->ms_siswa_id;

        $this->nama_siswa = $siswa->ms_siswa->nama_siswa;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas;
        $this->nisn = $siswa->ms_siswa->nisn;
        $this->tanggal_lahir = $siswa->ms_siswa->tanggal_lahir;
        $this->alamat = $siswa->ms_siswa->alamat;
        $this->telepon = $siswa->ms_siswa->telepon;

        // Hitung saldo tabungan siswa
        $this->saldo_tabungan_siswa = $siswa->ms_siswa->saldo_tabungan_siswa();
        $this->total_kredit_tabungan = $siswa->ms_siswa->total_kredit_tabungan();
        $this->total_debit_tabungan = $siswa->ms_siswa->total_debit_tabungan();
    }

    // kredit
    public function simpanKredit()
    {
        try {
            // Pastikan siswa dipilih
            if (!$this->ms_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih siswa terlebih dahulu.']);
                return;
            }

            if (!$this->ms_penempatan_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih PENEMPATAN terlebih dahulu.']);
                return;
            }

            // Validasi input untuk kredit
            $validatedData = $this->validate([
                'nominal_kredit' => 'required|numeric|min:1000',
                'deskripsi_kredit' => 'nullable|string|max:255',
            ], [
                'nominal_kredit.required' => 'Nominal kredit harus diisi.',
                'nominal_kredit.numeric' => 'Nominal kredit harus berupa angka.',
                'nominal_kredit.min' => 'Nominal kredit harus minimal 1.',
                'deskripsi_kredit.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_tabungan = 22001;

            $deskripsiJurnal = "Setoran Tunai Tabungan Rp {$this->nominal_kredit} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'debit',
                'nominal' => $this->nominal_kredit,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data untuk jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_tabungan,
                'posisi' => 'kredit',
                'nominal' => $this->nominal_kredit,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi kredit
            TabunganSiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'setoran', // Jenis transaksi untuk kredit
                'nominal' => $this->nominal_kredit,
                'deskripsi' => $this->deskripsi_kredit,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);

            // Reset input
            $this->reset(['nominal_kredit', 'deskripsi_kredit']);

            // Refresh saldo siswa
            $siswa = Siswa::find($this->ms_siswa_id);
            $this->saldo_tabungan_siswa = $siswa->saldo_tabungan_siswa(); // Menghitung saldo secara dinamis
            $this->total_kredit_tabungan = $siswa->total_kredit_tabungan();
            $this->total_debit_tabungan = $siswa->total_debit_tabungan();

            // Notifikasi sukses
            $this->emit('refreshTabunganSiswa');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi kredit berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function simpanDebit()
    {
        try {
            // Pastikan siswa dipilih
            if (!$this->ms_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih siswa terlebih dahulu.']);
                return;
            }
            if (!$this->ms_penempatan_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih PENEMPATAN terlebih dahulu.']);
                return;
            }

            // Validasi input untuk debit
            $validatedData = $this->validate([
                'nominal_debit' => 'required|numeric|min:1000',
                'deskripsi_debit' => 'nullable|string|max:255',
            ], [
                'nominal_debit.required' => 'Nominal debit harus diisi.',
                'nominal_debit.numeric' => 'Nominal debit harus berupa angka.',
                'nominal_debit.min' => 'Nominal debit harus minimal 1000.',
                'deskripsi_debit.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            // Periksa saldo siswa saat ini
            $siswa = Siswa::find($this->ms_siswa_id);
            $saldo_sekarang = $siswa->saldo_tabungan_siswa(); // Saldo dihitung secara dinamis

            // Periksa apakah saldo cukup
            if ($this->nominal_debit > $saldo_sekarang) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo tidak mencukupi untuk transaksi ini.']);
                return;
            }

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_tabungan = 22001;
            $deskripsiJurnal = "Penarikan Tunai Tabungan Rp {$this->nominal_debit} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_tabungan,
                'posisi' => 'debit',
                'nominal' => $this->nominal_debit,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'kredit',
                'nominal' => $this->nominal_debit,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi debit
            TabunganSiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'penarikan', // Jenis transaksi untuk debit
                'nominal' => $this->nominal_debit,
                'deskripsi' => $this->deskripsi_debit,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);

            // Reset input
            $this->reset(['nominal_debit', 'deskripsi_debit']);

            // Refresh saldo siswa
            $this->saldo_tabungan_siswa = $siswa->saldo_tabungan_siswa(); // Menghitung saldo terkini
            $this->total_kredit_tabungan = $siswa->total_kredit_tabungan();
            $this->total_debit_tabungan = $siswa->total_debit_tabungan();

            // Notifikasi sukses
            $this->emit('refreshTabunganSiswa');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi debit berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.transaksi-tabungan-siswa.data-siswa');
    }
}
