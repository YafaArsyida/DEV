<?php

namespace App\Http\Livewire\SmartCanteen\Widget;

use App\Models\Jenjang;
use App\Models\SmartCanteen\TransaksiSmartCanteen;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KartuSettlement extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';

    public $jenisSaldo = 'estimasi'; // estimasi | belum | sudah
    public $totalSaldo = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];
    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahunAjar = TahunAjar::find($tahunAjar);

        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahunAjar ? $tahunAjar->nama_tahun_ajar : 'Tidak Diketahui';
    }

    public function setJenisSaldo($jenis)
    {
        $this->jenisSaldo = $jenis;
    }

    public function hitungSaldo()
    {
        if (!$this->selectedJenjang) {
            $this->totalSaldo = 0;
            return;
        }

        $query = TransaksiSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang);

        $user = Auth::user();

        // Jika petugas kantin → hanya data miliknya
        if ($user->peran === 'kantin') {
            $query->where('ms_pengguna_id', $user->ms_pengguna_id);
        }

        // Filter settlement
        if ($this->jenisSaldo === 'belum') {
            $query->where('status_settlement', 'belum');
        }

        if ($this->jenisSaldo === 'sudah') {
            $query->where('status_settlement', 'sudah');
        }

        $this->totalSaldo = $query->sum('total_transaksi');
    }

    public function render()
    {
        $this->hitungSaldo();
        return view('livewire.smart-canteen.widget.kartu-settlement');
    }
}
