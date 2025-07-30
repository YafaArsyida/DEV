<?php

namespace App\Http\Livewire\AkuntansiLaporanNeraca;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiKelompokRekening;
use App\Models\AkuntansiRekening;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedBulan = null;
    public $endDate = null;

    public $namaJenjang = '';
    public $labaRugi = 0;
    public $tutupBuku = 'belum';

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

    public function isTahunAjaranSudahTutupBuku()
    {
        $tahunAjar = TahunAjar::find($this->selectedTahunAjar);
        return $tahunAjar && $tahunAjar->tutup_buku === 'sudah';
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('akuntansi.laporan-neraca.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'end_date' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        if ($this->selectedTahunAjar) {
            $tahunAjar = TahunAjar::findOrFail($this->selectedTahunAjar);
            $this->labaRugi = $tahunAjar->hitungLabaRugi($this->selectedJenjang);
            $this->tutupBuku = $tahunAjar->tutup_buku;
        }

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
            // Abaikan akun 32001 jika tahun ajaran sudah ditutup buku
            if ($kode == '32001' || $kode == '33001') {
                continue;
            }

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
