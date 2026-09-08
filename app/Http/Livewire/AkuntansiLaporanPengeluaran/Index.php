<?php

namespace App\Http\Livewire\AkuntansiLaporanPengeluaran;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    // public $selectedTahunAjar = null;
    public $selectedBulan = null;
    public $startDate = null;
    public $endDate = null;

    public $search = '';

    public $namaJenjang = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');
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
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        // $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function updateBulan($bulan)
    {
        $this->selectedBulan = $bulan;
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-pengeluaran.pdf', [
            'jenjang' => $this->selectedJenjang,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $bebanPerBulan = AkuntansiJurnal::with([
            'akuntansi_jurnal_detail.akuntansi_rekening',
        ])

            // ==========================================
            // FILTER HEADER JURNAL
            // ==========================================
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')

            // ==========================================
            // FILTER TANGGAL
            // ==========================================
            ->when(
                $this->startDate && $this->endDate,
                function ($query) {
                    $query->whereBetween('tanggal_transaksi', [
                        $this->startDate . ' 00:00:00',
                        $this->endDate . ' 23:59:59',
                    ]);
                }
            )

            // ==========================================
            // HANYA JURNAL YANG MEMILIKI REKENING BEBAN
            // ==========================================
            ->whereHas(
                'akuntansi_jurnal_detail',
                function ($query) {
                    $query->whereHas(
                        'akuntansi_rekening',
                        function ($query) {
                            $query->where(
                                'kode_rekening',
                                'like',
                                '5%'
                            );
                        }
                    );
                }
            )

            ->get()

            // ==========================================
            // FLATTEN DETAIL JURNAL
            // ==========================================
            ->flatMap(function ($jurnal) {

                return $jurnal->akuntansi_jurnal_detail

                    ->filter(function ($detail) {

                        return str_starts_with(
                            (string) $detail
                                ->akuntansi_rekening
                                ->kode_rekening,
                            '5'
                        );
                    })

                    ->map(function ($detail) use ($jurnal) {

                        // Beban:
                        // DEBIT  = menambah beban (+)
                        // KREDIT = mengurangi beban (-)
                        $nominal = strtolower($detail->posisi) === 'debit'
                            ? $detail->nominal
                            : -$detail->nominal;

                        return [
                            'nama_rekening' =>
                                $detail->akuntansi_rekening->nama_rekening,

                            'tanggal_transaksi' =>
                                $jurnal->tanggal_transaksi,

                            'nominal' =>
                                $nominal,
                        ];
                    });
            })

            // ==========================================
            // GROUP REKENING → BULAN
            // ==========================================
            ->groupBy([
                'nama_rekening',
                function ($item) {
                    return Carbon::parse(
                        $item['tanggal_transaksi']
                    )->format('Y-m');
                },
            ]);

        // ==========================================
        // AMBIL BULAN
        // ==========================================
        $bulanHeaders = collect($bebanPerBulan)
            ->flatMap(function ($item) {
                return collect($item)->keys()->all();
            })
            ->unique()
            ->sort()
            ->values();

        // ==========================================
        // FORMAT BULAN INDONESIA
        // ==========================================
        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {

            return [
                $bulan =>
                    HelperController::formatTanggalIndonesia(
                        $bulan . '-01',
                        'F Y'
                    ),
            ];
        });

        return view('livewire.akuntansi-laporan-pengeluaran.index',
            [
                'bebanPerBulan' => $bebanPerBulan,
                'bulanHeaders' => $bulanHeaders,
                'bulanIndo' => $bulanIndo,
            ]
        );
    }
}
