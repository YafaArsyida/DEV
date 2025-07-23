<?php

namespace App\Http\Livewire\Parameter;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

use App\Models\Jenjang;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;

class JenjangTahunAjarSiswa extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $search = '';

    public $saldoKas;
    public $saldoBank;

    protected $listeners = [
        'refreshSiswas' => 'handleRefreshSiswas',
        'refreshParameters',
        'refreshSaldo'
    ];

    private function calculateSaldo($kodeRekening)
    {
        $debit = AkuntansiJurnalDetail::where('kode_rekening', $kodeRekening)
            ->where('posisi', 'debit')
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->sum('nominal');

        $kredit = AkuntansiJurnalDetail::where('kode_rekening', $kodeRekening)
            ->where('posisi', 'kredit')
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->sum('nominal');

        return $debit - $kredit;
    }

    public function refreshSaldo()
    {
        $this->saldoKas = $this->calculateSaldo(11001); // Rekening kas
        $this->saldoBank = $this->calculateSaldo(11002); // Rekening bank
    }

    public function handleRefreshSiswas()
    {
        $this->emitSelf('$refresh');
    }

    public function updatedSelectedJenjang()
    {
        $this->checkAndEmitParameters();
    }

    public function updatedSelectedTahunAjar()
    {
        $this->checkAndEmitParameters();
    }

    public function mount()
    {
        // Tetapkan nilai pertama dari data yang tersedia jika ada
        $firstJenjang = Jenjang::whereIn('ms_jenjang_id', function ($query) {
            $query->select('ms_jenjang_id')
                ->from('ms_akses_jenjang')
                ->where('ms_pengguna_id', Auth::id());
        })->where('status', 'Aktif')->first();

        $firstTahunAjar = TahunAjar::where('status', 'Aktif')
            ->orderBy('urutan', 'asc')->first();

        $this->selectedJenjang = $firstJenjang->ms_jenjang_id ?? null;
        $this->selectedTahunAjar = $firstTahunAjar->ms_tahun_ajar_id ?? null;
    }

    private function checkAndEmitParameters()
    {
        // Emit hanya jika kedua parameter tidak null
        if ($this->selectedJenjang !== null && $this->selectedTahunAjar !== null) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }

    public function refreshParameters()
    {
        $this->selectedJenjang = null;
        $this->selectedTahunAjar = null;
        $this->emit('parameterUpdated', null, null);
    }

    public function render()
    {
        // Emit parameterUpdated saat komponen dirender pertama kali
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
        }

        $this->refreshSaldo();

        // Data siswa (hanya jika Jenjang dan Tahun Ajar dipilih)
        $siswas = null; // Awalnya null untuk menghindari error
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = PenempatanSiswa::with(['ms_siswa.ms_educard', 'ms_kelas', 'ms_tahun_ajar', 'ms_jenjang'])
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            // Filter berdasarkan kelas (jika dipilih)
            if ($this->search) {
                $query->where(function ($query) {
                    $query->whereHas('ms_siswa', function ($query) {
                        $query->where('nama_siswa', 'like', '%' . $this->search . '%');
                    })->orWhereHas('ms_siswa.ms_educard', function ($query) {
                        $query->where('kode_kartu', 'like', '%' . $this->search . '%');
                    });
                });
            }

            // Dapatkan hasil dengan paginasi
            $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
                ->orderBy('ms_siswa.nama_siswa')->paginate(10);
        }

        return view('livewire.parameter.jenjang-tahun-ajar-siswa', [
            'select_jenjang' => Jenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id()); // Filter berdasarkan pengguna yang login
            })->where('status', 'Aktif')->get(),
            'select_tahun_ajar' => TahunAjar::where('status', 'Aktif')->orderBy('urutan', 'asc')->get(),
            'siswa' => $siswas
        ]);
    }
}
