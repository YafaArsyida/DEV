<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\EduPaySiswa;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DataSiswa extends Component
{
    public $ms_siswa_id;
    public $ms_penempatan_siswa_id = null, $nama_siswa = null, $nama_kelas = null, $nisn = null, $tanggal_lahir = null, $alamat = null, $telepon = null;

    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $saldo_edupay_siswa;
    public $total_pemasukan_edupay_siswa;
    public $total_penarikan;
    public $total_pembayaran;
    public $total_pengeluaran_edupay_siswa;

    // save topup
    public $nominal_topup;
    public $deskripsi_topup;

    // save penarikan
    public $nominal_penarikan;
    public $deskripsi_penarikan;

    protected $listeners = [
        'siswaSelected',
        'refreshEduPays'
    ];

    public function refreshEduPays()
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

        // Mengisi properti dengan data yang ditemukan
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;

        $this->ms_jenjang_id = $siswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id;

        $this->ms_siswa_id = $siswa->ms_siswa_id;
        $this->nama_siswa = $siswa->ms_siswa->nama_siswa;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas;
        $this->nisn = $siswa->ms_siswa->nisn;
        $this->tanggal_lahir = $siswa->ms_siswa->tanggal_lahir;
        $this->alamat = $siswa->ms_siswa->alamat;
        $this->telepon = $siswa->ms_siswa->telepon;

        // Hitung saldo tabungan siswa
        $this->saldo_edupay_siswa = $siswa->ms_siswa->saldo_edupay_siswa(); // Menghitung saldo secara dinamis
        $this->total_pemasukan_edupay_siswa = $siswa->ms_siswa->total_pemasukan_edupay_siswa();
        $this->total_pengeluaran_edupay_siswa = $siswa->ms_siswa->total_pengeluaran_edupay_siswa();
    }

    // kredit
    public function simpanTopUp()
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

            // Validasi input untuk topup
            $validatedData = $this->validate([
                'nominal_topup' => 'required|numeric|min:1000',
                'deskripsi_topup' => 'nullable|string|max:255',
            ], [
                'nominal_topup.required' => 'Nominal top-up harus diisi.',
                'nominal_topup.numeric' => 'Nominal top-up harus berupa angka.',
                'nominal_topup.min' => 'Nominal top-up harus minimal 1000.',
                'deskripsi_topup.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_edupay = 22002;

            $deskripsiJurnal = "Top Up Tunai EduPay Rp {$this->nominal_topup} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'debit',
                'nominal' => $this->nominal_topup,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data untuk jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_edupay,
                'posisi' => 'kredit',
                'nominal' => $this->nominal_topup,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi topup
            EduPaySiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => auth()->id(),
                'jenis_transaksi' => 'topup tunai', // Jenis transaksi untuk top-up
                'nominal' => $this->nominal_topup,
                'deskripsi' => $this->deskripsi_topup,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);

            // Reset input
            $this->reset(['nominal_topup', 'deskripsi_topup']);

            // Refresh saldo siswa EduPay
            $siswa = Siswa::find($this->ms_siswa_id);
            $this->saldo_edupay_siswa = $siswa->saldo_edupay_siswa(); // Menghitung saldo secara dinamis
            $this->total_pemasukan_edupay_siswa = $siswa->total_pemasukan_edupay_siswa();
            $this->total_pengeluaran_edupay_siswa = $siswa->total_pengeluaran_edupay_siswa();

            // // Notifikasi sukses
            $this->emit('refreshEduPays');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi top-up berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function simpanPengeluaran()
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

            // Validasi input untuk pengeluaran
            $validatedData = $this->validate([
                'nominal_penarikan' => 'required|numeric|min:1000',
                'deskripsi_penarikan' => 'nullable|string|max:255',
            ], [
                'nominal_penarikan.required' => 'Nominal tarik tunai harus diisi.',
                'nominal_penarikan.numeric' => 'Nominal tarik tunai harus berupa angka.',
                'nominal_penarikan.min' => 'Nominal tarik tunai harus minimal 1000.',
                'deskripsi_penarikan.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            // Periksa saldo siswa saat ini
            $siswa = Siswa::find($this->ms_siswa_id);
            $saldo_sekarang = $siswa->saldo_edupay_siswa(); // Saldo dihitung secara dinamis dari saldo EduPay

            // Periksa apakah saldo cukup
            if ($this->nominal_penarikan > $saldo_sekarang) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo tidak mencukupi untuk transaksi ini.']);
                return;
            }

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_edupay = 22002;
            $deskripsiJurnal = "Penarikan Tunai EduPay Rp {$this->nominal_penarikan} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_edupay,
                'posisi' => 'debit',
                'nominal' => $this->nominal_penarikan,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data untuk jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'kredit',
                'nominal' => $this->nominal_penarikan,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi pengeluaran
            EduPaySiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'penarikan', // Jenis transaksi untuk tarik tunai
                'nominal' => $this->nominal_penarikan,
                'deskripsi' => $this->deskripsi_penarikan,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);
            // Reset input
            $this->reset(['nominal_penarikan', 'deskripsi_penarikan']);

            // Refresh saldo siswa
            $this->saldo_edupay_siswa = $siswa->saldo_edupay_siswa(); // Menghitung saldo secara dinamis
            $this->total_pemasukan_edupay_siswa = $siswa->total_pemasukan_edupay_siswa();
            $this->total_pengeluaran_edupay_siswa = $siswa->total_pengeluaran_edupay_siswa();

            // Notifikasi sukses
            $this->emit('refreshEduPays');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tarik tunai berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-edu-pay-siswa.data-siswa');
    }
}
