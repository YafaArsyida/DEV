<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use Livewire\Component;
use App\Models\KategoriTagihan;
use App\Models\KategoriTagihanSiswa;

class Kategori extends Component
{
    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $totalEstimasi = 0;
    public $totalDibayarkan = 0;
    public $totalKekurangan = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatingSearch()
    {
        $this->reset('search');
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function showExportPembayaranKategori()
    {
        // Inisialisasi data laporan dan total
        $laporans = collect([]);
        $totals = [
            'totalEstimasi' => 0,
            'totalDibayarkan' => 0,
            'totalKekurangan' => 0,
        ];

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = KategoriTagihanSiswa::with(['ms_tahun_ajar', 'ms_jenjang'])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            $query->orderBy('nama_kategori_tagihan_siswa');

            // Ambil data laporan
            $laporans = $query->get()->map(function ($item) {
                $estimasi = $item->total_tagihan_siswa() ?? 0;
                $dibayarkan = $item->total_dibayarkan() ?? 0;
                $kekurangan = $estimasi - $dibayarkan;

                // Hitung presentase
                $presentase = $estimasi > 0 ? round(($dibayarkan / $estimasi) * 100, 2) : 0;

                // Tambahkan properti ke setiap kategori
                return [
                    'ms_kategori_tagihan_siswa_id' => $item->ms_kategori_tagihan_siswa_id, // Pastikan id benar
                    'nama_kategori_tagihan_siswa' => $item->nama_kategori_tagihan_siswa,
                    'estimasi' => $estimasi,
                    'dibayarkan' => $dibayarkan,
                    'kekurangan' => $kekurangan,
                    'presentase' => $presentase,
                ];
            });

            // Hitung totals secara keseluruhan
            $totals['totalEstimasi'] = $laporans->sum('estimasi');
            $totals['totalDibayarkan'] = $laporans->sum('dibayarkan');
            $totals['totalKekurangan'] = $totals['totalEstimasi'] - $totals['totalDibayarkan'];
            $totals['totalPresentase'] = $totals['totalEstimasi'] > 0
                ? round(($totals['totalDibayarkan'] / $totals['totalEstimasi']) * 100, 2)
                : 0; // Hitung total presentase
        }

        // Emit data ke komponen lain
        $this->emit('prepareExportKategori', $laporans, $totals);
    }

    public function render()
    {
        $laporans = collect([]);
        $totals = [
            'totalEstimasi' => 0,
            'totalDibayarkan' => 0,
            'totalKekurangan' => 0,
        ];


        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = KategoriTagihanSiswa::with(['ms_tahun_ajar', 'ms_jenjang'])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

            if ($this->search) {
                $query->where('nama_kategori_tagihan_siswa', 'like', '%' . $this->search . '%');
            }

            $query->orderBy('nama_kategori_tagihan_siswa');

            // Ambil data laporan
            $laporans = $query->get()->map(function ($item) {
                $estimasi = $item->total_tagihan_siswa() ?? 0;
                $dibayarkan = $item->total_dibayarkan() ?? 0;
                $kekurangan = $estimasi - $dibayarkan;

                // Tambahkan properti ke setiap kategori
                return [
                    'ms_kategori_tagihan_siswa_id' => $item->ms_kategori_tagihan_siswa_id, // Pastikan id benar
                    'nama_kategori_tagihan_siswa' => $item->nama_kategori_tagihan_siswa,
                    'estimasi' => $estimasi,
                    'dibayarkan' => $dibayarkan,
                    'kekurangan' => $kekurangan,
                ];
            });

            // Hitung totals secara keseluruhan
            $totals['totalEstimasi'] = $laporans->sum('estimasi');
            $totals['totalDibayarkan'] = $laporans->sum('dibayarkan');
            $totals['totalKekurangan'] = $totals['totalEstimasi'] - $totals['totalDibayarkan'];
            $totals['totalPresentase'] = $totals['totalEstimasi'] > 0
                ? round(($totals['totalDibayarkan'] / $totals['totalEstimasi']) * 100, 2)
                : 0; // Hitung total presentase
        }

        return view('livewire.laporan-pembayaran-tagihan-siswa.kategori', [
            'laporans' => $laporans,
            'totals' => $totals,
        ]);
    }
}
