<?php

namespace App\Http\Livewire\SmartCanteen\SettlementTransaksi;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\SettlementSmartCanteen;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TahunAjar;
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
    public $selectedKantin = null;
    public $selectedTahunAjar = null;

    public $namaKantin = '-';
    public $namaTahunAjar = '-';

    // Filter internal
    public $startDate;
    public $endDate;
    public $search = '';

    public function updatedStartDate()
    {
        $this->getDataTransaksi();
    }

    public function updatedEndDate()
    {
        $this->$this->getDataTransaksi();
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
    public function updateParameters($kantin, $tahunAjar)
    {
        $this->selectedKantin = $kantin;
        $this->selectedTahunAjar = $tahunAjar;

        $j = Kantin::find($kantin);
        $t = TahunAjar::find($tahunAjar);

        $this->namaKantin = $j ? $j->nama_kantin : 'Tidak Diketahui';
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

            ->where('ms_kantin_id', $this->selectedKantin)

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
        $query = $this->queryTransaksiSettlement();

        $this->totalSettlement = (clone $query)
            ->sum('total_transaksi');

        return $query
            ->orderBy('tanggal_transaksi', 'DESC')
            ->paginate(100);
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

            $rekeningKasPusat     = 11001;
            $rekeningBankPusat    = 11002;
            $rekeningHutangKantin = 21001.01;

            // Kredit = Kas / Bank
            $kreditAkun = $this->metodePembayaran === 'tunai'
                ? $rekeningKasPusat
                : $rekeningBankPusat;

            $petugas = Auth::user()->nama ?? 'Petugas';
            $kantin = Kantin::find($this->selectedKantin)->nama_kantin ?? 'Kantin';

            $deskripsi = "Settlement kantin {$kantin} oleh {$petugas} sebesar Rp" . number_format($this->totalSettlement, 0, ',', '.');

            // Jurnal - Debit
            $jurnalDebit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $rekeningHutangKantin,
                'posisi' => 'debit',
                'nominal' => $this->totalSettlement,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => Auth::id(),
                'ms_tahun_ajaran_id' => $this->selectedTahunAjar,
                'ms_departemen_id' => 'KANTIN',
                'deskripsi' =>   $deskripsi,
            ]);

            // Jurnal - Kredit (mengurangi hutang)
            $jurnalKredit = AkuntansiJurnalDetail::create([
                'kode_rekening' => $kreditAkun,
                'posisi' => 'kredit',
                'nominal' => $this->totalSettlement,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => Auth::id(),
                'ms_tahun_ajaran_id' => $this->selectedTahunAjar,
                'ms_departemen_id' => 'KANTIN',
                'deskripsi' =>   $deskripsi,
            ]);

            // 4. Insert ke ms_settlement_kantin (header)
            $settlement = SettlementSmartCanteen::create([
                'tanggal_settlement' => now(),
                'total_settlement' => $this->totalSettlement,
                'metode_pembayaran' => $this->metodePembayaran,
                'deskripsi' =>  $deskripsi,
                'ms_pengguna_id' => Auth::id(),            // yg memproses
                'ms_kantin_id' => $this->selectedKantin,
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
            $this->emit('refreshSettlement');
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
            // tampil setelah pilih
            'dataTransaksi' => $this->getDataTransaksi(),
        ]);
    }
}
