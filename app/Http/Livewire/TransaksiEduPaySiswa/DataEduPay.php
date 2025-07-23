<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Http\Controllers\HelperController;
use App\Models\EduPaySiswa;
use App\Models\KuitansiEduPaySiswa;
use App\Models\PenempatanSiswa;
use App\Models\WhatsAppEduPaySiswa;
use Livewire\Component;

class DataEduPay extends Component
{
    public $ms_siswa_id;

    public $ms_penempatan_siswa_id;
    public $selectedJenjang = null;

    protected $listeners = [
        'refreshEduPays',
        'siswaSelected'
    ];

    public function refreshEduPays()
    {
        $this->emitSelf('$refresh');
    }

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        // Simpan ID siswa yang dipilih
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;
        $penempatanSiswa = PenempatanSiswa::with('ms_siswa', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($ms_penempatan_siswa_id);

        $this->ms_siswa_id = $penempatanSiswa->ms_siswa_id;
        $this->selectedJenjang = $penempatanSiswa->ms_jenjang_id;

        // Emit refresh agar data di render diperbarui
        $this->emitSelf('$refresh');
    }

    public function kirimWhatsapp($edupayId)
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);

        // Ambil data transaksi EduPay berdasarkan ID siswa
        $edupayTransaksi = EduPaySiswa::where('ms_siswa_id', $this->ms_siswa_id)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('ms_edupay_siswa_id', 'ASC')
            ->get();

        // Pastikan data transaksi ditemukan
        if ($edupayTransaksi->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan']);
            return;
        }

        // Cari transaksi spesifik berdasarkan ID
        $targetTransaksi = $edupayTransaksi->where('ms_edupay_siswa_id', $edupayId)->first();

        if (!$targetTransaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan']);
            return;
        }

        // Hitung saldo berdasarkan urutan transaksi
        $saldo = 0;
        foreach ($edupayTransaksi as $transaksi) {
            // Periksa jenis transaksi dan update saldo sesuai dengan jenisnya
            switch ($transaksi->jenis_transaksi) {
                case 'topup':
                case 'pengembalian dana':
                case 'topup online': // Topup online juga dianggap menambah saldo
                    $saldo += $transaksi->nominal;
                    break;
                case 'penarikan':
                case 'pembayaran':
                    $saldo -= $transaksi->nominal;
                    break;
            }

            // Simpan saldo saat mencapai transaksi yang diminta
            if ($transaksi->ms_edupay_siswa_id === $edupayId) {
                break;
            }
        }

        // dd($edupayId);
        // Ambil nomor telepon siswa
        $telepon = $targetTransaksi->ms_siswa->telepon;

        // Pastikan nomor telepon ada
        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Nomor telepon siswa tidak ditemukan']);
            return;
        }

        // Replace nomor telepon yang diawali dengan '0' menjadi '+62'
        if (substr($telepon, 0, 1) === '0') {
            $telepon = '+62' . substr($telepon, 1); // Ganti '0' pertama dengan '+62'
        }

        // Pastikan template ditemukan
        $templatePesan = WhatsAppEduPaySiswa::where('ms_jenjang_id', $this->selectedJenjang)
            ->latest()
            ->first();

        // Pastikan template ditemukan
        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        // Tentukan label jenis transaksi
        $jenisTransaksi = ucfirst($targetTransaksi->jenis_transaksi);

        // Siapkan rincian pembayaran (jika deskripsi tidak null/kosong)
        $rincianPembayaran = !empty($transaksi->deskripsi)
            ? "\n\n*{$transaksi->deskripsi}*"
            : "";

        // Siapkan pesan yang ingin dikirim
        $pesan = "*" . $templatePesan->judul . "*\n\n"; // Judul (dengan format *)
        $pesan .= $templatePesan->salam_pembuka . "\n\n"; // Salam pembuka
        $pesan .= $templatePesan->kalimat_pembuka . "\n\n"; // Kalimat pembuka
        $pesan .= "Kami informasikan bahwa *Transaksi EduPay* atas nama siswa *" . $transaksi->ms_siswa->nama_siswa . "* telah berhasil. Berikut adalah rincian transaksinya:\n\n";
        $pesan .= "*" . $jenisTransaksi . " : Rp" . number_format($transaksi->nominal, 0, ',', '.') . "*\n";
        $pesan .= "*Saldo EduPay : Rp" . number_format($saldo, 0, ',', '.') . "*";
        $pesan .= $rincianPembayaran . "\n\n"; // Rincian penggunaan jika ada
        $pesan .= $templatePesan->kalimat_penutup . "\n"; // Kalimat penutup
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n"; // Salam penutup
        $pesan .= "Tata Usaha - " . $transaksi->ms_pengguna->nama . "\n"; // Informasi petugas
        $pesan .= HelperController::formatTanggalIndonesia($transaksi->tanggal, 'd F Y'); // Tanggal transaksi

        // Format URL WhatsApp
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        // Emit event ke frontend untuk membuka URL di tab baru
        $this->emit('openNewTab', $url);
    }

    public function cetakTransaksi($eduPayId)
    {
        $surat = KuitansiEduPaySiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();

        if (!$surat) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Kuitansi tidak ada. Cek Dokumen Administrasi'
            ]);
            return;
        }
        // Dispatch event alertify sukses
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kuitansi sedang diproses.']);

        // Menggunakan route untuk mengarahkan ke controller cetak
        $url = route('transaksi.edupay-siswa.kuitansiPDF', [
            'eduPayId' => $eduPayId,
            'selectedJenjang' => $this->selectedJenjang,
            'selectedSiswa' => $this->ms_siswa_id
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
    }
    public function render()
    {
        $saldo = 0; // Inisialisasi di luar closure
        /// Query data tabungan siswa jika siswa dipilih
        $transaksiEduPay = $this->ms_siswa_id
            ? EduPaySiswa::where('ms_siswa_id', $this->ms_siswa_id)
            // ->orderBy('tanggal', 'ASC')
            ->orderBy('ms_edupay_siswa_id', 'ASC')
            ->get()
            ->map(function ($item) use (&$saldo) {
                switch ($item->jenis_transaksi) {
                    case 'pengembalian dana':
                    case 'topup tunai':
                    case 'topup online':
                        $saldo += $item->nominal;
                        break;
                    case 'penarikan':
                    case 'pembayaran':
                        $saldo -= $item->nominal;
                        break;
                }

                $item->saldo = $saldo;
                return $item;
            })
            : collect();

        return view('livewire.transaksi-edu-pay-siswa.data-edu-pay', [
            'transaksiEduPay' => $transaksiEduPay,
        ]);
    }
}
