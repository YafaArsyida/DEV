<?php

namespace App\Http\Livewire\LaporanEduPaySiswa;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Saldo extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $search = '';
    public $totalSaldo = 0;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshSaldoEduPay'
    ];

    public function refreshSaldoEduPay()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->resetPage(); // Reset pagination ketika parameter berubah
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function updatingSelectedKelas()
    {
        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function ExportSaldoEduPaySiswa()
    {
        $siswas = collect();
        $query = PenempatanSiswa::with(['ms_siswa.ms_educard', 'ms_kelas', 'ms_tahun_ajar', 'ms_jenjang'])
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

        // Filter berdasarkan kelas (jika dipilih)
        if ($this->selectedKelas) {
            $query->where('ms_kelas_id', $this->selectedKelas);
        }

        if ($this->search) {
            $query->where(function ($query) {
                $query->whereHas('ms_siswa', function ($query) {
                    $query->where('nama_siswa', 'like', '%' . $this->search . '%');
                })->orWhereHas('ms_siswa.ms_educard', function ($query) {
                    $query->where('kode_kartu', 'like', '%' . $this->search . '%');
                });
            });
        }

        $query->whereHas('ms_siswa.ms_edupay_siswa', function (Builder $query) {
            $query->whereNotNull('ms_penempatan_siswa_id');
        });


        $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')->get();

        $siswas = $siswas->map(function ($item) {
            $item['saldo_edupay_siswa'] = $item->ms_siswa->saldo_edupay_siswa();
            return $item;
        });

        // Hitung total saldo
        $this->totalSaldo = $query->get()->sum(function ($item) {
            return $item->ms_siswa->saldo_edupay_siswa() ?? 0;
        });

        // Emit data untuk frontend atau export
        $this->emit('prepareExportSaldoEduPaySiswa', $siswas->toArray(), $this->totalSaldo);
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }
        $siswas = collect();
        $siswas = PenempatanSiswa::with(['ms_siswa.ms_educard', 'ms_kelas', 'ms_tahun_ajar', 'ms_jenjang'])
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->when($this->selectedKelas, fn($q) => $q->where('ms_kelas_id', $this->selectedKelas))
            ->whereHas(
                'ms_siswa.ms_edupay_siswa',
                fn($q) =>
                $q->whereNotNull('ms_penempatan_siswa_id')
            )
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->whereHas(
                        'ms_siswa',
                        fn($q) =>
                        $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                    )->orWhereHas(
                        'ms_siswa.ms_educard',
                        fn($q) =>
                        $q->where('kode_kartu', 'like', '%' . $this->search . '%')
                    );
                });
            })
            ->get()
            ->sortByDesc(fn($item) => $item->ms_siswa->saldo_edupay_siswa() ?? 0)
            ->values(); // reset urutan index

        $this->totalSaldo = $siswas->sum(fn($item) => $item->ms_siswa->saldo_edupay_siswa() ?? 0);

        // Cek apakah koleksi siswa kosong.
        if (!$siswas || $siswas->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui..']);
        }

        return view('livewire.laporan-edu-pay-siswa.saldo', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
