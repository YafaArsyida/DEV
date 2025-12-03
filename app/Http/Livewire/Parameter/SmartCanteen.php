<?php

namespace App\Http\Livewire\Parameter;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use App\Models\TransaksiSmartCanteen;
use Illuminate\Support\Facades\Auth;

class SmartCanteen extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $saldoPendapatanKantin;

    protected $listeners = [
        'refreshParameters',
        'refreshSaldo'
    ];

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
        if ($this->selectedJenjang !== null && $this->selectedTahunAjar !== null) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        }
    }

    public function refreshParameters()
    {
        $this->selectedJenjang = null;
        $this->selectedTahunAjar = null;
        $this->emit('parameterUpdated', null, null);
    }

    /**
     * Hitung saldo pendapatan kantin (akun 41002)
     * Rumus pendapatan = kredit - debit 
     */
    private function calculateSaldoTransaksiKantin()
    {
        $user = Auth::user();

        return TransaksiSmartCanteen::with(['ms_pengguna', 'ms_siswa', 'ms_penempatan_siswa', 'ms_pegawai'])
            ->where('status_settlement', 'belum') // transaksi belum disettle

            // 🔥 Filter berdasarkan jenjang (siswa/pegawai)
            ->when($this->selectedJenjang, function ($q) {

                $q->where(function ($sub) {
                    $jenjang = $this->selectedJenjang;

                    // Jika transaksi oleh siswa
                    $sub->orWhereHas('ms_penempatan_siswa', function ($q2) use ($jenjang) {
                        $q2->where('ms_jenjang_id', $jenjang);
                    });

                    // Jika transaksi oleh pegawai
                    $sub->orWhereHas('ms_pegawai', function ($q3) use ($jenjang) {
                        $q3->where('ms_jenjang_id', $jenjang);
                    });
                });
            })
            // 🔒 Jika yang login adalah petugas kantin → filter khusus transaksi miliknya
            ->when($user->peran === 'kantin', function ($q) use ($user) {
                $q->where('ms_pengguna_id', $user->ms_pengguna_id);
            })

            ->sum('total_transaksi');
    }

    public function refreshSaldo()
    {
        $this->saldoPendapatanKantin = $this->calculateSaldoTransaksiKantin();
    }

    public function render()
    {
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
        }

        $this->refreshSaldo();

        return view('livewire.parameter.smart-canteen', [
            'select_jenjang' => Jenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id());
            })->where('status', 'Aktif')->get(),

            'select_tahun_ajar' => TahunAjar::where('status', 'Aktif')
                ->orderBy('urutan', 'asc')->get(),
        ]);
    }
}
