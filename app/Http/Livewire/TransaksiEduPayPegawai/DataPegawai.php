<?php

namespace App\Http\Livewire\TransaksiEduPayPegawai;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Pegawai;
use App\Models\TransaksiEduPay;
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
    public $saldo_edupay_pegawai;
    public $total_pemasukan_pegawai;
    public $total_pengeluaran_pegawai;

    // save topup
    public $nominal_topup;
    public $deskripsi_topup;

    // save penarikan
    public $nominal_penarikan;
    public $deskripsi_penarikan;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'pegawaiSelected',
        'refreshEduPays'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
    }

    public function refreshEduPays()
    {
        if ($this->ms_pegawai_id) {
            $this->pegawaiSelected($this->ms_pegawai_id);
        }
    }

    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard')
            ->findOrFail($ms_pegawai_id);

        if (!$pegawai) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pegawai tidak ditemukan.']);
            return;
        }

        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->nama_pegawai = $pegawai->nama_pegawai;
        $this->educard_pegawai = $pegawai->ms_educard->kode_kartu ?? null;
        $this->jabatan = $pegawai->ms_jabatan->nama_jabatan ?? null;
        $this->telepon_pegawai = $pegawai->telepon;
        $this->alamat_pegawai = $pegawai->alamat;

        // Hitung saldo dan transaksi edupay pegawai
        $this->saldo_edupay_pegawai     = $pegawai->saldo_edupay_pegawai();
        $this->total_pemasukan_pegawai  = $pegawai->total_pemasukan_edupay();
        $this->total_pengeluaran_pegawai = $pegawai->total_pengeluaran_edupay();
    }

    public function simpanTopUp()
    {
        DB::beginTransaction();

        try {
            // Pastikan pegawai dipilih
            if (!$this->ms_pegawai_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih pegawai terlebih dahulu.']);
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

            $nominal = number_format($this->nominal_topup, 0, ',', '.');
            $deskripsiJurnal = "Top Up Tunai EduPay Rp {$nominal} Pegawai {$this->nama_pegawai}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'debit',
                'nominal' => $this->nominal_topup,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => $ms_pengguna_id,
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
                'ms_pengguna_id' => $ms_pengguna_id,
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi topup
            TransaksiEduPay::create([
                'user_type' => 'pegawai',
                'user_id' => $this->ms_pegawai_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'topup tunai',
                'nominal' => $this->nominal_topup,
                'tanggal' => now(),
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'deskripsi' => $this->deskripsi_topup,
            ]);

            // Commit transaksi
            DB::commit();

            // Reset input
            $this->reset(['nominal_topup', 'deskripsi_topup']);

            // Refresh saldo EduPay
            $pegawai = Pegawai::with('ms_jabatan', 'ms_educard')
                ->findOrFail($this->ms_pegawai_id);

            // Hitung saldo dan transaksi edupay pegawai
            $this->saldo_edupay_pegawai     = $pegawai->saldo_edupay_pegawai();
            $this->total_pemasukan_pegawai  = $pegawai->total_pemasukan_edupay();
            $this->total_pengeluaran_pegawai = $pegawai->total_pengeluaran_edupay();

            // Emit event & notifikasi sukses
            $this->emit('refreshEduPays');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi top-up berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();

            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function simpanPengeluaran()
    {
        DB::beginTransaction();

        try {
            // Pastikan pegawai dipilih
            if (!$this->ms_pegawai_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih pegawai terlebih dahulu.']);
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

            $saldo_sekarang = $this->saldo_edupay_pegawai; // Saldo dihitung secara dinamis dari saldo EduPay

            // Periksa apakah saldo cukup
            if ($this->nominal_penarikan > $saldo_sekarang) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo tidak mencukupi untuk transaksi ini.']);
                return;
            }

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_edupay = 22002;

            $nominal = number_format($this->nominal_penarikan, 0, ',', '.');
            $deskripsiJurnal = "Penarikan Tunai EduPay Rp {$nominal} Pegawai {$this->nama_pegawai}";

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
            TransaksiEduPay::create([
                'user_type' => 'pegawai',
                'user_id' => $this->ms_pegawai_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'penarikan', // Jenis transaksi untuk tarik tunai
                'nominal' => $this->nominal_penarikan,
                'tanggal' => now(),
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'deskripsi' => $this->deskripsi_penarikan,
            ]);

            // Commit transaksi
            DB::commit();

            // Reset input
            $this->reset(['nominal_penarikan', 'deskripsi_penarikan']);

            // Refresh saldo EduPay
            $pegawai = Pegawai::with('ms_jabatan', 'ms_educard')
                ->findOrFail($this->ms_pegawai_id);

            // Hitung saldo dan transaksi edupay pegawai
            $this->saldo_edupay_pegawai     = $pegawai->saldo_edupay_pegawai();
            $this->total_pemasukan_pegawai  = $pegawai->total_pemasukan_edupay();
            $this->total_pengeluaran_pegawai = $pegawai->total_pengeluaran_edupay();

            // Notifikasi sukses
            $this->emit('refreshEduPays');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tarik tunai berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();

            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-edu-pay-pegawai.data-pegawai');
    }
}
