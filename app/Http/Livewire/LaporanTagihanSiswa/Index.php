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

    public $perPage = 40;
    public $laporans = [];

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
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function clearFilters()
    {
        // $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->selectedKategoriTagihan = [];
        $this->selectedJenisTagihan = [];
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function kirimWhatsappTagihan($msPenempatanSiswaId)
    {
        $laporan = collect($this->laporans)
            ->firstWhere('ms_penempatan_siswa_id', $msPenempatanSiswaId);

        if (!$laporan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan']);
            return;
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);
        }

        // Nomor telepon
        $telepon = $laporan['telepon'] ?? null;

        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Nomor telepon siswa tidak ditemukan'
            ]);
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
            . $laporan['nama_siswa']
            . "* kelas *"
            . ($laporan['nama_kelas'] ?? '-')
            . "* masih perlu diselesaikan. Berikut adalah rincian tagihannya:\n\n";

        // ===============================
        // AMBIL RINCIAN TAGIHAN
        // ===============================
        foreach ($laporan['rincian_tagihan'] as $tagihan) {

            if ($tagihan['jumlah_kekurangan'] <= 0) {
                continue;
            }

            $pesan .= " - *"
                . strtoupper($tagihan['nama_jenis_tagihan_siswa'])
                . " : Rp"
                . number_format($tagihan['jumlah_kekurangan'], 0, ',', '.')
                . "*\n";
        }

        // Total
        $pesan .= "\n*Total Tagihan Rp" . number_format($laporan['total_tagihan'], 0, ',', '.') . "*\n";

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
    
    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Jatuh tempo diperbarui'
        ]);
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function resetTanggal()
    {
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function render()
    {
        $endDate = $this->endDate
            ? Carbon::parse($this->endDate)->endOfDay()
            : Carbon::now()->endOfMonth();

        $penempatanQuery = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa' => function ($q) use ($endDate) {
                $q->with([
                    'ms_jenis_tagihan_siswa'
                ])
                    // ->withSum('dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar', 'jumlah_bayar')
                    ->withSum([
                        'dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar' => function ($q) {
                            $q->where(
                                'dt_transaksi_tagihan_siswa.status_transaksi',
                                '!=',
                                'dibatalkan'
                            );
                        }
                    ], 'jumlah_bayar')
                    ->where('status', '!=', 'Lunas')
                    ->whereHas('ms_jenis_tagihan_siswa', function ($q2) use ($endDate) {
                        $q2->where('tanggal_jatuh_tempo', '<=', $endDate);

                        if (!empty($this->selectedKategoriTagihan)) {
                            $q2->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
                        }
                    });

                if (!empty($this->selectedJenisTagihan)) {
                    $q->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihan);
                }
            }
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

        $penempatanQuery->whereHas('ms_tagihan_siswa', function ($q) use ($endDate) {
            $q->where('status', '!=', 'Lunas')
                ->whereHas('ms_jenis_tagihan_siswa', function ($q2) use ($endDate) {
                    $q2->where('tanggal_jatuh_tempo', '<=', $endDate);

                    if (!empty($this->selectedKategoriTagihan)) {
                        $q2->whereIn('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
                    }
                });

            if (!empty($this->selectedJenisTagihan)) {
                $q->whereIn('ms_jenis_tagihan_siswa_id', $this->selectedJenisTagihan);
            }
        });

        $penempatans = $penempatanQuery
            ->paginate($this->perPage);


        // ================================
        // 2) Build laporan
        // ================================
        $laporans = collect();

        foreach ($penempatans as $p) {
            $tagihan = $p->ms_tagihan_siswa;

            $laporans->push([
                'ms_penempatan_siswa_id' => $p->ms_penempatan_siswa_id,
                'nama_siswa' => $p->ms_siswa->nama_siswa,
                'telepon'    => $p->ms_siswa->telepon,
                'nama_kelas' => $p->ms_kelas->nama_kelas,
                'ms_kelas_id' => $p->ms_kelas->ms_kelas_id,

                'total_tagihan' => $tagihan->sum(function ($t) {
                    return $t->jumlah_tagihan_siswa - ($t->jumlah_sudah_dibayar ?? 0);
                }),

                'rincian_tagihan' => $tagihan
                    ->sortBy(fn($t) => $t->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo)
                    ->map(function ($t) {
                        $dibayar = $t->jumlah_sudah_dibayar ?? 0;

                        return [
                            'nama_jenis_tagihan_siswa' => $t->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa,
                            'status' => $t->status,
                            'jumlah_tagihan_siswa' => $t->jumlah_tagihan_siswa,
                            'jumlah_sudah_dibayar' => $dibayar,
                            'jumlah_kekurangan' => $t->jumlah_tagihan_siswa - $dibayar,
                        ];
                    })
                    ->values()
                    ->toArray(),
            ]);
        }
        // ================================
        // 3) Sort laporan final
        // ================================
        $laporans = $laporans->sortBy([
            ['ms_kelas_id', 'asc'],
            ['nama_siswa', 'asc'],
        ])->values();


        $this->laporans = $laporans->toArray();

        $totalTagihan = $laporans->sum('total_tagihan');

        $pesans = null;

        if ($this->selectedJenjang) {
            $pesans = WhatsAppTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();
        }

        return view('livewire.laporan-tagihan-siswa.index', [
            'laporans'      => $this->laporans,
            'totalTagihan' => $totalTagihan,     // kumpulan siswa + tagihan per siswa
            'pagination' => $penempatans, // untuk tombol paginate

            'ms_pesan_id' => $pesans ? $pesans->ms_whatsapp_tagihan_siswa_id : null,
            'pesans' => $pesans,
        ]);
    }
}
