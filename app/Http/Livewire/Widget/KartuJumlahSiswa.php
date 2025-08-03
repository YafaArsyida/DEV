<?php

namespace App\Http\Livewire\Widget;

use App\Models\Jenjang;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;
use Livewire\Component;

class KartuJumlahSiswa extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';
    public $jumlahSiswa = 0;

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
    public function render()
    {
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $this->jumlahSiswa = PenempatanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->count();
        }
        return view('livewire.widget.kartu-jumlah-siswa');
    }
}
