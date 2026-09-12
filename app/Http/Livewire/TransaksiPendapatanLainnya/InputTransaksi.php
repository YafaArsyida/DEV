<?php

namespace App\Http\Livewire\TransaksiPendapatanLainnya;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use App\Models\PendapatanLainnya;
use App\Models\TransaksiPendapatanLainnya;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InputTransaksi extends Component
{
    public $selectedJenjang = null;

    public $nominal = 0;
    public $kode_rekening;
    public $metode_pembayaran = 'tunai';
    public $deskripsi;

    public $select_transaksi = [];
    public $totalPendapatanLainnya = 0;

    protected $listeners = [
        'parameterUpdated',
        'refreshTransaksi' => 'loadData'
    ];

    public function parameterUpdated($jenjang)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;

        $this->loadData();
    }

    public function mount()
    {
        // SELECT OPTION (cache candidate)
        $this->select_transaksi = AkuntansiRekening::where('akuntansi_kelompok_rekening_id', 4)
            ->where('tipe_akun', 'Pendapatan Lainnya')
            ->orderBy('kode_rekening', 'ASC')
            ->get();
    }

    public function loadData()
    {
        $this->totalPendapatanLainnya = TransaksiPendapatanLainnya::query()
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('status_transaksi', '!=', 'dibatalkan')
            ->sum('nominal');
    }

    public function simpanTransaksi()
    {
        DB::beginTransaction();

        try {
            $this->nominal = $this->normalizeAmount($this->nominal);

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

            // =====================================================
            // REKENING
            // =====================================================
            $kodeRekeningKas = 11001;
            $kodeRekeningBank = 11002;

            $kodeRekeningDebit = $this->metode_pembayaran === 'bank'
                ? $kodeRekeningBank
                : $kodeRekeningKas;

            // =====================================================
            // NAMA TRANSAKSI
            // =====================================================
            $namaTransaksi = AkuntansiRekening::where(
                'kode_rekening',
                $this->kode_rekening
            )->value('nama_rekening');

            // Deskripsi transaksi
            $deskripsi = trim(
                "{$namaTransaksi} Rp {$this->nominal} {$this->deskripsi}"
            );

            // =====================================================
            // BUAT JURNAL
            // =====================================================
            $jurnal = AccountingService::create([
                'tanggal' => now(),
                'deskripsi' => $deskripsi,
                'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
                'ms_tahun_ajaran_id' => NULL,
                'ms_jenjang_id' => $this->selectedJenjang,

                'ms_departemen_id' => 'SEKOLAH',

                'detail' => [
                    // Debit Kas / Bank
                    [
                        'kode_rekening' => $kodeRekeningDebit,
                        'posisi' => 'debit',
                        'nominal' => $this->nominal,
                    ],

                    // Kredit Pendapatan
                    [
                        'kode_rekening' => $this->kode_rekening,
                        'posisi' => 'kredit',
                        'nominal' => $this->nominal,
                    ],

                ],
            ]);

            // =====================================================
            // SIMPAN TRANSAKSI PENDAPATAN
            // =====================================================
            TransaksiPendapatanLainnya::create([
                'ms_pengguna_id' => auth()->user()->ms_pengguna_id,

                'ms_jenjang_id' => $this->selectedJenjang,

                'kode_rekening' => $this->kode_rekening,

                'nominal' => $this->nominal,

                'metode_pembayaran' => $this->metode_pembayaran,

                'tanggal' => now(),

                'deskripsi' => $deskripsi,

                'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
            ]);

            // =====================================================
            // COMMIT
            // =====================================================
            DB::commit();

            // =====================================================
            // RESET & FEEDBACK
            // =====================================================
            $this->reset(['deskripsi']);
            $this->nominal = 0;

            $this->loadData();

            $this->emit('refreshTransaksi');

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage()
                    ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-pendapatan-lainnya.input-transaksi');
    }

    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
