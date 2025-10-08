<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Http\Controllers\HelperController;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\SuratTagihanSiswa;
use App\Models\User;
use App\Models\WhatsAppTagihanSiswa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $search = '';
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    public $totalTagihan = 0;
    public $totalDibayarkan = 0;
    public $totalKekurangan = 0;
    public $jumlahTagihan = 0;
    public $totalPersen = 0;

    // Listener untuk Livewire
    protected $listeners = [
        'refreshTagihans' => '$refresh',
        'parameterUpdated' => 'updateParameters',
        'tagihanUpdated'
    ];

    public function tagihanUpdated()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function updatingSelectedKelas()
    {
        $this->emitSelf('$refresh'); //lebih ringan
    }

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->emitSelf('$refresh'); //lebih ringan

        // $this->resetPage(); // Reset paginasi saat parameter berubah
    }

    public function cetakLaporanTagihan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'laporan diproses.']);

        $url = route('keuangan.tagihan-siswa.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'kelas' => $this->selectedKelas,
            'search' => $this->search,
        ]);

        $this->emit('openNewTab', $url);
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
        $templatePesan = WhatsAppTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();

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
            'selectedJenisTagihan' => [],
            'selectedKategoriTagihan' => [],
            'startDate' => null,
            'endDate' => null,
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

        // Data siswa (hanya jika Jenjang dan Tahun Ajar dipilih)
        $tagihans = [];

        // Reset nilai agar aman saat filter berubah
        $this->totalTagihan = 0;
        $this->totalDibayarkan = 0;
        $this->jumlahTagihan = 0;
        $this->totalKekurangan = 0;

        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $query = PenempatanSiswa::with(['ms_siswa', 'ms_kelas'])
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar);

            if ($this->selectedKelas) {
                $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
            }

            if ($this->search) {
                $query->whereHas('ms_siswa', function ($q) {
                    $q->where('nama_siswa', 'like', '%' . $this->search . '%');
                });
            }

            $tagihans = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
                ->orderBy('ms_siswa.nama_siswa')->get();

            foreach ($tagihans as $item) {
                $tagihan = $item->total_tagihan_siswa();
                $dibayar = $item->total_dibayarkan();
                $jumlah = $item->jumlah_jenis_tagihan_siswa();

                $this->totalTagihan += $tagihan;
                $this->totalDibayarkan += $dibayar;
                $this->jumlahTagihan += $jumlah;
            }

            $this->totalKekurangan = $this->totalTagihan - $this->totalDibayarkan;
            // Hindari pembagian 0
            if ($this->totalTagihan > 0) {
                $this->totalPersen = round(($this->totalDibayarkan / $this->totalTagihan) * 100, 2);
            } else {
                $this->totalPersen = 0;
            }
        }

        return view('livewire.tagihan-siswa.index', [
            'select_kelas' => $select_kelas,
            'tagihans' => $tagihans,
        ]);
    }
}
