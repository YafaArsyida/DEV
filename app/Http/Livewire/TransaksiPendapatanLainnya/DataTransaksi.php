<?php

namespace App\Http\Livewire\TransaksiPendapatanLainnya;

use App\Models\AkuntansiRekening;
use App\Models\TransaksiPendapatanLainnya;
use Carbon\Carbon;
use Livewire\Component;

class DataTransaksi extends Component
{
    public $selectedJenjang = null;
    public $selectedRekening = null;

    public $startDate = null;
    public $endDate = null;

    public $search;

    public $select_transaksi = [];

    protected $listeners = [
        'parameterUpdated',
        'refreshTransaksi' => '$refresh'
    ];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');

        // SELECT OPTION (cache candidate)
        $this->select_transaksi = AkuntansiRekening::where('akuntansi_kelompok_rekening_id', 4)
            ->where('tipe_akun', 'Pendapatan Lainnya')
            ->orderBy('kode_rekening', 'ASC')
            ->get();
    }

    public function parameterUpdated($jenjang)
    {
        // Update nilai selectedJenjang
        $this->selectedJenjang = $jenjang;
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
        if (!$this->selectedJenjang) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('transaksi.pendapatan-lainnya.pdf', [
            'jenjang' => $this->selectedJenjang,
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
        $base = TransaksiPendapatanLainnya::query()
            ->with(['akuntansi_rekening', 'ms_pengguna'])
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

            ->when($this->startDate && $this->endDate, fn($q) => $q->whereBetween('tanggal', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]))


            ->orderBy('tanggal', 'ASC')
            ->get()

            ->map(function ($item) use (&$saldo) {
                $saldo += $item->nominal;
                $item->saldo = $saldo;
                return $item;
            });

        return view('livewire.transaksi-pendapatan-lainnya.data-transaksi', [
            'data' => $data,
            'saldoAwal' => $saldoAwal,
        ]);
    }
}
