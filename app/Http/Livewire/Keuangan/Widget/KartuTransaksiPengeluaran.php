<?php

namespace App\Http\Livewire\Keuangan\Widget;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use App\Models\TransaksiPengeluaran;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class KartuTransaksiPengeluaran extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $nominal, $kode_rekening, $metode_pembayaran = 'tunai', $deskripsi;

    public $select_transaksi = [];
    public $totalPengeluaran;

    protected $listeners = [
        'parameterUpdated',
    ];

    public function parameterUpdated($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

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
        $this->totalPengeluaran = TransaksiPengeluaran::query()
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
            $namaTransaksi = AkuntansiRekening::where(
                'kode_rekening',
                $this->kode_rekening
            )->value('nama_rekening');

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

                'ms_tahun_ajaran_id' => null,

                'ms_jenjang_id' => $this->selectedJenjang,

                'ms_departemen_id' => 'SEKOLAH',

                'detail' => [
                    // Debit Pengeluaran
                    [
                        'kode_rekening' => $this->kode_rekening,
                        'posisi' => 'debit',
                        'nominal' => $this->nominal,
                    ],

                    // Kredit Kas / Bank
                    [
                        'kode_rekening' => $kodeRekeningKredit,
                        'posisi' => 'kredit',
                        'nominal' => $this->nominal,
                    ],
                ],
            ]);

            // =====================================================
            // SIMPAN TRANSAKSI PENGELUARAN
            // =====================================================
            TransaksiPengeluaran::create([
                'ms_pengguna_id' => auth()->user()->ms_pengguna_id,

                'ms_jenjang_id' => $this->selectedJenjang,

                'kode_rekening' => $this->kode_rekening,

                'nominal' => $this->nominal,

                'metode_pembayaran' => $this->metode_pembayaran,

                'tanggal' => now(),

                'deskripsi' => $deskripsi,

                'akuntansi_jurnal_id' =>
                    $jurnal->akuntansi_jurnal_id,
            ]);

            // =====================================================
            // COMMIT
            // =====================================================
            DB::commit();

            // ======================
            // RESET & FEEDBACK
            // ======================
            $this->reset(['deskripsi']);
            $this->nominal = 0;
            $this->loadData();

            $this->emit('refreshJurnalHariIni');
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
        return view('livewire.keuangan.widget.kartu-transaksi-pengeluaran');
    }
     private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
