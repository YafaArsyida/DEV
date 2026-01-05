<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\SmartCanteen\DetailTransaksiSmartCanteen;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProdukTerlaris extends Component
{
    use WithPagination;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedPeriode = 'today';

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatingSelectedPeriode()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Memperbarui...'
        ]);

        $this->resetPage();
    }

    /** 
     * Menerima parameter dari komponen Parameter (jenjang & tahun ajar)
     */
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $this->resetPage();
    }

    /**
     * Mengembalikan range tanggal sesuai periode
     */
    private function getDateRange()
    {
        switch ($this->selectedPeriode) {
            case 'today':
                return [now()->startOfDay(), now()->endOfDay()];

            case 'yesterday':
                return [
                    now()->subDay()->startOfDay(),
                    now()->subDay()->endOfDay()
                ];

            case 'this_month':
                return [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ];

            case '3_months':
                return [
                    now()->subMonths(3)->startOfDay(),
                    now()->endOfDay()
                ];

            case '6_months':
                return [
                    now()->subMonths(6)->startOfDay(),
                    now()->endOfDay()
                ];

            default:
                return [null, null]; // tanpa filter tanggal
        }
    }

    /**
     * Query Produk Terlaris
     */
    private function queryProdukTerlaris()
    {
        [$start, $end] = $this->getDateRange();

        return DetailTransaksiSmartCanteen::query()
            ->select(
                'dt_transaksi_kantin.ms_produk_kantin_id',
                DB::raw('SUM(dt_transaksi_kantin.jumlah_produk) as total_terjual'),
                DB::raw('SUM(dt_transaksi_kantin.jumlah_bayar) as total_pendapatan')
            )
            ->join('ms_produk_kantin', 'ms_produk_kantin.ms_produk_kantin_id', '=', 'dt_transaksi_kantin.ms_produk_kantin_id')
            ->join('ms_transaksi_kantin', 'ms_transaksi_kantin.ms_transaksi_kantin_id', '=', 'dt_transaksi_kantin.ms_transaksi_kantin_id')
            ->when(
                $this->selectedJenjang,
                fn($q) =>
                $q->where('ms_produk_kantin.ms_jenjang_id', $this->selectedJenjang)
            )
            ->when(
                $start && $end,
                fn($q) =>
                $q->whereBetween('ms_transaksi_kantin.tanggal_transaksi', [$start, $end])
            )
            ->groupBy('dt_transaksi_kantin.ms_produk_kantin_id')
            ->orderByDesc('total_terjual');
    }

    public function render()
    {
        $produk = $this->queryProdukTerlaris()->paginate(10);

        return view('livewire.smart-canteen.widget.produk-terlaris', [
            'produks' => $produk
        ]);
    }
}
