<?php

namespace App\Http\Livewire\AkuntansiLaporanNeraca;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedBulan = null;
    public $startDate = null;
    public $endDate = null;

    public $namaJenjang = '';
    public $labaRugi = 0;
    public $tutupBuku = 'belum';
    public $totalAset = 0;
    public $totalKewajiban = 0;
    public $totalEkuitas = 0;
    public $totalPassiva = 0;
    public $selisihNeraca = 0;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function mount()
    {
        $this->endDate = Carbon::today()->toDateString();
    }

    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;

        $janjang = Jenjang::find($jenjang);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->endDate = Carbon::today()->toDateString();
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-neraca.pdf', [
            'jenjang' => $this->selectedJenjang,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    protected function getDateRange(): array
    {
        $endDate = $this->endDate
            ? Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay()
            : null;

        $startDate = $this->startDate
            ? Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay()
            : ($endDate ? Carbon::parse($endDate)->startOfYear()->startOfDay() : null);

        return [$startDate, $endDate];
    }

    public function render()
    {
        [$startDate, $endDate] = $this->getDateRange();

        $akunSaldo = AkuntansiJurnalDetail::query()
            ->join('akuntansi_rekening', 'akuntansi_jurnal_detail.kode_rekening', '=', 'akuntansi_rekening.kode_rekening')
            ->join('akuntansi_jurnal', 'akuntansi_jurnal_detail.akuntansi_jurnal_id', '=', 'akuntansi_jurnal.akuntansi_jurnal_id')
            ->where('akuntansi_jurnal.ms_jenjang_id', $this->selectedJenjang)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
            ->whereRaw("LEFT(akuntansi_jurnal_detail.kode_rekening, 1) IN ('1', '2', '3')")
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$startDate, $endDate]);
            })
            ->when($endDate && !$startDate, function ($query) use ($endDate) {
                $query->where('akuntansi_jurnal.tanggal_transaksi', '<=', $endDate);
            })
            ->select(
                'akuntansi_jurnal_detail.kode_rekening',
                'akuntansi_rekening.nama_rekening',
                'akuntansi_rekening.posisi_normal',
                DB::raw('SUM(CASE WHEN akuntansi_jurnal_detail.posisi = "debit" THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN akuntansi_jurnal_detail.posisi = "kredit" THEN akuntansi_jurnal_detail.nominal ELSE 0 END) as total_kredit')
            )
            ->groupBy(
                'akuntansi_jurnal_detail.kode_rekening',
                'akuntansi_rekening.nama_rekening',
                'akuntansi_rekening.posisi_normal'
            )
            ->orderBy('akuntansi_jurnal_detail.kode_rekening')
            ->get();

        $kelompok = [
            'aset' => [],
            'kewajiban' => [],
            'ekuitas' => [],
        ];

        foreach ($akunSaldo as $item) {
            $saldo = $item->posisi_normal === 'debit'
                ? ((float) $item->total_debit - (float) $item->total_kredit)
                : ((float) $item->total_kredit - (float) $item->total_debit);

            $data = [
                'kode' => $item->kode_rekening,
                'nama' => $item->nama_rekening,
                'saldo' => $saldo,
            ];

            $kodeAwal = substr((string) $item->kode_rekening, 0, 1);

            if ($kodeAwal === '1') {
                $kelompok['aset'][] = $data;
            } elseif ($kodeAwal === '2') {
                $kelompok['kewajiban'][] = $data;
            } elseif ($kodeAwal === '3') {
                $kelompok['ekuitas'][] = $data;
            }
        }

        $pendapatan = AkuntansiJurnalDetail::query()
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal.akuntansi_jurnal_id'
            )
            ->join(
                'akuntansi_rekening',
                'akuntansi_jurnal_detail.kode_rekening',
                '=',
                'akuntansi_rekening.kode_rekening'
            )
            ->where('akuntansi_jurnal.ms_jenjang_id', $this->selectedJenjang)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
            ->where('akuntansi_rekening.kode_rekening', 'like', '4%')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(
                    'akuntansi_jurnal.tanggal_transaksi',
                    [$startDate, $endDate]
                );
            })
            ->when($endDate && !$startDate, function ($query) use ($endDate) {
                $query->where(
                    'akuntansi_jurnal.tanggal_transaksi',
                    '<=',
                    $endDate
                );
            })
            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                            THEN akuntansi_jurnal_detail.nominal
                        WHEN akuntansi_jurnal_detail.posisi = 'debit'
                            THEN -akuntansi_jurnal_detail.nominal
                        ELSE 0
                    END
                ), 0) AS total
            ")
            ->value('total');

        $beban = AkuntansiJurnalDetail::query()
            ->join(
                'akuntansi_jurnal',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal.akuntansi_jurnal_id'
            )
            ->join(
                'akuntansi_rekening',
                'akuntansi_jurnal_detail.kode_rekening',
                '=',
                'akuntansi_rekening.kode_rekening'
            )
            ->where('akuntansi_jurnal.ms_jenjang_id', $this->selectedJenjang)
            ->where('akuntansi_jurnal.ms_departemen_id', 'SEKOLAH')
            ->where('akuntansi_rekening.kode_rekening', 'like', '5%')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween(
                    'akuntansi_jurnal.tanggal_transaksi',
                    [$startDate, $endDate]
                );
            })
            ->when($endDate && !$startDate, function ($query) use ($endDate) {
                $query->where(
                    'akuntansi_jurnal.tanggal_transaksi',
                    '<=',
                    $endDate
                );
            })
            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN akuntansi_jurnal_detail.posisi = 'debit'
                            THEN akuntansi_jurnal_detail.nominal
                        WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                            THEN -akuntansi_jurnal_detail.nominal
                        ELSE 0
                    END
                ), 0) AS total
            ")
            ->value('total');

        $this->labaRugi = (float) $pendapatan - (float) $beban;

        $this->totalAset = collect($kelompok['aset'])->sum('saldo');
        $this->totalKewajiban = collect($kelompok['kewajiban'])->sum('saldo');
        $this->totalEkuitas = collect($kelompok['ekuitas'])->sum('saldo') + ($this->labaRugi ?? 0);
        $this->totalPassiva = $this->totalKewajiban + $this->totalEkuitas;
        $this->selisihNeraca = $this->totalAset - $this->totalPassiva;

        return view('livewire.akuntansi-laporan-neraca.index',
            [
                'kelompok' => $kelompok,
                'labaRugi' => $this->labaRugi,
                'totalAset' => $this->totalAset,
                'totalKewajiban' => $this->totalKewajiban,
                'totalEkuitas' => $this->totalEkuitas,
                'totalPassiva' => $this->totalPassiva,
                'selisihNeraca' => $this->selisihNeraca,
                'startDate' => $startDate ? $startDate->toDateString() : null,
                'endDate' => $endDate ? $endDate->toDateString() : null,
            ]
        );
    }
}
