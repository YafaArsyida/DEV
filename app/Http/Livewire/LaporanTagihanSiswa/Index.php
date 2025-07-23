<?php

namespace App\Http\Livewire\LaporanTagihanSiswa;

use App\Http\Controllers\HelperController;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\SuratTagihanSiswa;
use App\Models\TagihanSiswa;
use App\Models\WhatsAppTagihanSiswa;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $penempatanSiswaList = [];

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $startDate = null;
    public $endDate = null;

    // public $selectedKelas = [];
    public $selectedKelas = null;
    public $selectedKategoriTagihan = [];
    public $selectedJenisTagihan = [];

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'applyFilters' => 'applyFilters',
        'clearFilters' => 'clearFilters',
    ];

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function updatingSelectedKelas()
    {
        $this->resetPage(); // Reset pagination ketika kelas berubah
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->selectedKelas = null;
        $this->resetPage(); // Reset paginasi saat parameter berubah
    }

    public function kirimWhatsappTagihan($msPenempatanSiswaId)
    {
        // Ambil data penempatan siswa beserta tagihan dan jenis tagihan
        $penempatanSiswa = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa' => function ($query) {
                if (!empty($this->selectedJenisTagihan)) {
                    $query->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihan);
                }

                if (!empty($this->selectedKategoriTagihan)) {
                    $query->whereHas('ms_jenis_tagihan_siswa', function ($q) {
                        $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
                    });
                }

                if (!empty($this->startDate) && !empty($this->endDate)) {
                    $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
                    $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
                    $query->whereBetween('tanggal_jatuh_tempo', [$startDate, $endDate]);
                }

                $query->where('status', '!=', 'Lunas');
            },
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa',
            'ms_tagihan_siswa.dt_transaksi_tagihan_siswa'
        ])->find($msPenempatanSiswaId);

        // Validasi data penempatan siswa
        if (!$penempatanSiswa) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan']);
            return;
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);
        }

        // Ambil nomor telepon siswa
        $telepon = $penempatanSiswa->ms_siswa->telepon;

        // Validasi nomor telepon
        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Nomor telepon siswa tidak ditemukan']);
            return;
        }

        // Format nomor telepon (mengganti 0 di depan dengan +62)
        $telepon = substr($telepon, 0, 1) === '0' ? '+62' . substr($telepon, 1) : $telepon;

        // Ambil template pesan dari model WhatsAppTagihanSiswa
        $templatePesan = WhatsAppTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();

        // Validasi keberadaan template
        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        // Persiapkan pesan berdasarkan template
        $pesan = "*" . $templatePesan->judul . "*\n\n"; // Judul
        $pesan .= $templatePesan->salam_pembuka . "\n\n"; // Salam pembuka
        $pesan .= $templatePesan->kalimat_pembuka . "\n\n"; // Kalimat pembuka
        $pesan .= "Kami informasikan bahwa Tagihan sekolah atas nama siswa *" . $penempatanSiswa->ms_siswa->nama_siswa . "* kelas *" . ($penempatanSiswa->ms_kelas->nama_kelas ?? '-') . "* masih perlu diselesaikan. Berikut adalah rincian tagihannya : \n\n";

        $totalTagihan = 0;

        foreach ($penempatanSiswa->ms_tagihan_siswa as $tagihan) {
            $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();

            if ($kekurangan <= 0) {
                continue;
            }

            $namaTagihan = strtoupper($tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tidak Ditemukan');
            $jatuhTempo = $tagihan->tanggal_jatuh_tempo
                ? HelperController::formatTanggalIndonesia($tagihan->tanggal_jatuh_tempo, 'd F Y')
                : 'Tidak Ditentukan';

            $pesan .= " - *{$namaTagihan} : Rp" . number_format($kekurangan, 0, ',', '.') . "*\n";
            // $pesan .= " - *{$namaTagihan} - Rp" . number_format($kekurangan, 0, ',', '.') . "*, jatuh tempo {$jatuhTempo}\n";
            $totalTagihan += $kekurangan;
        }

        $pesan .= "\n*Total Tagihan Rp" . number_format($totalTagihan, 0, ',', '.') . "*\n";

        // template instruksi
        $surat = SuratTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        if ($surat) {
            // Fungsi untuk mengganti tag <b> dan </b> dengan tanda *
            $convertToBold = function ($text) {
                return str_replace(['<b>', '</b>'], '*', $text);
            };

            if (!empty($surat->panduan)) {
                $pesan .= "\n" . $convertToBold($surat->panduan);
            }
            if (!empty($surat->instruksi_1)) {
                $pesan .= "\n" . $convertToBold($surat->instruksi_1);
            }
            if (!empty($surat->instruksi_2)) {
                $pesan .= "\n" . $convertToBold($surat->instruksi_2);
            }
            if (!empty($surat->instruksi_3)) {
                $pesan .= "\n" . $convertToBold($surat->instruksi_3);
            }
            if (!empty($surat->instruksi_4)) {
                $pesan .= "\n" . $convertToBold($surat->instruksi_4);
            }
            if (!empty($surat->instruksi_5)) {
                $pesan .= "\n" . $convertToBold($surat->instruksi_5);
            }
        }

        $pesan .= "\n\n" . $templatePesan->kalimat_penutup . "\n"; // Kalimat penutup
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n"; // Salam penutup
        $pesan .= "Tata Usaha" . ($penempatanSiswa->ms_siswa->petugas ?? '') . "\n"; // Informasi petugas
        $pesan .= HelperController::formatTanggalIndonesia(now(), 'd F Y'); // Tanggal transaksi

        // Format URL WhatsApp
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        // Emit event untuk membuka tab baru dengan URL WhatsApp
        $this->emit('openNewTab', $url);
    }

    public function applyFilters($filters)
    {
        // Simpan filter yang diterima
        $this->startDate = $filters['startDate'] ?? null;
        $this->endDate = $filters['endDate'] ?? null;
        // $this->selectedKelas = $filters['selectedKelas'] ?? [];
        $this->selectedKategoriTagihan = $filters['selectedKategoriTagihan'] ?? [];
        $this->selectedJenisTagihan = $filters['selectedJenisTagihan'] ?? [];
    }

    public function clearFilters()
    {
        $this->startDate = null;
        $this->endDate = null;

        // $this->selectedKelas = [];
        $this->selectedKategoriTagihan = [];
        $this->selectedJenisTagihan = [];
    }

    public function showExportTagihanSiswa()
    {
        // Inisialisasi data dan total
        $laporans = collect([]);
        $totals = [
            'totalTagihan' => 0,
        ];

        // Query Utama
        $query = TagihanSiswa::join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
            ->join('ms_kelas', 'ms_penempatan_siswa.ms_kelas_id', '=', 'ms_kelas.ms_kelas_id')
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->select('ms_siswa.nama_siswa', 'ms_kelas.nama_kelas', 'ms_tagihan_siswa.*')
            ->with(['ms_penempatan_siswa.ms_siswa', 'ms_penempatan_siswa.ms_kelas', 'ms_jenis_tagihan_siswa'])
            ->whereHas('ms_penempatan_siswa', function ($q) {
                $q->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                    ->where('ms_jenjang_id', $this->selectedJenjang);
            })
            ->where('ms_tagihan_siswa.status', '!=', 'Lunas');

        // Filter Berdasarkan Kelas
        if (!empty($this->selectedKelas)) {
            $query->whereHas('ms_penempatan_siswa.ms_kelas', function ($q) {
                // $q->whereIn('ms_kelas_id', $this->selectedKelas);
                $q->where('ms_kelas_id', $this->selectedKelas);
            });
        }

        // Filter Berdasarkan Tanggal Jatuh Tempo
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereBetween('tanggal_jatuh_tempo', [$startDate, $endDate]);
        }

        // Filter Berdasarkan Kategori Tagihan
        if (!empty($this->selectedKategoriTagihan)) {
            $query->whereHas('ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
            });
        }

        // Filter Berdasarkan Jenis Tagihan
        if (!empty($this->selectedJenisTagihan)) {
            $query->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihan);
        }

        // Eksekusi Query
        $tagihans = $query->get(); // Mengembalikan koleksi biasa

        // Map Data Laporan
        $laporans = $tagihans
            ->groupBy('ms_penempatan_siswa.ms_siswa.nama_siswa')
            ->map(function ($tagihanSiswa) {
                return [
                    'ms_penempatan_siswa_id' => $tagihanSiswa->first()->ms_penempatan_siswa_id,
                    'nama_siswa' => $tagihanSiswa->first()->ms_penempatan_siswa->ms_siswa->nama_siswa,
                    'nama_kelas' => $tagihanSiswa->first()->ms_penempatan_siswa->ms_kelas->nama_kelas,
                    'ms_kelas_id' => $tagihanSiswa->first()->ms_penempatan_siswa->ms_kelas->ms_kelas_id,
                    'total_tagihan' => $tagihanSiswa->reduce(function ($carry, $tagihan) {
                        return $carry + ($tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar());
                    }, 0),
                    'rincian_tagihan' => $tagihanSiswa->map(function ($tagihan) {
                        return [
                            'nama_jenis_tagihan_siswa' => $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa,
                            'status' => $tagihan->status,
                            'jumlah_tagihan_siswa' => $tagihan->jumlah_tagihan_siswa,
                            'jumlah_sudah_dibayar' => $tagihan->jumlah_sudah_dibayar(),
                            'jumlah_kekurangan' => $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar(),
                        ];
                    })->toArray(),
                ];
            })
            ->sortBy([
                ['ms_kelas_id', 'asc'],
                ['nama_siswa', 'asc'],
            ])
            ->values();

        // Hitung Total Tagihan
        $totals['totalTagihan'] = $laporans->sum('total_tagihan');

        // Emit Data untuk Proses Ekspor
        $this->emit('prepareExportTagihan', $laporans, $totals);
    }

    // Fungsi untuk menangani tombol cetak
    public function cetakSurat($msPenempatanSiswaId)
    {
        $surat = SuratTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();

        if (!$surat) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Surat tidak ditemukan untuk jenjang yang dipilih.'
            ]);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Surat sedang diproses.'
        ]);

        $url = route('laporan.tagihan-siswa.generatePDF', [
            'selectedJenjang' => $this->selectedJenjang,
            'msPenempatanSiswaId' => $msPenempatanSiswaId,
            'selectedJenisTagihan' => json_encode($this->selectedJenisTagihan),
            'selectedKategoriTagihan' => json_encode($this->selectedKategoriTagihan),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }
    public function cetakSuratKelas($ms_kelas_id)
    {
        $surat = SuratTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();

        if (!$surat) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Surat tidak ditemukan untuk jenjang yang dipilih.'
            ]);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Surat sedang diproses.'
        ]);

        $url = route('laporan.tagihan-kelas.generatePDFByClass', [
            'ms_kelas_id' => $ms_kelas_id,
            'penempatanSiswaList' => json_encode($this->penempatanSiswaList),
            'selectedJenjang' => $this->selectedJenjang,
            'selectedJenisTagihan' => json_encode($this->selectedJenisTagihan),
            'selectedKategoriTagihan' => json_encode($this->selectedKategoriTagihan),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $query = TagihanSiswa::join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
            ->join('ms_kelas', 'ms_penempatan_siswa.ms_kelas_id', '=', 'ms_kelas.ms_kelas_id')
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->join('ms_jenis_tagihan_siswa', 'ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', '=', 'ms_jenis_tagihan_siswa.ms_jenis_tagihan_siswa_id') // ✅ JOIN INI PENTING
            ->select('ms_siswa.nama_siswa', 'ms_kelas.nama_kelas', 'ms_tagihan_siswa.*')
            ->with(['ms_penempatan_siswa.ms_siswa', 'ms_penempatan_siswa.ms_kelas', 'ms_jenis_tagihan_siswa'])
            ->whereHas('ms_penempatan_siswa', function ($q) {
                $q->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                    ->where('ms_jenjang_id', $this->selectedJenjang);
            })
            ->where('ms_tagihan_siswa.status', '!=', 'Lunas');

        // Filter Nama Siswa
        if ($this->search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . trim($this->search) . '%');
        }

        // Filter Berdasarkan Kelas
        if (!empty($this->selectedKelas)) {
            $query->whereHas('ms_penempatan_siswa.ms_kelas', function ($q) {
                // $q->whereIn('ms_kelas_id', $this->selectedKelas);
                $q->where('ms_kelas_id', $this->selectedKelas);
            });
        }

        // Filter Berdasarkan Tanggal Jatuh Tempo
        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereHas('ms_jenis_tagihan_siswa', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_jatuh_tempo', [$startDate, $endDate]);
            });
        }

        // Filter Berdasarkan Kategori Tagihan
        if (!empty($this->selectedKategoriTagihan)) {
            $query->whereHas('ms_jenis_tagihan_siswa', function ($q) {
                $q->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
            });
        }

        // Filter Berdasarkan Jenis Tagihan
        if (!empty($this->selectedJenisTagihan)) {
            $query->whereIn('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihan);
        }

        // Terapkan Paginate
        $tagihans = $query->paginate(1000); // Mengatur jumlah data per halaman

        // Proses Grouping
        $laporans = $tagihans->getCollection()
            ->groupBy('ms_penempatan_siswa.ms_siswa.nama_siswa')
            ->map(function ($tagihanSiswa) {
                return [
                    'ms_penempatan_siswa_id' => $tagihanSiswa->first()->ms_penempatan_siswa_id,
                    'nama_siswa' => $tagihanSiswa->first()->ms_penempatan_siswa->ms_siswa->nama_siswa,
                    'nama_kelas' => $tagihanSiswa->first()->ms_penempatan_siswa->ms_kelas->nama_kelas,
                    'ms_kelas_id' => $tagihanSiswa->first()->ms_penempatan_siswa->ms_kelas->ms_kelas_id,
                    'total_tagihan' => $tagihanSiswa->reduce(function ($carry, $tagihan) {
                        return $carry + ($tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar());
                    }, 0),
                    'rincian_tagihan' => $tagihanSiswa->map(function ($tagihan) {
                        return [
                            'nama_jenis_tagihan_siswa' => $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa,
                            'status' => $tagihan->status,
                            'jumlah_tagihan_siswa' => $tagihan->jumlah_tagihan_siswa,
                            'jumlah_sudah_dibayar' => $tagihan->jumlah_sudah_dibayar(),
                            'jumlah_kekurangan' => $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar(),
                        ];
                    })->toArray(),
                ];
            })
            ->sortBy([
                ['ms_kelas_id', 'asc'],
                ['nama_siswa', 'asc'],
            ])
            ->values();

        $this->penempatanSiswaList = $laporans->pluck('ms_penempatan_siswa_id')->toArray();

        // Pindahkan data yang sudah di paginasi
        $paginatedLaporans = $tagihans->setCollection(collect($laporans));

        $totalTagihan = $laporans->sum('total_tagihan');

        return view('livewire.laporan-tagihan-siswa.index', [
            'select_kelas' => $select_kelas,
            'laporans' => $paginatedLaporans,
            'totalTagihan' => $totalTagihan,
        ]);
    }
}
