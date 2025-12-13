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
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    // public $penempatanSiswaList = [];

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $select_kelas = [];

    // public $startDate = null;
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

        // Set default endDate
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        // 🔥 Load kelas ketika parameter berubah
        $this->loadKelas();


        $this->resetPage(); // Reset paginasi saat parameter berubah
    }

    public function loadKelas()
    {
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $this->select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        } else {
            $this->select_kelas = [];
        }
    }

    public function applyFilters($filters)
    {
        // Simpan filter yang diterima
        // $this->endDate = $filters['endDate'] ?? null;
        $this->selectedKategoriTagihan = $filters['selectedKategoriTagihan'] ?? [];
        $this->selectedJenisTagihan = $filters['selectedJenisTagihan'] ?? [];
    }

    public function clearFilters()
    {
        // $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->selectedKategoriTagihan = [];
        $this->selectedJenisTagihan = [];
    }

    public function kirimWhatsappTagihan($msPenempatanSiswaId)
    {
        // Ambil penempatan + relasi dasar
        $penempatanSiswa = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa',
            'ms_tagihan_siswa.dt_transaksi_tagihan_siswa'
        ])->find($msPenempatanSiswaId);

        if (!$penempatanSiswa) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan']);
            return;
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);
        }

        // Nomor telepon
        $telepon = $penempatanSiswa->ms_siswa->telepon;
        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Nomor telepon siswa tidak ditemukan']);
            return;
        }
        if (substr($telepon, 0, 1) === '0') {
            $telepon = '+62' . substr($telepon, 1);
        }

        // Template pesan
        $templatePesan = WhatsAppTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        // Pesan awal
        $pesan  = "*" . $templatePesan->judul . "*\n\n";
        $pesan .= $templatePesan->salam_pembuka . "\n\n";
        $pesan .= $templatePesan->kalimat_pembuka;
        $pesan .= "Kami informasikan bahwa Tagihan sekolah atas nama siswa *"
            . $penempatanSiswa->ms_siswa->nama_siswa
            . "* kelas *" . ($penempatanSiswa->ms_kelas->nama_kelas ?? '-') . "* masih perlu diselesaikan. Berikut adalah rincian tagihannya: \n\n";

        // ===============================
        // AMBIL TAGIHAN YANG SUDAH DIFILTER & SORT (SAMA DENGAN TAMPILAN)
        // ===============================
        $filteredTagihan = $this->getFilteredTagihan($penempatanSiswa);

        $totalTagihan = 0;

        foreach ($filteredTagihan as $tagihan) {

            // Hitung kekurangan
            $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();
            if ($kekurangan <= 0) continue;

            // Nama & Jatuh Tempo
            $namaTagihan = strtoupper($tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? '-');
            $jatuhTempo  = $tagihan->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo
                ? HelperController::formatTanggalIndonesia($tagihan->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo, 'd F Y')
                : '-';

            // Tambahkan baris untuk WA
            $pesan .= " - *{$namaTagihan} : Rp" . number_format($kekurangan, 0, ',', '.') . "*\n";

            // Jika mau tampilkan jatuh tempo, pakai ini:
            // $pesan .= " - *{$namaTagihan} - Rp" . number_format($kekurangan, 0, ',', '.') . "*, jatuh tempo {$jatuhTempo}\n";

            $totalTagihan += $kekurangan;
        }

        // Total
        $pesan .= "\n*Total Tagihan Rp" . number_format($totalTagihan, 0, ',', '.') . "*\n";

        // Instruksi tambahan dari surat
        $surat = SuratTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        if ($surat) {
            $convert = fn($text) => str_replace(['<b>', '</b>'], '*', $text);

            foreach (['panduan', 'instruksi_1', 'instruksi_2', 'instruksi_3', 'instruksi_4', 'instruksi_5'] as $field) {
                if (!empty($surat->{$field})) {
                    $pesan .= "\n" . $convert($surat->{$field});
                }
            }
        }

        // Petugas
        $ms_pengguna_id = Auth::id();
        $nama_petugas = User::where('ms_pengguna_id', $ms_pengguna_id)->value('nama');

        $pesan .= "\n" . $templatePesan->kalimat_penutup . "\n";
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n";
        $pesan .= "Tata Usaha - " . ($nama_petugas ?? '') . "\n";
        $pesan .= HelperController::formatTanggalIndonesia(now(), 'd F Y');

        // Buat URL WhatsApp
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        $this->emit('openNewTab', $url);
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
        // if ($this->startDate && $this->endDate) {
        //     $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
        //     $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
        //     $query->whereBetween('tanggal_jatuh_tempo', [$startDate, $endDate]);
        // }

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
            // 'penempatanSiswaList' => json_encode($this->penempatanSiswaList),
            'selectedJenjang' => $this->selectedJenjang,
            'selectedJenisTagihan' => json_encode($this->selectedJenisTagihan),
            'selectedKategoriTagihan' => json_encode($this->selectedKategoriTagihan),
            'endDate' => $this->endDate,
        ]);

        $this->emit('openNewTab', $url);
    }

    private function getFilteredTagihan(PenempatanSiswa $p)
    {
        $endDate = $this->endDate
            ? Carbon::parse($this->endDate)->endOfDay()
            : Carbon::now()->endOfMonth();

        return $p->ms_tagihan_siswa
            ->filter(function ($t) use ($endDate) {

                if ($t->status === 'Lunas') return false;

                if (!empty($this->selectedKategoriTagihan)) {
                    if (!in_array($t->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa_id, $this->selectedKategoriTagihan)) {
                        return false;
                    }
                }

                if (!empty($this->selectedJenisTagihan)) {
                    if (!in_array($t->ms_jenis_tagihan_siswa_id, $this->selectedJenisTagihan)) {
                        return false;
                    }
                }

                return $t->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo <= $endDate;
            })
            ->sortBy(fn($t) => $t->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo)
            ->values();
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Tanggal diperbarui'
        ]);
    }

    public function resetTanggal()
    {
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function render()
    {
        // ================================
        // 1) Query siswa (paginate)
        // ================================
        $penempatanQuery = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa',
        ])
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang);

        if ($this->search) {
            $penempatanQuery->whereHas('ms_siswa', function ($q) {
                $q->where('nama_siswa', 'like', '%' . trim($this->search) . '%');
            });
        }

        if (!empty($this->selectedKelas)) {
            $penempatanQuery->where('ms_kelas_id', $this->selectedKelas);
        }

        $penempatans = $penempatanQuery->paginate(25);


        // ================================
        // 2) Build laporan
        // ================================
        $laporans = collect();

        foreach ($penempatans as $p) {
            $tagihanFiltered = $this->getFilteredTagihan($p);

            if ($tagihanFiltered->isEmpty()) continue;

            $laporans->push([
                'ms_penempatan_siswa_id' => $p->ms_penempatan_siswa_id,
                'nama_siswa' => $p->ms_siswa->nama_siswa,
                'nama_kelas' => $p->ms_kelas->nama_kelas,
                'ms_kelas_id' => $p->ms_kelas->ms_kelas_id,

                'total_tagihan' => $tagihanFiltered->sum(
                    fn($t) =>
                    $t->jumlah_tagihan_siswa - $t->jumlah_sudah_dibayar()
                ),

                'rincian_tagihan' => $tagihanFiltered->map(function ($t) {
                    return [
                        'nama_jenis_tagihan_siswa' => $t->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa,
                        'status' => $t->status,
                        'jumlah_tagihan_siswa' => $t->jumlah_tagihan_siswa,
                        'jumlah_sudah_dibayar' => $t->jumlah_sudah_dibayar(),
                        'jumlah_kekurangan' => $t->jumlah_tagihan_siswa - $t->jumlah_sudah_dibayar(),
                    ];
                })->toArray(),
            ]);
        }

        // ================================
        // 3) Sort laporan final
        // ================================
        $laporans = $laporans->sortBy([
            ['ms_kelas_id', 'asc'],
            ['nama_siswa', 'asc'],
        ])->values();

        $totalTagihan = $laporans->sum('total_tagihan');

        $pesans = null;

        if ($this->selectedJenjang) {
            $pesans = WhatsAppTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.laporan-tagihan-siswa.index', [
            'laporans' => $laporans,     // kumpulan siswa + tagihan per siswa
            'totalTagihan' => $totalTagihan,     // kumpulan siswa + tagihan per siswa
            'pagination' => $penempatans, // untuk tombol paginate

            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_tagihan_siswa_id : null,
            'pesans' => $pesans,
        ]);
    }
}
