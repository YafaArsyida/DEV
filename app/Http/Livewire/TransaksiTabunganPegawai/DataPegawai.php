<?php

namespace App\Http\Livewire\TransaksiTabunganPegawai;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Pegawai;
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

    public $saldo_tabungan_pegawai;
    public $total_kredit_tabungan;
    public $total_debit_tabungan;

    // save kredit
    public $nominal_kredit;
    public $deskripsi_kredit;

    // save debit
    public $nominal_debit;
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

    public function refreshTabungans()
    {
        if ($this->ms_pegawai_id) {
            $this->pegawaiSelected($this->ms_pegawai_id);
        }
    }

    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard', 'ms_transaksi_tabungan')
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
        $this->saldo_tabungan_pegawai     = $pegawai->saldo_tabungan_pegawai();
        $this->total_kredit_tabungan  = $pegawai->total_kredit_tabungan();
        $this->total_debit_tabungan = $pegawai->total_debit_tabungan();
    }

    public function simpanKredit()
    {
        DB::beginTransaction();

        try {
            // Pastikan pegawai dipilih
            if (!$this->ms_pegawai_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih pegawai terlebih dahulu.']);
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
            $kode_rekening_tabungan = 22004;

            $deskripsiJurnal = "Setoran Tunai Tabungan Rp {$this->nominal_kredit} pegawai {$this->nama_pegawai}";

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
            TransaksiTabungan::create([
                'user_type' => 'pegawai',
                'user_id' => $this->ms_pegawai_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'setoran', // Jenis transaksi untuk kredit
                'nominal' => $this->nominal_kredit,
                'tanggal' => now(),
                'deskripsi' => $this->deskripsi_kredit,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
            ]);

            DB::commit();
            // Reset input
            $this->reset(['nominal_kredit', 'deskripsi_kredit']);

            // Refresh saldo 
            $pegawai = Pegawai::find($this->ms_pegawai_id);
            $this->saldo_tabungan_pegawai     = $pegawai->saldo_tabungan_pegawai();
            $this->total_kredit_tabungan  = $pegawai->total_kredit_tabungan();
            $this->total_debit_tabungan = $pegawai->total_debit_tabungan();

            // Notifikasi sukses
            $this->emit('refreshTabungans');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi kredit berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function simpanDebit()
    {
        DB::beginTransaction();

        try {
            // Pastikan pegawai dipilih
            if (!$this->ms_pegawai_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih pegawai terlebih dahulu.']);
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

            // Periksa saldo pegawai saat ini
            $pegawai = Pegawai::find($this->ms_pegawai_id);
            $saldo_sekarang = $pegawai->saldo_tabungan_pegawai(); // Saldo dihitung secara dinamis

            // Periksa apakah saldo cukup
            if ($this->nominal_debit > $saldo_sekarang) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo tidak mencukupi untuk transaksi ini.']);
                return;
            }

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_tabungan = 22004;
            $deskripsiJurnal = "Penarikan Tunai Tabungan Rp {$this->nominal_debit} pegawai {$this->nama_pegawai}";

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
            TransaksiTabungan::create([
                'user_type' => 'pegawai',
                'user_id' => $this->ms_pegawai_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'penarikan', // Jenis transaksi untuk debit
                'nominal' => $this->nominal_debit,
                'tanggal' => now(),
                'deskripsi' => $this->deskripsi_debit,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
            ]);

            DB::commit();

            // Reset input
            $this->reset(['nominal_debit', 'deskripsi_debit']);

            // Refresh saldo 
            $this->saldo_tabungan_pegawai     = $pegawai->saldo_tabungan_pegawai();
            $this->total_kredit_tabungan  = $pegawai->total_kredit_tabungan();
            $this->total_debit_tabungan = $pegawai->total_debit_tabungan();

            // Notifikasi sukses
            $this->emit('refreshTabungans');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi debit berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-tabungan-pegawai.data-pegawai');
    }
}
