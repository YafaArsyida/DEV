<?php

namespace App\Http\Livewire\Keuangan\AkuntansiLaporanPendapatan;

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
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-pendapatan.pdf', [
            'jenjang' => $this->selectedJenjang,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $pendapatanPerBulan = AkuntansiJurnal::with([
                'akuntansi_jurnal_detail.akuntansi_rekening',
            ])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_departemen_id', 'SEKOLAH')

            // FILTER TANGGAL
            ->when(
                $this->startDate && $this->endDate,
                fn ($q) => $q->whereBetween('tanggal_transaksi', [
                    $this->startDate . ' 00:00:00',
                    $this->endDate . ' 23:59:59',
                ])
            )

            // HANYA JURNAL YANG MEMILIKI REKENING PENDAPATAN
            ->whereHas('akuntansi_jurnal_detail', function ($query) {
                $query->whereHas('akuntansi_rekening', function ($query) {
                    $query->where('kode_rekening', 'like', '4%');
                });
            })

            ->get()

            // AMBIL DETAIL REKENING PENDAPATAN
            ->flatMap(function ($jurnal) {

                return $jurnal->akuntansi_jurnal_detail
                    ->filter(function ($detail) {

                        return str_starts_with(
                            (string) $detail->akuntansi_rekening->kode_rekening,
                            '4'
                        );
                    })
                    ->map(function ($detail) use ($jurnal) {

                        // Kredit = pendapatan bertambah
                        // Debit  = pendapatan berkurang/reversal
                        $nominal = strtolower($detail->posisi) === 'kredit'
                            ? $detail->nominal
                            : -$detail->nominal;

                        return [
                            'nama_rekening' => $detail->akuntansi_rekening->nama_rekening,
                            'tanggal_transaksi' => $jurnal->tanggal_transaksi,
                            'nominal' => $nominal,
                        ];
                    });
            })

            // GROUP REKENING → BULAN
            ->groupBy([
                'nama_rekening',
                fn ($item) =>
                    Carbon::parse($item['tanggal_transaksi'])
                        ->format('Y-m'),
            ]);

        // ==============================
        // HEADER BULAN
        // ==============================
        $bulanHeaders = collect($pendapatanPerBulan)
            ->flatMap(function ($item) {
                return collect($item)->keys()->all();
            })
            ->unique()
            ->sort()
            ->values();

        // ==============================
        // FORMAT BULAN INDONESIA
        // ==============================
        $bulanIndo = $bulanHeaders->mapWithKeys(function ($bulan) {
            return [
                $bulan =>
                    HelperController::formatTanggalIndonesia(
                        $bulan . '-01',
                        'F Y'
                    ),
            ];
        });

        return view('livewire.keuangan.akuntansi-laporan-pendapatan.index', [
                'pendapatanPerBulan' => $pendapatanPerBulan,
                'bulanHeaders' => $bulanHeaders,
                'bulanIndo' => $bulanIndo,
            ]
        );
    }
}
