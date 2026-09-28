<?php

namespace App\Http\Livewire\Keuangan\AkuntansiLaporanLabaRugi;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $startDate = null;
    public $endDate = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;

        $janjang = Jenjang::find($jenjang);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function updatedStartDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }
    
    public function resetTanggal()
    {
        $this->startDate = Carbon::now()
            ->subMonths(3)
            ->startOfDay()
            ->format('Y-m-d');

        $this->endDate = Carbon::now()
            ->endOfDay()
            ->format('Y-m-d');

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-laba-rugi.pdf', [
            'jenjang' => $this->selectedJenjang,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function mount()
    {
        $this->startDate = Carbon::now()
            ->subMonths(3)
            ->startOfDay()
            ->format('Y-m-d');

        $this->endDate = Carbon::now()
            ->endOfDay()
            ->format('Y-m-d');
    }

    public function render()
    {
        // ==========================================
        // QUERY JURNAL
        // ==========================================
        $jurnals = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
        ])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')
            ->when(
                $this->startDate && $this->endDate,
                function ($query) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();

                    $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

                    $query->whereBetween(
                        'tanggal_transaksi', [$startDate, $endDate]
                    );
                }
            )
            ->get();


        // ==========================================
        // DETAIL PENDAPATAN & BEBAN
        // ==========================================
        $pendapatanDetails = collect();
        $bebanDetails = collect();

        foreach ($jurnals as $jurnal) {

            foreach ($jurnal->akuntansi_jurnal_detail as $detail) {

                // Simpan tanggal jurnal ke detail
                $detail->tanggal_transaksi = $jurnal->tanggal_transaksi;

                // ==========================================
                // PENDAPATAN (4xxx)
                // ==========================================
                if (str_starts_with($detail->kode_rekening, '4')) {

                    // Kredit = pendapatan bertambah
                    // Debit  = pendapatan berkurang / reversal
                    $detail->nominal_laporan =
                        $detail->posisi === 'kredit'
                            ? $detail->nominal
                            : - $detail->nominal;

                    $pendapatanDetails->push($detail);
                }

                // ==========================================
                // BEBAN (5xxx)
                // ==========================================
                if (str_starts_with($detail->kode_rekening, '5')) {

                    // Debit  = beban bertambah
                    // Kredit = beban berkurang / reversal
                    $detail->nominal_laporan =
                        $detail->posisi === 'debit'
                            ? $detail->nominal
                            : - $detail->nominal;

                    $bebanDetails->push($detail);
                }
            }
        }


        // ==========================================
        // GROUP PENDAPATAN PER REKENING & BULAN
        // ==========================================
        $pendapatanPerBulan = $pendapatanDetails
            ->groupBy([
                fn ($item) => $item->akuntansi_rekening->nama_rekening,

                fn ($item) => Carbon::parse($item->tanggal_transaksi)
                    ->format('Y-m'),
            ]);


        // ==========================================
        // GROUP BEBAN PER REKENING & BULAN
        // ==========================================
        $bebanPerBulan = $bebanDetails->groupBy([
                fn ($item) => $item->akuntansi_rekening->nama_rekening,
                fn ($item) => Carbon::parse($item->tanggal_transaksi)->format('Y-m'),
            ]);


        // ==========================================
        // HEADER BULAN
        // ==========================================
        $bulanHeaders = $pendapatanPerBulan
            ->keys()
            ->merge($bebanPerBulan->keys())
            ->unique()
            ->sort()
            ->values();

        /*
        * Karena struktur groupBy:
        *
        * rekening
        *    └── bulan
        *
        * maka kita ambil seluruh key bulan
        * dari masing-masing rekening.
        */
        $bulanHeaders = $pendapatanPerBulan
            ->merge($bebanPerBulan)
            ->flatMap(function ($dataPerBulan) {
                return $dataPerBulan->keys();
            })
            ->unique()
            ->sort()
            ->values();


        // ==========================================
        // FORMAT BULAN INDONESIA
        // ==========================================
        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {

            return [
                $bulan => HelperController::formatTanggalIndonesia(
                    $bulan . '-01',
                    'F Y'
                ),
            ];
        });


        // ==========================================
        // TOTAL PENDAPATAN PER REKENING
        // ==========================================
        $totalPendapatanRekening = [];

        foreach ($pendapatanPerBulan as $namaRekening => $dataPerBulan) {

            $totalPendapatanRekening[$namaRekening] =
                $dataPerBulan->sum(function ($details) {
                    return $details->sum('nominal_laporan');
                });
        }


        // ==========================================
        // TOTAL BEBAN PER REKENING
        // ==========================================
        $totalBebanRekening = [];

        foreach ($bebanPerBulan as $namaRekening => $dataPerBulan) {

            $totalBebanRekening[$namaRekening] =
                $dataPerBulan->sum(function ($details) {
                    return $details->sum('nominal_laporan');
                });
        }


        // ==========================================
        // TOTAL PENDAPATAN PER BULAN
        // ==========================================
        $totalPendapatanPerBulan = [];

        foreach ($bulanHeaders as $bulan) {

            $totalPendapatanPerBulan[$bulan] =
                $pendapatanPerBulan->sum(function ($dataPerBulan) use ($bulan) {

                    return optional(
                        $dataPerBulan[$bulan] ?? null
                    )->sum('nominal_laporan');
                });
        }


        // ==========================================
        // TOTAL BEBAN PER BULAN
        // ==========================================
        $totalBebanPerBulan = [];

        foreach ($bulanHeaders as $bulan) {

            $totalBebanPerBulan[$bulan] =
                $bebanPerBulan->sum(function ($dataPerBulan) use ($bulan) {

                    return optional(
                        $dataPerBulan[$bulan] ?? null
                    )->sum('nominal_laporan');
                });
        }


        // ==========================================
        // LABA / RUGI PER BULAN
        // ==========================================
        $labaRugiPerBulan = [];

        foreach ($bulanHeaders as $bulan) {

            $pendapatan = $totalPendapatanPerBulan[$bulan] ?? 0;

            $beban = $totalBebanPerBulan[$bulan] ?? 0;

            $labaRugiPerBulan[$bulan] = $pendapatan - $beban;
        }


        // ==========================================
        // GRAND TOTAL
        // ==========================================
        $totalPendapatan = array_sum($totalPendapatanPerBulan);

        $totalBeban = array_sum($totalBebanPerBulan);

        $totalLabaRugi =  array_sum($labaRugiPerBulan);


        // ==========================================
        // VIEW
        // ==========================================
        return view('livewire.keuangan.akuntansi-laporan-laba-rugi.index', [
                // DATA UTAMA
                'pendapatanPerBulan' => $pendapatanPerBulan,
                'bebanPerBulan' => $bebanPerBulan,
                'bulanIndo' => $bulanIndo,

                // TOTAL REKENING
                'totalPendapatanRekening' => $totalPendapatanRekening,

                'totalBebanRekening' => $totalBebanRekening,

                // TOTAL BULANAN
                'totalPendapatanPerBulan' => $totalPendapatanPerBulan,

                'totalBebanPerBulan' => $totalBebanPerBulan,

                // LABA / RUGI
                'labaRugiPerBulan' => $labaRugiPerBulan,

                // GRAND TOTAL
                'totalPendapatan' => $totalPendapatan,

                'totalBeban' => $totalBeban,

                'totalLabaRugi' => $totalLabaRugi,
            ]
        );
    }
}
