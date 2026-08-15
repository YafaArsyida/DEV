<?php

namespace App\Http\Livewire\AkuntansiLaporanArusKas;

use App\Models\AkuntansiJurnalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $perPage = 50;

    public $selectedJenjang = null;

    public $selectedBulan = null;
   
    public $startDate = null;
    public $endDate = null;

    public $selectedRekening = null;

    public $search = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

     public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedRekening = null;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedRekening()
    {
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->resetPage();

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->resetPage();
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-arus-kas.pdf', [
            'jenjang' => $this->selectedJenjang,
            'rekening' => $this->selectedRekening,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $akunKasBank = ['11001', '11002'];

        $transaksiJurnal = AkuntansiJurnalDetail::with([
            'akuntansi_rekening',
            'akuntansi_jurnal.ms_pengguna',
        ])
            ->whereIn(
                'kode_rekening',
                $this->selectedRekening
                    ? [$this->selectedRekening]
                    : $akunKasBank
            )
            ->whereHas('akuntansi_jurnal', function ($query) {

                $query->where('ms_jenjang_id', $this->selectedJenjang)
                    ->where('ms_departemen_id', 'SEKOLAH');

                // FILTER PERIODE
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('tanggal_transaksi', [
                        Carbon::parse($this->startDate)->startOfDay(),
                        Carbon::parse($this->endDate)->endOfDay(),
                    ]);
                }

                // PENCARIAN
                if ($this->search) {
                    $query->where(function ($query) {
                        $query->where(
                            'deskripsi',
                            'like',
                            '%' . $this->search . '%'
                        )
                        ->orWhere(
                            'nomor_jurnal',
                            'like',
                            '%' . $this->search . '%'
                        );
                    });
                }
            })
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->select('akuntansi_jurnal_detail.*')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KAS MASUK & KAS KELUAR
        |--------------------------------------------------------------------------
        */

        $totalKasMasuk = $transaksiJurnal
            ->where('posisi', 'debit')
            ->sum('nominal');

        $totalKasKeluar = $transaksiJurnal
            ->where('posisi', 'kredit')
            ->sum('nominal');


        /*
        |--------------------------------------------------------------------------
        | SALDO AWAL
        |--------------------------------------------------------------------------
        */

        $saldoAwal = 0;

        if ($this->startDate) {

            $saldoAwal = AkuntansiJurnalDetail::query()
                ->whereIn(
                    'kode_rekening',
                    $this->selectedRekening
                        ? [$this->selectedRekening]
                        : $akunKasBank
                )
                ->whereHas('akuntansi_jurnal', function ($query) {

                    $query->where(
                        'ms_jenjang_id',
                        $this->selectedJenjang
                    )
                    ->where(
                        'ms_departemen_id',
                        'SEKOLAH'
                    )
                    ->where(
                        'tanggal_transaksi',
                        '<',
                        Carbon::parse($this->startDate)->startOfDay()
                    );
                })
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN posisi = 'debit'
                            THEN nominal
                            ELSE 0
                        END
                    )
                    -
                    SUM(
                        CASE
                            WHEN posisi = 'kredit'
                            THEN nominal
                            ELSE 0
                        END
                    ) AS saldo
                ")
                ->value('saldo') ?? 0;
        }


        /*
        |--------------------------------------------------------------------------
        | SALDO AKHIR
        |--------------------------------------------------------------------------
        */

        $saldoAkhir = $saldoAwal
            + $totalKasMasuk
            - $totalKasKeluar;


        return view('livewire.akuntansi-laporan-arus-kas.index',
            [
                'transaksiJurnal' => $transaksiJurnal,
                'saldoAwal'       => $saldoAwal,
                'saldoAkhir'      => $saldoAkhir,
                'totalKasMasuk'   => $totalKasMasuk,
                'totalKasKeluar'  => $totalKasKeluar,
            ]
        );
    }
}
