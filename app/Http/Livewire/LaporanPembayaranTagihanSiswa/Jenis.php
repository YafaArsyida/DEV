<?php

namespace App\Http\Livewire\LaporanPembayaranTagihanSiswa;

use Livewire\Component;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;

class Jenis extends Component
{
    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKategoriTagihan = null; // Filter kategori tagihan

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updatingSearch()
    {
        $this->reset('search');
    }

    public function updatingselectedKategoriTagihan()
    {
        // Reset data kategori yang dipilih
        $this->reset('selectedKategoriTagihan');
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function showExportPembayaranJenis()
    {

        $laporans = collect([]);
        $totals = [
            'totalEstimasi' => 0,
            'totalDibayarkan' => 0,
            'totalKekurangan' => 0,
        ];

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = JenisTagihanSiswa::with(['ms_kategori_tagihan_siswa', 'ms_tahun_ajar', 'ms_jenjang'])
                ->where('ms_jenis_tagihan_siswa.ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_jenis_tagihan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar);

            // Filter berdasarkan kategori tagihan jika ada
            if ($this->selectedKategoriTagihan) {
                $query->where('ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
            }

            // Ambil data laporan dengan urutan tertentu
            $laporans = $query->orderBy('ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id')
                ->orderBy('ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa')
                ->select('ms_jenis_tagihan_siswa.*')
                ->get()
                ->map(function ($item) {
                    // Proses data per item untuk menghitung estimasi, dibayarkan, dan kekurangan
                    $estimasi = $item->total_tagihan_siswa() ?? 0;
                    $dibayarkan = $item->total_tagihan_siswa_dibayarkan() ?? 0;
                    $kekurangan = $estimasi - $dibayarkan;

                    $presentase = $estimasi > 0 ? round(($dibayarkan / $estimasi) * 100, 2) : 0;

                    // Tambahkan properti ke setiap jenis tagihan
                    return [
                        'ms_jenis_tagihan_siswa_id' => $item->ms_jenis_tagihan_siswa_id,
                        'nama_jenis_tagihan_siswa' => $item->nama_jenis_tagihan_siswa,
                        'nama_kategori_tagihan_siswa' => $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-',
                        'estimasi' => $estimasi,
                        'dibayarkan' => $dibayarkan,
                        'kekurangan' => $kekurangan,
                        'presentase' => $presentase,
                    ];
                });

            // Hitung total estimasi, dibayarkan, dan kekurangan
            $totals['totalEstimasi'] = $laporans->sum('estimasi');
            $totals['totalDibayarkan'] = $laporans->sum('dibayarkan');
            $totals['totalKekurangan'] = $totals['totalEstimasi'] - $totals['totalDibayarkan'];
            $totals['totalPresentase'] = $totals['totalEstimasi'] > 0
                ? round(($totals['totalDibayarkan'] / $totals['totalEstimasi']) * 100, 2)
                : 0; // Hitung total presentase
        }

        $this->emit('prepareExportJenis', $laporans, $totals);
    }

    public function render()
    {
        $select_kategori = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kategori = KategoriTagihanSiswa::with(['ms_jenjang', 'ms_tahun_ajar'])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $laporans = collect([]);
        $totals = [
            'totalEstimasi' => 0,
            'totalDibayarkan' => 0,
            'totalKekurangan' => 0,
        ];

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = JenisTagihanSiswa::with(['ms_kategori_tagihan_siswa', 'ms_tahun_ajar', 'ms_jenjang'])
                ->where('ms_jenis_tagihan_siswa.ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_jenis_tagihan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar);

            // Filter berdasarkan kategori tagihan jika ada
            if ($this->selectedKategoriTagihan) {
                $query->where('ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
            }

            // Filter pencarian berdasarkan nama jenis tagihan
            if ($this->search) {
                $query->where('ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa', 'like', '%' . $this->search . '%');
            }

            // Ambil data laporan dengan urutan tertentu
            $laporans = $query->orderBy('ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id')
                ->orderBy('ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa')
                ->select('ms_jenis_tagihan_siswa.*')
                ->get()
                ->map(function ($item) {
                    // Proses data per item untuk menghitung estimasi, dibayarkan, dan kekurangan
                    $estimasi = $item->total_tagihan_siswa() ?? 0;
                    $dibayarkan = $item->total_tagihan_siswa_dibayarkan() ?? 0;
                    $kekurangan = $estimasi - $dibayarkan;

                    // Tambahkan properti ke setiap jenis tagihan
                    return [
                        'ms_jenis_tagihan_siswa_id' => $item->ms_jenis_tagihan_siswa_id,
                        'nama_jenis_tagihan_siswa' => $item->nama_jenis_tagihan_siswa,
                        'kategori_tagihan_siswa' => $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-',
                        'estimasi' => $estimasi,
                        'dibayarkan' => $dibayarkan,
                        'kekurangan' => $kekurangan,
                    ];
                });

            // Hitung total estimasi, dibayarkan, dan kekurangan
            $totals['totalEstimasi'] = $laporans->sum('estimasi');
            $totals['totalDibayarkan'] = $laporans->sum('dibayarkan');
            $totals['totalKekurangan'] = $totals['totalEstimasi'] - $totals['totalDibayarkan'];
            $totals['totalPresentase'] = $totals['totalEstimasi'] > 0
                ? round(($totals['totalDibayarkan'] / $totals['totalEstimasi']) * 100, 2)
                : 0; // Hitung total presentase
        }

        return view('livewire.laporan-pembayaran-tagihan-siswa.jenis', [
            'select_kategori' => $select_kategori,
            'laporans' => $laporans,
            'totals' => $totals,
        ]);
    }
}
