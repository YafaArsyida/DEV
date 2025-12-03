<?php

namespace App\Http\Livewire\SmartCanteen\SettlementTransaksi;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\SettlementSmartCanteen;
use App\Models\TahunAjar;
use App\Models\TransaksiSmartCanteen;
use App\Models\User;
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
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '-';
    public $namaTahunAjar = '-';

    // Filter internal
    public $selectedKantin = '';

    public $startDate;
    public $endDate;
    public $search = '';

    public function updatedSelectedKantin()
    {
        $this->hitungTotalSettlement();
    }

    public function updatedStartDate()
    {
        $this->hitungTotalSettlement();
    }

    public function updatedEndDate()
    {
        $this->hitungTotalSettlement();
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
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $j = Jenjang::find($jenjang);
        $t = TahunAjar::find($tahunAjar);

        $this->namaJenjang = $j ? $j->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $t ? $t->nama_tahun_ajar : 'Tidak Diketahui';

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
            'ms_penempatan_siswa',
            'ms_pegawai'
        ])
            ->where('status_settlement', 'belum')

            // Filter kantin
            ->when(
                $this->selectedKantin,
                fn($q) =>
                $q->where('ms_pengguna_id', $this->selectedKantin)
            )

            // Filter jenjang
            ->when($this->selectedJenjang, function ($q) {
                $jenjang = $this->selectedJenjang;

                $q->where(function ($sub) use ($jenjang) {
                    $sub->orWhereHas(
                        'ms_penempatan_siswa',
                        fn($q2) =>
                        $q2->where('ms_jenjang_id', $jenjang)
                    );

                    $sub->orWhereHas(
                        'ms_pegawai',
                        fn($q3) =>
                        $q3->where('ms_jenjang_id', $jenjang)
                    );
                });
            })

            // Search
            ->when($this->search, function ($q) {
                $keyword = "%{$this->search}%";

                $q->where(function ($sub) use ($keyword) {
                    $sub->where('deskripsi', 'like', $keyword)
                        ->orWhereHas(
                            'ms_siswa',
                            fn($q2) =>
                            $q2->where('nama_siswa', 'like', $keyword)
                        )
                        ->orWhereHas(
                            'ms_pegawai',
                            fn($q3) =>
                            $q3->where('nama_pegawai', 'like', $keyword)
                        );
                });
            })

            // Date filter
            ->when(
                $this->startDate,
                fn($q) =>
                $q->whereDate('tanggal_transaksi', '>=', $this->startDate)
            )
            ->when(
                $this->endDate,
                fn($q) =>
                $q->whereDate('tanggal_transaksi', '<=', $this->endDate)
            );
    }

    public function getDataTransaksi()
    {
        return $this->queryTransaksiSettlement()
            ->orderBy('tanggal_transaksi', 'DESC')
            ->paginate(100);
    }

    public function hitungTotalSettlement()
    {
        $this->totalSettlement = $this->queryTransaksiSettlement()
            ->sum('total_transaksi');
    }

    // ==========================
    // PROSES SETTLEMENT
    // ==========================
    public function prosesSettlement()
    {
        // 1. Ambil transaksi dari query builder utama
        $transaksi = $this->queryTransaksiSettlement()->get();

        // 2. Hitung total
        $total = $transaksi->sum('total_transaksi');

        // 3. Jika tidak ada transaksi → gagalkan
        if ($total <= 0 || $transaksi->count() == 0) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Tidak ada transaksi yang bisa disettle'
            ]);
            return;
        }

        DB::beginTransaction();
        try {

            // ==========================
            // 1. Buat Jurnal Settlement
            // ==========================

            $kode_rekening_kas_tunai    = 11001;
            $kode_rekening_kas_transfer = 11002;
            $kode_rekening_hutang_kantin = 21001.01;

            // Debit = Kas / Bank
            $kreditAkun = $this->metodePembayaran === 'tunai'
                ? $kode_rekening_kas_tunai
                : $kode_rekening_kas_transfer;

            $petugas = Auth::user()->nama ?? 'Petugas';
            $kantin = User::find($this->selectedKantin)->nama ?? 'Kantin';

            $deskripsi = "Settlement kantin {$kantin} oleh {$petugas} sebesar Rp" . number_format($this->totalSettlement, 0, ',', '.');

            // Jurnal - Debit
            $jurnalDebit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $kode_rekening_hutang_kantin,
                'posisi' => 'debit',
                'nominal' => $this->totalSettlement,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => Auth::id(),
                'ms_tahun_ajaran_id' => $this->selectedTahunAjar,
                'ms_jenjang_id' => $this->selectedJenjang,
                'deskripsi' =>   $deskripsi,
                'is_canceled' => 'active'
            ]);

            // Jurnal - Kredit (mengurangi hutang)
            $jurnalKredit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $kreditAkun,
                'posisi' => 'kredit',
                'nominal' => $this->totalSettlement,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => Auth::id(),
                'ms_tahun_ajaran_id' => $this->selectedTahunAjar,
                'ms_jenjang_id' => $this->selectedJenjang,
                'deskripsi' =>   $deskripsi,
                'is_canceled' => 'active'
            ]);

            // 4. Insert ke ms_settlement_kantin (header)
            $settlement = SettlementSmartCanteen::create([
                'tanggal_settlement' => now(),
                'total_settlement' => $this->totalSettlement,
                'metode_pembayaran' => $this->metodePembayaran,
                'deskripsi' =>  $deskripsi,
                'ms_pengguna_id' => Auth::id(),            // yg memproses
                'ms_pengguna_kantin_id' => $this->selectedKantin, // kantin yg menerima
                'akun_jurnal_debit_id' => $jurnalDebit->akuntansi_jurnal_detail_id,
                'akun_jurnal_kredit_id' => $jurnalKredit->akuntansi_jurnal_detail_id,
            ]);


            foreach ($transaksi as $t) {
                $t->update([
                    'status_settlement' => 'sudah',
                    'ms_settlement_kantin_id' => $settlement->ms_settlement_kantin_id
                ]);
            }

            DB::commit();

            // 6. Feedback sukses
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Settlement berhasil diproses'
            ]);

            // Refresh list
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalSettlement']);
            $this->reset(['startDate', 'endDate', 'search']);
            $this->hitungTotalSettlement();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal settlement: ' . $e->getMessage()
            ]);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalSettlement']);
        }
    }

    public function render()
    {
        return view('livewire.smart-canteen.settlement-transaksi.index', [
            'kantinUsers' => User::where('peran', 'kantin')
                ->whereHas('ms_akses_jenjang', function ($q) {
                    $q->where('ms_jenjang_id', $this->selectedJenjang);
                })
                ->get(),

            // tampil setelah pilih
            // 'dataTransaksi' => $this->selectedKantin ? $this->getDataTransaksi() : collect([]),
            'dataTransaksi' => $this->getDataTransaksi(),
        ]);
    }
}
