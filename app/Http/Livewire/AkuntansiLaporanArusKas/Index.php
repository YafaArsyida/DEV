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
        $namaRekening = 'Kas & Bank';

        if ($this->selectedRekening === '11001') {
            $namaRekening = 'Kas Besar';
        } elseif ($this->selectedRekening === '11002') {
            $namaRekening = 'Bank Sekolah';
        }

        $akunKasBank = ['11001', '11002'];

        /*
        |--------------------------------------------------------------------------
        | FILTER REKENING KAS / BANK
        |--------------------------------------------------------------------------
        */
        $kodeKasBank = $this->selectedRekening
            ? [$this->selectedRekening]
            : $akunKasBank;


        /*
        |--------------------------------------------------------------------------
        | BASE QUERY TRANSAKSI
        |--------------------------------------------------------------------------
        */
        $baseQuery = AkuntansiJurnalDetail::query()
            ->with([
                'akuntansi_rekening',
                'akuntansi_jurnal.ms_pengguna',
            ])
            ->whereIn('akuntansi_jurnal_detail.kode_rekening', $kodeKasBank)
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
            ->where('akuntansi_jurnal.ms_jenjang_id', $this->selectedJenjang)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH');


        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE
        |--------------------------------------------------------------------------
        */
        if ($this->startDate && $this->endDate) {
            $baseQuery->whereBetween(
                'akuntansi_jurnal.tanggal_transaksi',
                [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(),
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | QUERY TRANSAKSI UNTUK TABEL
        |--------------------------------------------------------------------------
        |
        | Search hanya digunakan untuk menyaring transaksi yang ditampilkan.
        |
        */
        $transaksiQuery = clone $baseQuery;

        if ($this->search) {
            $transaksiQuery->where(function ($query) {
                $query->where(
                    'akuntansi_jurnal.deskripsi',
                    'like',
                    '%' . $this->search . '%'
                )
                ->orWhere(
                    'akuntansi_jurnal.nomor_jurnal',
                    'like',
                    '%' . $this->search . '%'
                );
            });
        }

        $transaksiJurnal = $transaksiQuery
            ->orderBy('akuntansi_jurnal.tanggal_transaksi')
            ->orderBy('akuntansi_jurnal.akuntansi_jurnal_id')
            ->orderBy('akuntansi_jurnal_detail.akuntansi_jurnal_detail_id')
            ->select('akuntansi_jurnal_detail.*')
            ->paginate($this->perPage);


        /*
        |--------------------------------------------------------------------------
        | TOTAL KAS MASUK & KAS KELUAR
        |--------------------------------------------------------------------------
        |
        | Tidak menggunakan pagination.
        | Menghitung seluruh transaksi pada periode/filter.
        |
        */
        $summaryQuery = clone $baseQuery;

        $summary = $summaryQuery
            ->selectRaw("
                SUM(
                    CASE
                        WHEN akuntansi_jurnal_detail.posisi = 'debit'
                        THEN akuntansi_jurnal_detail.nominal
                        ELSE 0
                    END
                ) AS total_kas_masuk,

                SUM(
                    CASE
                        WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                        THEN akuntansi_jurnal_detail.nominal
                        ELSE 0
                    END
                ) AS total_kas_keluar
            ")
            ->first();

        $totalKasMasuk = $summary->total_kas_masuk ?? 0;
        $totalKasKeluar = $summary->total_kas_keluar ?? 0;


        /*
        |--------------------------------------------------------------------------
        | SALDO AWAL
        |--------------------------------------------------------------------------
        |
        | Saldo seluruh Kas/Bank sebelum tanggal mulai laporan.
        |
        */
        $saldoAwal = 0;

        if ($this->startDate) {

            $saldoAwal = AkuntansiJurnalDetail::query()
                ->whereIn('kode_rekening', $kodeKasBank)
                ->whereHas('akuntansi_jurnal', function ($query) {
                    $query->where(
                        'ms_jenjang_id',
                        $this->selectedJenjang
                    )
                    ->where(
                        'ms_departemen_id', 'SEKOLAH'
                    )
                    ->where(
                        'tanggal_transaksi',
                        '<',
                        Carbon::parse($this->startDate)->startOfDay()
                    );
                })
                ->selectRaw("
                    COALESCE(
                        SUM(
                            CASE
                                WHEN posisi = 'debit'
                                THEN nominal
                                ELSE 0
                            END
                        ),
                        0
                    )
                    -
                    COALESCE(
                        SUM(
                            CASE
                                WHEN posisi = 'kredit'
                                THEN nominal
                                ELSE 0
                            END
                        ),
                        0
                    ) AS saldo
                ")
                ->value('saldo') ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | SALDO AKHIR
        |--------------------------------------------------------------------------
        */
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;

        return view('livewire.akuntansi-laporan-arus-kas.index',
            [
                'transaksiJurnal' => $transaksiJurnal,
                'saldoAwal'       => $saldoAwal,
                'saldoAkhir'      => $saldoAkhir,
                'totalKasMasuk'   => $totalKasMasuk,
                'totalKasKeluar'  => $totalKasKeluar,
                'namaRekening' => $namaRekening,
            ]
        );
    }
}
