<?php

namespace App\Http\Livewire\Keuangan\Widget;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Livewire\Component;
use Carbon\Carbon;

class KartuJumlahJurnalPengeluaran extends Component
{
    public $selectedJenjang;
    public $startDate;
    public $endDate;

    public $namaJenjang = '';
    public $namaTahunAjar = '';
    public $tanggalMulai = '';
    public $totalPengeluaran = 0;
    public $labelPeriode = 'Bulan Ini';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshJurnalHariIni' => 'hitungPengeluaran',
    ];

    public function mount()
    {
        // Set default periode ke bulan ini
        $this->setPeriode('this_month');
    }

    public function updateParameters($jenjang, $tahunAjar, $start = null, $end = null)
    {
        $this->selectedJenjang = $jenjang;

        $this->startDate = $start ?? now()->startOfMonth()->toDateString();
        $this->endDate = $end ?? now()->endOfMonth()->toDateString();

        $this->namaJenjang = Jenjang::find($jenjang)->nama_jenjang ?? 'Tidak Diketahui';
        $this->namaTahunAjar = TahunAjar::find($tahunAjar)->nama_tahun_ajar ?? 'Tidak Diketahui';
        $this->tanggalMulai = TahunAjar::find($tahunAjar)->tanggal_mulai;

        $this->hitungPengeluaran();
    }
    public function setPeriode($periode)
    {
        $today = Carbon::today();

        switch ($periode) {
            case 'this_month':
                $this->startDate = $today->copy()->startOfMonth()->toDateString();
                $this->endDate = $today->copy()->endOfMonth()->toDateString();
                $this->labelPeriode = 'Bulan Ini';
                break;

            case 'last_3_months':
                $this->startDate = $today->copy()->subMonths(3)->startOfMonth()->toDateString();
                $this->endDate = $today->copy()->endOfMonth()->toDateString();
                $this->labelPeriode = '3 Bulan Terakhir';
                break;

            case 'last_6_months':
                $this->startDate = $today->copy()->subMonths(6)->startOfMonth()->toDateString();
                $this->endDate = $today->copy()->endOfMonth()->toDateString();
                $this->labelPeriode = '6 Bulan Terakhir';
                break;

            case 'all':
                $this->startDate = $this->tanggalMulai;
                $this->endDate = $today->toDateString();
                $this->labelPeriode = 'Semua';
                break;
        }

        // Refresh data
        $this->hitungPengeluaran();
    }

    public function hitungPengeluaran()
    {
        if (!$this->selectedJenjang) {
            $this->totalPengeluaran = 0;
            return;
        }

        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        $this->totalPengeluaran = AkuntansiJurnalDetail::query()
            ->whereHas('akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '5%');
            })
            ->whereHas('akuntansi_jurnal', function ($query) use ($startDate, $endDate) {
                $query->where('ms_jenjang_id', $this->selectedJenjang)
                    ->where('ms_departemen_id', 'SEKOLAH')
                    ->whereBetween('tanggal_transaksi', [
                        $startDate,
                        $endDate
                    ]);
            })
            ->selectRaw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN posisi = 'debit' THEN nominal
                            WHEN posisi = 'kredit' THEN -nominal
                            ELSE 0
                        END
                    ),
                    0
                ) AS total
            ")
            ->value('total');

        $this->totalPengeluaran = (float) $this->totalPengeluaran;
    }

    public function render()
    {
        return view('livewire.keuangan.widget.kartu-jumlah-jurnal-pengeluaran');
    }
}
