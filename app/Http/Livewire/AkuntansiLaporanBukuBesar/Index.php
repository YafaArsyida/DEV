<?php

namespace App\Http\Livewire\AkuntansiLaporanBukuBesar;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedJenjang = null;
    public $selectedBulan = null;
    public $selectedRekening = null;
    public $startDate = null;
    public $endDate = null;
    public $search = '';

    public $perPage = 50;

    protected $paginationTheme = 'bootstrap';

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
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-buku-besar.pdf', [
            'jenjang' => $this->selectedJenjang,
            'rekening' => $this->selectedRekening,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'search' => $this->search
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $jenisAkunRekening = AkuntansiRekening::orderBy('kode_rekening')->get();
        $selectedRekeningData = null;
        $saldoAwal = 0;

        $transaksiJurnal = AkuntansiJurnalDetail::whereRaw('0 = 1')
            ->paginate($this->perPage);
            
        $saldoAwalHalaman = 0;

        if ($this->selectedRekening) {
            $selectedRekeningData = AkuntansiRekening::where('kode_rekening', $this->selectedRekening)->first();
        }

        if ($this->selectedRekening && $this->selectedJenjang && $this->startDate && $this->endDate && $selectedRekeningData) {
            $start = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

            $saldoAwalData = AkuntansiJurnalDetail::join(
                'akuntansi_jurnal',
                'akuntansi_jurnal.akuntansi_jurnal_id',
                '=',
                'akuntansi_jurnal_detail.akuntansi_jurnal_id'
            )
                ->where('akuntansi_jurnal_detail.kode_rekening', $this->selectedRekening)
                ->where('akuntansi_jurnal.ms_jenjang_id', $this->selectedJenjang)
                ->where('akuntansi_jurnal.tanggal_transaksi', '<', $start)
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN akuntansi_jurnal_detail.posisi = 'debit'
                            THEN akuntansi_jurnal_detail.nominal
                            ELSE 0
                        END
                    ) as total_debit,

                    SUM(
                        CASE
                            WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                            THEN akuntansi_jurnal_detail.nominal
                            ELSE 0
                        END
                    ) as total_kredit
                ")
                ->first();

            $totalDebitBefore = $saldoAwalData->total_debit ?? 0;
            $totalKreditBefore = $saldoAwalData->total_kredit ?? 0;

            if ($selectedRekeningData->posisi_normal === 'kredit') {
                $saldoAwal = $totalKreditBefore - $totalDebitBefore;
            } else {
                $saldoAwal = $totalDebitBefore - $totalKreditBefore;
            }

            $query = AkuntansiJurnalDetail::with([
                'akuntansi_jurnal.ms_pengguna',
                'akuntansi_rekening',
            ])
                ->join(
                    'akuntansi_jurnal',
                    'akuntansi_jurnal.akuntansi_jurnal_id',
                    '=',
                    'akuntansi_jurnal_detail.akuntansi_jurnal_id'
                )
                ->where('akuntansi_jurnal_detail.kode_rekening', $this->selectedRekening)
                ->where('akuntansi_jurnal.ms_jenjang_id', $this->selectedJenjang)
                ->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$start, $end])
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('akuntansi_jurnal.deskripsi', 'like', '%' . $this->search . '%')
                            ->orWhere('akuntansi_jurnal.nomor_jurnal', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('akuntansi_jurnal.tanggal_transaksi')
                ->orderBy('akuntansi_jurnal.akuntansi_jurnal_id')
                ->orderBy('akuntansi_jurnal_detail.akuntansi_jurnal_detail_id')
                ->select('akuntansi_jurnal_detail.*');

            $transaksiJurnal = $query->paginate($this->perPage);

            $saldoAwalHalaman = $saldoAwal;

            if ($transaksiJurnal->currentPage() > 1 && $transaksiJurnal->count() > 0) {
                $firstRow = $transaksiJurnal->first();
                $firstTanggal = $firstRow->akuntansi_jurnal->tanggal_transaksi;
                $firstJurnalId = $firstRow->akuntansi_jurnal->akuntansi_jurnal_id;
                $firstDetailId = $firstRow->akuntansi_jurnal_detail_id;

                $saldoAwalHalamanData = AkuntansiJurnalDetail::join(
                    'akuntansi_jurnal',
                    'akuntansi_jurnal.akuntansi_jurnal_id',
                    '=',
                    'akuntansi_jurnal_detail.akuntansi_jurnal_id'
                )
                    ->where('akuntansi_jurnal_detail.kode_rekening', $this->selectedRekening)
                    ->where('akuntansi_jurnal.ms_jenjang_id', $this->selectedJenjang)
                    ->whereBetween('akuntansi_jurnal.tanggal_transaksi', [$start, $end])
                    ->when($this->search, function ($query) {
                        $query->where(function ($query) {
                            $query->where('akuntansi_jurnal.deskripsi', 'like', '%' . $this->search . '%')
                                ->orWhere('akuntansi_jurnal.nomor_jurnal', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->whereRaw(
                        '(akuntansi_jurnal.tanggal_transaksi,
                        akuntansi_jurnal.akuntansi_jurnal_id,
                        akuntansi_jurnal_detail.akuntansi_jurnal_detail_id)
                        < (?, ?, ?)',
                        [$firstTanggal, $firstJurnalId, $firstDetailId]
                    )
                    ->selectRaw("
                        SUM(
                            CASE 
                                WHEN akuntansi_jurnal_detail.posisi = 'debit'
                                THEN akuntansi_jurnal_detail.nominal
                                ELSE 0
                            END
                        ) AS total_debit,

                        SUM(
                            CASE 
                                WHEN akuntansi_jurnal_detail.posisi = 'kredit'
                                THEN akuntansi_jurnal_detail.nominal
                                ELSE 0
                            END
                        ) AS total_kredit
                    ")
                    ->first();

                $totalDebitPageBefore = $saldoAwalHalamanData->total_debit ?? 0;
                $totalKreditPageBefore = $saldoAwalHalamanData->total_kredit ?? 0;

                if ($selectedRekeningData->posisi_normal === 'kredit') {
                    $saldoAwalHalaman += $totalKreditPageBefore - $totalDebitPageBefore;
                } else {
                    $saldoAwalHalaman += $totalDebitPageBefore - $totalKreditPageBefore;
                }
            }
        }

        return view('livewire.akuntansi-laporan-buku-besar.index', [
            'jenisAkunRekening' => $jenisAkunRekening,
            'selectedRekeningData' => $selectedRekeningData,
            'transaksiJurnal' => $transaksiJurnal,
            'saldoAwal' => $saldoAwal,
            'saldoAwalHalaman' => $saldoAwalHalaman,
        ]);
    }
}
