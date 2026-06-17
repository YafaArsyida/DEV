<?php

namespace App\Http\Livewire\TransaksiPengeluaran;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InputTransaksi extends Component
{
    public $selectedJenjang = null;

    public $nominal, $kode_rekening, $metode_pembayaran = 'tunai', $deskripsi;

    public $select_transaksi = [];
    public $totalPengeluaran;

    protected $listeners = [
        'parameterUpdated',
    ];

    public function parameterUpdated($jenjang)
    {
        // Update nilai selectedJenjang
        $this->selectedJenjang = $jenjang;

        $this->loadData();
    }

    public function mount()
    {
        // SELECT OPTION (cache candidate)
        $this->select_transaksi = AkuntansiRekening::where('akuntansi_kelompok_rekening_id', 5)
            ->where('tipe_akun', 'Beban Operasional')
            ->orderBy('kode_rekening', 'ASC')
            ->get();
    }

    public function loadData()
    {
        $this->totalPengeluaran = Pengeluaran::query()
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->sum('nominal');
    }

    public function simpanTransaksi()
    {
        DB::beginTransaction();

        try {
            $this->validate([
                'kode_rekening' => 'required',
                'nominal' => 'required|numeric|min:1000',
                'deskripsi' => 'nullable|string|max:255',
                'metode_pembayaran' => 'required',
            ], [
                'kode_rekening.required' => 'Jenis Transaksi harus dipilih.',
                'nominal.required' => 'Nominal harus diisi.',
                'nominal.numeric' => 'Nominal harus berupa angka.',
                'nominal.min' => 'Nominal harus minimal 1000.',
                'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
                'metode_pembayaran.required' => 'Metode Pembayaran harus dipilih.',
            ]);

            // ======================
            // SET REKENING
            // ======================
            $kodeRekeningKas  = 11001;
            $kodeRekeningBank = 11002;

            $kodeRekeningKredit = $this->metode_pembayaran === 'bank'
                ? $kodeRekeningBank
                : $kodeRekeningKas;

            // ======================
            // AMBIL NAMA TRANSAKSI
            // ======================
            $namaTransaksi = AkuntansiRekening::where('kode_rekening', $this->kode_rekening)
                ->value('nama_rekening');

            $deskripsi = trim("{$namaTransaksi} Rp {$this->nominal} {$this->deskripsi}");

            // ======================
            // COMMON DATA
            // ======================
            $baseData = [
                'nominal' => $this->nominal,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_jenjang_id' => $this->selectedJenjang,
                'ms_departemen_id' => 'SEKOLAH',
                'deskripsi' => $deskripsi,
            ];

            // ======================
            // JURNAL DEBIT
            // ======================
            $jurnalDebit = AkuntansiJurnalDetail::create(array_merge($baseData, [
                'kode_rekening' => $this->kode_rekening,
                'posisi' => 'debit',
            ]));

            // ======================
            // JURNAL KREDIT
            // ======================
            $jurnalKredit = AkuntansiJurnalDetail::create(array_merge($baseData, [
                'kode_rekening' => $kodeRekeningKredit,
                'posisi' => 'kredit',
            ]));

            // ======================
            // SIMPAN TRANSAKSI
            // ======================
            Pengeluaran::create([
                'ms_pengguna_id' => auth()->id(),
                'ms_jenjang_id' => $this->selectedJenjang,
                'kode_rekening' => $this->kode_rekening,
                'nominal' => $this->nominal,
                'metode_pembayaran' => $this->metode_pembayaran,
                'tanggal' => now(),
                'deskripsi' => $deskripsi,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebit->akuntansi_jurnal_detail_id,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKredit->akuntansi_jurnal_detail_id,
            ]);

            // ======================
            // COMMIT
            // ======================
            DB::commit();

            // ======================
            // RESET & FEEDBACK
            // ======================
            $this->reset(['nominal', 'deskripsi']);
            $this->loadData();

            $this->emit('refreshTransaksiPengeluaran');
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi berhasil disimpan.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-pengeluaran.input-transaksi');
    }
}
