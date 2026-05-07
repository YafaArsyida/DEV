<?php

namespace App\Http\Livewire\TransaksiPengeluaran;

use App\Models\AkuntansiRekening;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Livewire\Component;

class DataTransaksi extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedRekening = null;

    public $startDate = null;
    public $endDate = null;

    public $search;

    public $select_transaksi = [];

    protected $listeners = [
        'parameterUpdated',
        'refreshTransaksiPengeluaran' => '$refresh'
    ];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');

        // SELECT OPTION (cache candidate)
        $this->select_transaksi = AkuntansiRekening::where('akuntansi_kelompok_rekening_id', 5)
            ->where('tipe_akun', 'Beban Operasional')
            ->orderBy('kode_rekening', 'ASC')
            ->get();
    }

    public function parameterUpdated($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
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

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('transaksi.pengeluaran.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'rekening' => $this->selectedRekening,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'search' => $this->search,
        ]);

        $this->emit('openNewTab', $url);
    }
    public function render()
    {
        // ======================
        // ✅ BASE QUERY (INLINE)
        // ======================
        $base = Pengeluaran::query()
            ->with(['akuntansi_rekening', 'ms_pengguna'])
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang);

        // ======================
        // ✅ HITUNG SALDO AWAL
        // ======================
        $saldoAwal = 0;

        if ($this->startDate) {
            $saldoAwal = (clone $base)
                ->when(
                    $this->selectedRekening,
                    fn($q) =>
                    $q->where('kode_rekening', $this->selectedRekening)
                )
                ->whereDate('tanggal', '<', $this->startDate)
                ->sum('nominal');
        }

        // ======================
        // ✅ DATA PERIODE
        // ======================
        $saldo = $saldoAwal;

        $data = (clone $base)

            ->when(
                $this->selectedRekening,
                fn($q) =>
                $q->where('kode_rekening', $this->selectedRekening)
            )

            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('akuntansi_rekening', function ($qr) {
                            $qr->where('nama_rekening', 'like', '%' . $this->search . '%');
                        });
                });
            })

            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('tanggal', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay()
                ]);
            })

            ->orderBy('tanggal', 'ASC')
            ->get()

            ->map(function ($item) use (&$saldo) {
                $saldo += $item->nominal;
                $item->saldo = $saldo;
                return $item;
            });

        return view('livewire.transaksi-pengeluaran.data-transaksi',[
            'data' => $data,
            'saldoAwal' => $saldoAwal,
        ]);
    }
}
