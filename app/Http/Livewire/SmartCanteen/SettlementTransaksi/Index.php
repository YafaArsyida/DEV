<?php

namespace App\Http\Livewire\SmartCanteen\SettlementTransaksi;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\SettlementSmartCanteen;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Services\AccountingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $totalSettlement = 0;
    public $metodePembayaran;

    // Parameter dari listener
    public $selectedKantin = null;

    public $namaKantin = '-';

    // Filter internal
    public $startDate;
    public $endDate;
    public $search = '';

    public $selectedJenjang = '';
    public $namaJenjang = '-';

    public function updatedStartDate()
    {
        $this->getDataTransaksi();
    }

    public function updatedEndDate()
    {
        $this->$this->getDataTransaksi();
    }


    public function updatedSelectedJenjang($value)
    {
        if ($value) {
            $jenjang = Jenjang::find($value);

            $this->namaJenjang = $jenjang?->nama_jenjang ?? '-';
        } else {
            $this->namaJenjang = '-';
        }

        $this->resetPage();
    }
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function resetPeriode()
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui']);
        $this->resetPage();
    }

    // ==========================
    // LISTENER PARAMETER UTAMA
    // ==========================
    public function updateParameters($kantin)
    {
        $this->selectedKantin = $kantin;

        $j = Kantin::find($kantin);

        $this->namaKantin = $j ? $j->nama_kantin : 'Tidak Diketahui';

        $this->resetPage();
        // $this->refreshSaldo();
    }

    // ==========================
    // QUERY TRANSAKSI
    // ==========================
    private function queryTransaksiSettlement()
    {
        return TransaksiSmartCanteen::with([
            'ms_pengguna',
            'ms_siswa',
            'ms_jenjang',
            'ms_pegawai'
        ])
            ->where('status_settlement', 'belum')
            ->where('ms_kantin_id', $this->selectedKantin)

            // Filter Jenjang
            ->when(
                $this->selectedJenjang,
                fn ($q) => $q->where('ms_jenjang_id', $this->selectedJenjang)
            )

            // Search
            ->when($this->search, function ($q) {
                $keyword = "%{$this->search}%";

                $q->where(function ($sub) use ($keyword) {
                    $sub->where('deskripsi', 'like', $keyword)
                        ->orWhereHas('ms_siswa', function ($q2) use ($keyword) {
                            $q2->where('nama_siswa', 'like', $keyword);
                        })
                        ->orWhereHas('ms_pegawai', function ($q3) use ($keyword) {
                            $q3->where('nama_pegawai', 'like', $keyword);
                        });
                });
            })

            // Date filter
            ->when(
                $this->startDate,
                fn ($q) => $q->whereDate(
                    'tanggal_transaksi', '>=', $this->startDate
                )
            )

            ->when(
                $this->endDate,
                fn ($q) => $q->whereDate('tanggal_transaksi', '<=', $this->endDate
                )
            );
    }

    private function queryTotalSettlement()
    {
        return TransaksiSmartCanteen::query()
            ->where('status_settlement', 'belum')
            ->where('ms_kantin_id', $this->selectedKantin)
            ->when(
                $this->selectedJenjang, fn ($q) => $q->where(
                    'ms_jenjang_id', $this->selectedJenjang
                )
            );
    }

    public function getDataTransaksi()
    {
        // Saldo settlement = seluruh transaksi outstanding
        $this->totalSettlement = $this->queryTotalSettlement()
            ->sum('total_transaksi');

        // Data tabel = boleh terkena filter
        return $this->queryTransaksiSettlement()
            ->orderBy('tanggal_transaksi', 'DESC')
            ->paginate(50);
    }


    // PROSES SETTLEMENT
    public function prosesSettlement()
    {
        $msPenggunaId = Auth::user()->ms_pengguna_id;

        if (!$this->selectedKantin) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Kantin belum dipilih.'
            ]);
            return;
        }

        if (!$this->selectedJenjang) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jenjang belum dipilih.'
            ]);
            return;
        }

        if (!$this->metodePembayaran) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Metode pembayaran belum dipilih.'
            ]);
            return;
        }

        DB::beginTransaction();

        try {

            // 1. AMBIL TRANSAKSI YANG BELUM DISETTLE + LOCK
            $transaksi = $this->queryTotalSettlement()
                ->lockForUpdate()
                ->get();

            // 2. VALIDASI TRANSAKSI
            if ($transaksi->isEmpty()) {
                throw new \Exception(
                    'Tidak ada transaksi yang bisa disettle.'
                );
            }

            // 3. HITUNG TOTAL BERDASARKAN TRANSAKSI AKTUAL
            $totalSettlement = $transaksi->sum('total_transaksi');

            if ($totalSettlement <= 0) {
                throw new \Exception(
                    'Total settlement tidak valid.'
                );
            }

            // Gunakan total aktual dari database
            $this->totalSettlement = $totalSettlement;

            // 4. NORMALISASI METODE PEMBAYARAN
            $metode = strtolower(trim($this->metodePembayaran));

            // 5. MAPPING REKENING
            $rekeningHutangKantin = 21001.01;

            switch ($metode) {

                case 'tunai':
                    // Uang settlement diserahkan dalam bentuk kas
                    $rekeningKasBank = 11001;
                    $namaMetode = 'Tunai';
                    break;

                case 'transfer':
                    // Uang settlement diserahkan melalui bank/transfer
                    $rekeningKasBank = 11002;
                    $namaMetode = 'Transfer';
                    break;

                default:
                    throw new \Exception(
                        'Metode pembayaran settlement tidak valid.'
                    );
            }

            // 6. INFORMASI KANTIN
            $kantin = Kantin::find($this->selectedKantin);

            if (!$kantin) {
                throw new \Exception(
                    'Data kantin tidak ditemukan.'
                );
            }

            $namaKantin = $kantin->nama_kantin ?? 'Kantin';

            // 7. INFORMASI JENJANG
            $namaJenjang = $this->namaJenjang ?? '-';

            // 8. DESKRIPSI JURNAL
            $deskripsi = sprintf(
                'Settlement kantin %s - %s oleh %s metode %s Rp%s',
                $namaKantin,
                $namaJenjang,
                Auth::user()->nama ?? 'Petugas',
                $namaMetode,
                number_format($totalSettlement, 0, ',', '.')
            );

            // =========================================================
            // 9. BUAT JURNAL SETTLEMENT
            //
            // Debit  : Hutang Kantin
            // Kredit : Kas / Bank Pusat
            //
            // Kantin sebelumnya:
            // Debit  Saldo EduPay
            // Kredit Hutang Kantin
            //
            // Saat settlement:
            // Debit  Hutang Kantin
            // Kredit Kas / Bank
            // =========================================================
            $jurnal = AccountingService::create([
                'tanggal' => now(),
                'deskripsi' => $deskripsi,
                'ms_pengguna_id' => $msPenggunaId,

                // Kantin tidak menggunakan tahun ajaran
                'ms_tahun_ajaran_id' => null,

                // Tetap berdasarkan jenjang transaksi
                'ms_jenjang_id' => $this->selectedJenjang,

                'ms_departemen_id' => 'KANTIN',

                'detail' => [
                    // DEBIT
                    [
                        'kode_rekening' => $rekeningHutangKantin,
                        'posisi' => 'debit',
                        'nominal' => $totalSettlement,
                    ],

                    // KREDIT
                    [
                        'kode_rekening' => $rekeningKasBank,
                        'posisi' => 'kredit',
                        'nominal' => $totalSettlement,
                    ],
                ],
            ]);

            // 10. BUAT HEADER SETTLEMENT
            $settlement = SettlementSmartCanteen::create([
                'ms_pengguna_id' => $msPenggunaId,
                'ms_kantin_id' => $this->selectedKantin,
                'ms_jenjang_id' => $this->selectedJenjang,

                'tanggal_settlement' => now(),
                'total_settlement' => $totalSettlement,
                'metode_pembayaran' => $namaMetode,
                'deskripsi' => $deskripsi,

                // Hubungkan settlement dengan jurnal utama
                'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
            ]);

            // 11. UPDATE SEMUA TRANSAKSI YANG DISETTLE
            foreach ($transaksi as $transaksiKantin) {
                $transaksiKantin->update([
                    'status_settlement' => 'sudah',
                    'ms_settlement_kantin_id' =>
                        $settlement->ms_settlement_kantin_id,
                ]);
            }

            // 12. COMMIT
            DB::commit();

            // 13. FEEDBACK
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Settlement berhasil diproses.'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalSettlement'
            ]);

            // Reset filter tanggal/search
            $this->reset([
                'startDate',
                'endDate',
                'search'
            ]);

            $this->resetPage();

            $this->emit('refreshSettlement');

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal settlement: ' . $e->getMessage()
            ]);

            report($e);
        }
    }

    public function render()
    {
        return view('livewire.smart-canteen.settlement-transaksi.index', [
            'select_jenjang' => Jenjang::orderBy('nama_jenjang')->get(),
            'dataTransaksi' => $this->getDataTransaksi(),
        ]);
    }
}
