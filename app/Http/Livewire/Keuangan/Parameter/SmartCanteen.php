<?php

namespace App\Http\Livewire\Keuangan\Parameter;

use Livewire\Component;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;

class SmartCanteen extends Component
{
    public $selectedKantin = null;

    public $saldoPendapatanKantin;

    protected $listeners = [
        'refreshParameters',
        'refreshSaldo'
    ];

    public function updatedSelectedKantin()
    {
        $this->checkAndEmitParameters();
    }

    public function mount()
    {
        $user = Auth::user();
        // SUPERADMIN bisa semua kantin
        if ($user->peran === 'SUPERADMIN') {
            $firstKantin = Kantin::first();
        } else {

            // selain superadmin hanya kantin miliknya
            $firstKantin = Kantin::whereIn('ms_kantin_id', function ($query) use ($user) {
                $query->select('ms_kantin_id')
                    ->from('ms_akses_kantin')
                    ->where('ms_pengguna_id', $user->ms_pengguna_id);
            })->first();
        }

        $this->selectedKantin = $firstKantin->ms_kantin_id ?? null;
    }

    private function checkAndEmitParameters()
    {
        if ($this->selectedKantin !== null) {
            $this->emit('parameterUpdated', $this->selectedKantin);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        }
    }

    public function refreshParameters()
    {
        $this->selectedKantin = null;
        $this->emit('parameterUpdated', null);
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
            ->when($this->selectedKantin, function ($q) {

                $q->where(function ($sub) {
                    $jenjang = $this->selectedKantin;

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
        $user = Auth::user();

        // query kantin berdasarkan role
        if ($user->peran === 'SUPERADMIN') {

            $selectKantin = Kantin::get();
        } else {

            $selectKantin = Kantin::whereIn('ms_kantin_id', function ($query) use ($user) {
                $query->select('ms_kantin_id')
                    ->from('ms_akses_kantin')
                    ->where('ms_pengguna_id', $user->ms_pengguna_id);
            })->get();
        }

        if ($this->selectedKantin) {
            $this->emit('parameterUpdated', $this->selectedKantin);
        }

        // $this->refreshSaldo();

        return view('livewire.keuangan.parameter.smart-canteen', [
            'select_kantin' => $selectKantin,
            
            'select_tahun_ajar' => TahunAjar::where('status', 'Aktif')
                ->orderBy('urutan', 'asc')->get(),
        ]);
    }
}
