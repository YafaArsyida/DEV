<?php

namespace App\Http\Livewire\AkuntansiLaporanArusKas;

use App\Models\AkuntansiJurnalDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedBulan = null;
    public $startDate = null;
    public $endDate = null;

    public $selectedRekening = '';

    public $search = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-arus-kas.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'rekening' => $this->selectedRekening,
            'start_date' => $this->endDate,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $akunKasBank = [11001, 11002]; // 11001 = Kas, 11002 = Bank

        // Ambil data transaksi sesuai filter
        $transaksiJurnal = AkuntansiJurnalDetail::with('akuntansi_rekening', 'ms_pengguna')
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->when($this->selectedRekening, function ($query) {
                // Jika user memilih rekening tertentu
                $query->where('kode_rekening', $this->selectedRekening);
            }, function ($query) use ($akunKasBank) {
                // Jika tidak ada pilihan, tampilkan semua Kas/Bank
                $query->whereIn('kode_rekening', $akunKasBank);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
            })
            ->when($this->search, function ($query) {
                $query->where('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('tanggal_transaksi')
            ->get();

        // Hitung total kas masuk (debit) & kas keluar (kredit)
        $totalDebit = $transaksiJurnal->where('posisi', 'debit')->sum('nominal');
        $totalKredit = $transaksiJurnal->where('posisi', 'kredit')->sum('nominal');

        // Hitung saldo awal

        $saldoAwal = 0;
        if ($this->startDate != null && $this->endDate != null) {
            $saldoAwal = AkuntansiJurnalDetail::where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->when($this->selectedRekening, function ($query) {
                    // Jika user memilih rekening tertentu
                    $query->where('kode_rekening', $this->selectedRekening);
                }, function ($query) use ($akunKasBank) {
                    // Jika tidak ada pilihan, tampilkan semua Kas/Bank
                    $query->whereIn('kode_rekening', $akunKasBank);
                })
                ->when($this->startDate && $this->endDate, function ($query) {
                    $query->whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate]);
                })
                ->when($this->startDate, function ($query) {
                    $query->where('tanggal_transaksi', '<', $this->startDate);
                })
                ->selectRaw("
                SUM(CASE WHEN posisi = 'debit' THEN nominal ELSE 0 END) -
                SUM(CASE WHEN posisi = 'kredit' THEN nominal ELSE 0 END) as saldo
            ")
                ->value('saldo');
        }

        // Saldo akhir
        $saldoAkhir = $saldoAwal + ($totalDebit - $totalKredit);

        return view('livewire.akuntansi-laporan-arus-kas.index', [
            'transaksiJurnal' => $transaksiJurnal,
            'saldoAwal'       => $saldoAwal,
            'saldoAkhir'      => $saldoAkhir,
            'totalDebit'      => $totalDebit,
            'totalKredit'     => $totalKredit,
        ]);
    }
}
