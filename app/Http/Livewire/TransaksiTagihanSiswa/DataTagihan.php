<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Http\Controllers\HelperController;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\SuratTagihanSiswa;
use App\Models\TagihanSiswa;
use App\Models\WhatsAppPembayaranTagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class DataTagihan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $ms_penempatan_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $ms_siswa_id;

    public $saldoTabunganSiswa;
    public $saldoEduPaySiswa;

    public $totalEstimasi;
    public $totalDibayarkan;
    public $totalKekurangan;

    public $siswaSelected = false;

    protected $listeners = [
        'siswaSelected', // Listener untuk parameter siswa yang dipilih
        'tagihanUpdated'
    ];

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;
        $this->siswaSelected = true;

        // Ambil data jenjang dan tahun ajar
        $penempatanSiswa = PenempatanSiswa::find($ms_penempatan_siswa_id);

        if ($penempatanSiswa) {
            $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id;
            $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id;
            $this->ms_siswa_id = $penempatanSiswa->ms_siswa_id;
            $this->saldoTabunganSiswa = $penempatanSiswa->ms_siswa->saldo_tabungan_siswa();
            $this->saldoEduPaySiswa = $penempatanSiswa->ms_siswa->saldo_edupay_siswa();
        } else {
            $this->ms_jenjang_id = null;
            $this->ms_tahun_ajar_id = null;
            $this->ms_siswa_id = null;
        }
    }

    public function tagihanUpdated()
    {
        $penempatanSiswa = PenempatanSiswa::find($this->ms_penempatan_siswa_id);

        if ($penempatanSiswa) {
            $this->saldoTabunganSiswa = $penempatanSiswa->ms_siswa->saldo_tabungan_siswa();
            $this->saldoEduPaySiswa = $penempatanSiswa->ms_siswa->saldo_edupay_siswa();
        }
        $this->emitSelf('$refresh'); //ringan
    }

    public function aksiLunas($ms_tagihan_siswa_id)
    {
        if (!$this->ms_penempatan_siswa_id) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Siswa belum dipilih.']);
            return;
        }

        // Ambil data tagihan berdasarkan ID
        $tagihan = TagihanSiswa::where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)
            ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->first();

        if (!$tagihan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan tidak ditemukan.']);
            return;
        }

        // Periksa apakah tagihan sudah ada di keranjang
        $keranjangExist = KeranjangTagihanSiswa::where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)
            ->first();

        if ($keranjangExist) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan ini sudah ada di keranjang.']);
            return;
        }

        // Insert tagihan ke keranjang
        $ms_pengguna_id = Auth::id();

        KeranjangTagihanSiswa::create([
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'ms_tagihan_siswa_id' => $ms_tagihan_siswa_id,
            'ms_pengguna_id' => $ms_pengguna_id, // ID pengguna yang melakukan aksi
            'jumlah_bayar' => $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar(),
            'tanggal_dibayar' => now(),
            'status' => 'Lunas',
            'deskripsi' => 'Tagihan #' . $ms_tagihan_siswa_id . ' dimasukkan ke keranjang.',
        ]);

        // Update status tagihan menjadi 'Masuk Keranjang'
        $tagihan->update([
            'status' => 'Masuk Keranjang',
            'deskripsi' => 'Tagihan masuk keranjang oleh user ' . $ms_pengguna_id
        ]);

        $this->emitSelf('$refresh');
        $this->emit('keranjangUpdated'); // Emit event ke komponen Livewire terkait
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil dimasukkan ke keranjang.']);
    }

    // AKSI BAYAR
    public function showBayar($ms_tagihan_siswa_id)
    {
        // Periksa apakah tagihan ada di keranjang
        $keranjang = KeranjangTagihanSiswa::where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)->first();

        if ($keranjang) {
            // Jika sudah ada di keranjang, berikan notifikasi dan hentikan proses
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan sudah ada di keranjang.']);
            return;
        }

        // Emit event ke komponen lain untuk memuat data tagihan
        $this->emit('loadTagihan', $ms_tagihan_siswa_id);
    }

    // cek di keranjang
    public function isInKeranjang($ms_tagihan_siswa_id)
    {
        return KeranjangTagihanSiswa::where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)->exists();
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
        $templatePesan = WhatsAppPembayaranTagihanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)->first();

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

        $totalEstimasi = 0;

        foreach ($penempatanSiswa->ms_tagihan_siswa as $tagihan) {
            $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();

            if ($kekurangan <= 0) {
                continue;
            }

            $namaTagihan = strtoupper($tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan ?? 'Tidak Ditemukan');
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

        $pesan .= "\n" . $templatePesan->kalimat_penutup . "\n"; // Kalimat penutup
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n"; // Salam penutup
        $pesan .= "Tata Usaha" . ($penempatanSiswa->ms_siswa->petugas ?? '') . "\n"; // Informasi petugas
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
        // Query Tagihan
        $query = TagihanSiswa::select('ms_tagihan_siswa.*', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->join('ms_jenis_tagihan_siswa', 'ms_jenis_tagihan_siswa.ms_jenis_tagihan_siswa_id', '=', 'ms_tagihan_siswa.ms_jenis_tagihan_siswa_id')
            ->join('ms_kategori_tagihan_siswa', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', '=', 'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->whereHas('ms_penempatan_siswa', function ($q) {
                $q->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id);
            });

        $tagihans = $query
            ->orderBy('ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', 'ASC')
            ->orderBy('ms_jenis_tagihan_siswa_id', 'ASC')->get();

        $this->totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $this->totalDibayarkan = $tagihans->sum(function ($tagihan) {
            return $tagihan->jumlah_sudah_dibayar();
        });
        $this->totalKekurangan = $this->totalEstimasi - $this->totalDibayarkan;

        return view('livewire.transaksi-tagihan-siswa.data-tagihan', [
            'tagihans' => $tagihans,
        ]);
    }
}
