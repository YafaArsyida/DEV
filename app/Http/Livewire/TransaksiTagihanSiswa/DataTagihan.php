<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Http\Controllers\HelperController;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\SuratTagihanSiswa;
use App\Models\TagihanSiswa;
use App\Models\User;
use App\Models\WhatsAppTagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class DataTagihan extends Component
{
    public $ms_penempatan_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $ms_siswa_id;

    public $tagihans = [];

    public $totalEstimasi;
    public $totalDibayarkan;
    public $totalKekurangan;

    public $siswaSelected = false;

    protected $listeners = [
        'siswaSelected', // Listener untuk parameter siswa yang dipilih

        'reloadTagihanSiswa' => 'loadTagihan',
        
        'refreshTagihanSiswa' => 'loadTagihan'
    ];

    public function siswaSelected($id)
    {
        $penempatan = PenempatanSiswa::with('ms_siswa')->find($id);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Tagihan dimuat'
        ]);

        $this->siswaSelected = true;
        $this->ms_penempatan_siswa_id = $id;

        $this->ms_jenjang_id = $penempatan->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $penempatan->ms_tahun_ajar_id;
        $this->ms_siswa_id = $penempatan->ms_siswa_id;

        $this->loadTagihan(); // 🔥 load sekali
    }

    public function loadTagihan()
    {
        $tagihans = TagihanSiswa::query()
            ->with(['ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa'])
            ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar')
            ->orderBy('ms_jenis_tagihan_siswa_id')
            ->get();

        $keranjangIds = KeranjangTagihanSiswa::where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->whereIn('ms_tagihan_siswa_id', $tagihans->pluck('ms_tagihan_siswa_id'))
            ->pluck('ms_tagihan_siswa_id')
            ->flip();

        $this->tagihans = $tagihans->map(function ($item) use ($keranjangIds) {
            $totalBayar = $item->total_bayar ?? 0;

            return [
                'ms_tagihan_siswa_id' => $item->ms_tagihan_siswa_id,
                'nama_jenis' => $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa,
                'nama_kategori' => $item->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa,
                'jumlah_tagihan_siswa' => $item->jumlah_tagihan_siswa,
                'total_bayar' => $totalBayar,
                'kekurangan' => $item->jumlah_tagihan_siswa - $totalBayar,
                'cicilan_status' => $item->ms_jenis_tagihan_siswa->cicilan_status,
                'status' => $item->status,
                'in_keranjang' => isset($keranjangIds[$item->ms_tagihan_siswa_id]),
            ];
        })->values()->toArray(); // 🔥 WAJIB
    }

    public function tambahKeranjang($tagihanId)
    {
        DB::beginTransaction();
        
        try {
            if (!$this->ms_penempatan_siswa_id) {
                throw new \Exception('Siswa belum dipilih.');
            }

            $tagihan = TagihanSiswa::query()
                ->where('ms_tagihan_siswa_id', $tagihanId)
                ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar')
                ->first();

            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan');
            }

            $jumlahBayar = max(
                0,
                ($tagihan->jumlah_tagihan_siswa ?? 0) - ($tagihan->total_bayar ?? 0)
            );

            if ($jumlahBayar <= 0) {
                throw new \Exception('Tagihan sudah lunas');
            }

            KeranjangTagihanSiswa::firstOrCreate(
                [
                    'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                    'ms_tagihan_siswa_id' => $tagihanId,
                ],
                [
                    'ms_pengguna_id' => auth()->id(),
                    'jumlah_bayar' => $jumlahBayar,
                    'tanggal_dibayar' => now(),
                    'status' => 'Lunas',
                    'deskripsi' => "Tagihan #{$tagihanId} dimasukkan ke keranjang",
                ]
            );

            $tagihan->update([
                'status' => 'Masuk Keranjang',
                'deskripsi' => 'Masuk keranjang oleh petugas ' . auth()->id()
            ]);

            DB::commit();

            // 🔥 update local state (INI KUNCI UTAMA)
            foreach ($this->tagihans as $i => $item) {
                if ($item['ms_tagihan_siswa_id'] == $tagihanId) {
                    $this->tagihans[$i]['in_keranjang'] = true;
                    $this->tagihans[$i]['status'] = 'Masuk Keranjang';
                    break;
                }
            }

            
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil masuk keranjang'
            ]);
            
            $this->emit('keranjangUpdated');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function kirimWhatsappTagihan($msPenempatanSiswaId)
    {
        // Ambil data penempatan siswa beserta tagihan dan jenis tagihan
        $penempatanSiswa = PenempatanSiswa::with([
            'ms_siswa',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa',
            'ms_tagihan_siswa.dt_transaksi_tagihan_siswa'
        ])->find($msPenempatanSiswaId);

        // Pastikan data penempatan siswa ditemukan
        if (!$penempatanSiswa) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan']);
            return;
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
        $templatePesan = WhatsAppTagihanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)->first();

        // Validasi keberadaan template
        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Pesan berhasil diproses.'
        ]);

        // Persiapkan pesan berdasarkan template
        $pesan = "*" . $templatePesan->judul . "*\n\n"; // Judul
        $pesan .= $templatePesan->salam_pembuka . "\n\n"; // Salam pembuka
        $pesan .= $templatePesan->kalimat_pembuka; // Kalimat pembuka
        $pesan .= "Kami informasikan bahwa Tagihan sekolah atas nama siswa *" . $penempatanSiswa->ms_siswa->nama_siswa . "* kelas *" . ($penempatanSiswa->ms_kelas->nama_kelas ?? '-') . "* masih perlu diselesaikan. Berikut adalah rincian tagihannya : \n\n";

        $totalEstimasi = 0;

        foreach ($penempatanSiswa->ms_tagihan_siswa as $tagihan) {
            $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();

            if ($kekurangan <= 0) {
                continue;
            }

            $namaTagihan = strtoupper($tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tidak Ditemukan');
            $jatuhTempo = $tagihan->tanggal_jatuh_tempo
                ? HelperController::formatTanggalIndonesia($tagihan->tanggal_jatuh_tempo, 'd F Y')
                : 'Tidak Ditentukan';

            // $pesan .= " - *{$namaTagihan} - Rp" . number_format($kekurangan, 0, ',', '.') . "*, jatuh tempo {$jatuhTempo}\n";
            $pesan .= " - *{$namaTagihan} : Rp" . number_format($kekurangan, 0, ',', '.') . "*\n";
            $totalEstimasi += $kekurangan;
        }

        $pesan .= "\n*Total Tagihan Rp" . number_format($totalEstimasi, 0, ',', '.') . "*\n";

        // template instruksi
        $surat = SuratTagihanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)->first();
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

        $ms_pengguna_id = Auth::id();
        $nama_petugas = User::where('ms_pengguna_id', $ms_pengguna_id)->value('nama');

        $pesan .= "\n" . $templatePesan->kalimat_penutup . "\n"; // Kalimat penutup
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n"; // Salam penutup
        $pesan .= "Tata Usaha - " . ($nama_petugas ?? '') . "\n"; // Informasi petugas
        $pesan .= HelperController::formatTanggalIndonesia(now(), 'd F Y'); // Tanggal transaksi

        // Format URL WhatsApp
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        // Emit event untuk membuka tab baru dengan URL WhatsApp
        $this->emit('openNewTab', $url);
    }

    public function cetakSurat($msPenempatanSiswaId)
    {
        $surat = SuratTagihanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)->first();

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
            'selectedJenjang' => $this->ms_jenjang_id,
            'msPenempatanSiswaId' => $msPenempatanSiswaId,
            'selectedJenisTagihan' => [],
            'selectedKategoriTagihan' => [],
            'startDate' => null,
            'endDate' => null,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.data-tagihan', [
            'tagihans' => $this->tagihans,
            'totalEstimasi' => collect($this->tagihans)->sum(fn($x) => $x['jumlah_tagihan_siswa'] ?? 0),
            'totalDibayarkan' => collect($this->tagihans)->sum(fn($x) => $x['total_bayar'] ?? 0),
            'totalKekurangan' => collect($this->tagihans)->sum(fn($x) => $x['kekurangan'] ?? 0),
        ]);
    }
}
