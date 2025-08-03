<?php

namespace App\Http\Livewire\Widget;

use App\Models\Jenjang;
use App\Models\TagihanSiswa;
use App\Models\TahunAjar;
use Livewire\Component;

class KartuJumlahTagihanSiswa extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '';
    public $namaTahunAjar = '';
    public $totalTagihan = 0;

    public $jenisRekapitulasi = 'tagihan'; // default: estimasi tagihan total

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $tahun = TahunAjar::find($tahunAjar);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
        $this->namaTahunAjar = $tahun ? $tahun->nama_tahun_ajar : 'Tidak Diketahui';

        $this->hitungJumlah();
    }

    public function setJenisRekapitulasi($jenis)
    {
        $this->jenisRekapitulasi = $jenis;
        $this->hitungJumlah(); // untuk refresh data total
    }

    public function hitungJumlah()
    {
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $tagihan = TagihanSiswa::with('dt_transaksi_tagihan_siswa', 'ms_penempatan_siswa')
                ->whereHas('ms_penempatan_siswa', function ($query) {
                    $query->where('ms_jenjang_id', $this->selectedJenjang)
                        ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);
                })->get();

            switch ($this->jenisRekapitulasi) {
                case 'dibayar':
                    $this->totalTagihan = $tagihan->sum(fn($item) => $item->jumlah_sudah_dibayar());
                    break;
                case 'kekurangan':
                    $this->totalTagihan = $tagihan->sum(fn($item) => $item->jumlah_kekurangan());
                    break;
                default:
                    $this->totalTagihan = $tagihan->sum('jumlah_tagihan_siswa');
                    break;
            }
        } else {
            $this->totalTagihan = 0;
        }
    }

    public function render()
    {
        return view('livewire.widget.kartu-jumlah-tagihan-siswa');
    }
}
