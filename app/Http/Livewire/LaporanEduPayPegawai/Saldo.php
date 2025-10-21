<?php

namespace App\Http\Livewire\LaporanEduPayPegawai;

use App\Models\Jabatan;
use App\Models\Pegawai;
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
    public $selectedJabatan = null;

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

    public function updatingSelectedJabatan()
    {
        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_jabatan = Jabatan::get();

        $pegawai = collect();
        $pegawai = Pegawai::with(['ms_jabatan', 'ms_jenjang', 'ms_educard'])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->when($this->selectedJabatan, fn($q) => $q->where('ms_jabatan_id', $this->selectedJabatan))
            ->when($this->search, function ($query) {
                $query->where('nama_pegawai', 'like', '%' . $this->search . '%');
            })
            ->get()
            ->filter(fn($item) => $item->saldo_edupay_pegawai() !== 0)
            ->sortByDesc(fn($item) => $item->saldo_edupay_pegawai())
            ->values(); // reset index

        $this->totalSaldo = $pegawai->sum(fn($item) => $item->saldo_edupay_pegawai());

        // Cek apakah koleksi siswa kosong.
        if (!$pegawai || $pegawai->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak ditemukan.']);
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui..']);
        }

        return view('livewire.laporan-edu-pay-pegawai.saldo', [
            'select_jabatan' => $select_jabatan,
            'pegawai' => $pegawai,
        ]);
    }
}
