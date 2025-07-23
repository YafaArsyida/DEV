<?php

namespace App\Http\Livewire\AkuntansiLaporanNeraca;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiKelompokRekening;
use App\Models\AkuntansiRekening;
use App\Models\Jenjang;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedBulan = null;
    public $endDate = null;

    public $namaJenjang = '';

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $janjang = Jenjang::find($jenjang);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function resetTanggal()
    {
        $this->endDate = null;
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function render()
    {
        $transaksi = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->when($this->endDate, function ($query) {
                $query->whereDate('tanggal_transaksi', '<=', $this->endDate);
            })
            ->get();

        // Kelompokkan transaksi berdasarkan kategori rekening (1xx, 2xx, 3xx)
        $kelompok = [
            'aset' => [],
            'kewajiban' => [],
            'ekuitas' => [],
        ];

        foreach ($transaksi->groupBy('kode_rekening') as $kode => $transaksiRek) {
            $rekening = $transaksiRek->first()->akuntansi_rekening;
            $namaRekening = $rekening->nama_rekening;
            $posisiNormal = $rekening->posisi_normal;
            $kodeAwal = substr($kode, 0, 1);

            // Hitung saldo berdasarkan posisi normal
            $saldo = $transaksiRek->sum(function ($t) use ($posisiNormal) {
                if ($t->posisi === $posisiNormal) {
                    return $t->nominal;
                } else {
                    return -$t->nominal;
                }
            });

            $data = [
                'kode' => $kode,
                'nama' => $namaRekening,
                'saldo' => $saldo,
            ];

            if ($kodeAwal === '1') {
                $kelompok['aset'][] = $data;
            } elseif ($kodeAwal === '2') {
                $kelompok['kewajiban'][] = $data;
            } elseif ($kodeAwal === '3') {
                $kelompok['ekuitas'][] = $data;
            }
        }

        return view('livewire.akuntansi-laporan-neraca.index', [
            'kelompok' => $kelompok,
        ]);
    }
}
