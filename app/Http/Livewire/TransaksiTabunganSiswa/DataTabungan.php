<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Http\Controllers\HelperController;
use App\Models\PenempatanSiswa;
use App\Models\TabunganSiswa;
use App\Models\WhatsAppHistoriTabunganSiswa;
use Livewire\Component;

class DataTabungan extends Component
{
    public $ms_siswa_id;
    public $ms_penempatan_siswa_id;

    public $selectedJenjang = null;

    protected $listeners = [
        'refreshTabunganSiswa',
        'siswaSelected'
    ];

    public function refreshTabunganSiswa()
    {
        $this->emitSelf('$refresh');
    }

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;
        $penempatanSiswa = PenempatanSiswa::with('ms_siswa', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($ms_penempatan_siswa_id);

        $this->ms_siswa_id = $penempatanSiswa->ms_siswa_id;
        $this->selectedJenjang = $penempatanSiswa->ms_jenjang_id;

        // Emit refresh agar data di render diperbarui
        $this->emitSelf('$refresh');
    }

    public function kirimWhatsapp($tabunganId)
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);
        // Ambil data tabungan berdasarkan ID
        $tabungan = TabunganSiswa::where('ms_siswa_id', $this->ms_siswa_id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('ms_tabungan_siswa_id', 'asc')
            ->get();

        // Pastikan data tabungan ditemukan
        if ($tabungan->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tabungan tidak ditemukan']);
            return;
        }

        // Cari transaksi spesifik berdasarkan ID
        $targetTransaksi = $tabungan->where('ms_tabungan_siswa_id', $tabunganId)->first();

        if (!$targetTransaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tabungan tidak ditemukan']);
            return;
        }

        // Hitung saldo berdasarkan urutan transaksi
        $saldo = 0;
        foreach ($tabungan as $transaksi) {
            $saldo += $transaksi->jenis_transaksi === 'setoran' ? $transaksi->nominal : -$transaksi->nominal;

            // Simpan saldo saat mencapai transaksi yang diminta
            if ($transaksi->ms_tabungan_siswa_id === $tabunganId) {
                break;
            }
        }

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

        // Ambil template pesan dari PesanTransaksiTabungan
        $templatePesan = WhatsAppHistoriTabunganSiswa::where('ms_jenjang_id', $this->selectedJenjang)
            ->latest()
            ->first();

        // Pastikan template ditemukan
        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        // Tentukan label jenis transaksi
        $jenisTransaksi = ucfirst($targetTransaksi->jenis_transaksi) === 'Setoran' ? 'Setoran' : 'Penarikan';

        // Siapkan pesan yang ingin dikirim
        $pesan = "*" . $templatePesan->judul . "*\n\n"; // Judul (dengan format *)
        $pesan .= $templatePesan->salam_pembuka . "\n\n"; // Salam pembuka
        $pesan .= $templatePesan->kalimat_pembuka . "\n\n"; // Kalimat pembuka
        $pesan .= "Kami informasikan bahwa *Transaksi Tabungan* atas nama siswa *" . $targetTransaksi->ms_siswa->nama_siswa . "* telah berhasil. Berikut adalah rincian transaksinya:\n\n";
        $pesan .= "*" . $jenisTransaksi . ": Rp" . number_format($targetTransaksi->nominal, 0, ',', '.') . "*\n";
        $pesan .= "*Saldo Akhir: Rp" . number_format($saldo, 0, ',', '.') . "*\n";
        $pesan .= "\n" . $templatePesan->kalimat_penutup . "\n"; // Kalimat penutup
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n"; // Salam penutup
        $pesan .= "Tata Usaha - " . $targetTransaksi->ms_pengguna->nama . "\n"; // Informasi petugas
        $pesan .= HelperController::formatTanggalIndonesia($targetTransaksi->tanggal, 'd F Y'); // Tanggal transaksi

        // Format URL WhatsApp
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        // Emit event ke frontend untuk membuka URL di tab baru
        $this->emit('openNewTab', $url);
    }
    public function render()
    {
        $saldo = 0; // Inisialisasi di luar closure
        /// Query data tabungan siswa jika siswa dipilih
        $transaksiTabungan = $this->ms_siswa_id
            ? TabunganSiswa::where('ms_siswa_id', $this->ms_siswa_id)
            // ->orderBy('tanggal', 'asc')
            ->orderBy('ms_tabungan_siswa_id', 'asc')
            ->get()
            ->map(function ($item) use (&$saldo) {
                $saldo += $item->jenis_transaksi === 'setoran' ? $item->nominal : -$item->nominal;
                $item->saldo = $saldo;
                return $item;
            })
            : collect();

        return view('livewire.transaksi-tabungan-siswa.data-tabungan', [
            'transaksiTabungan' => $transaksiTabungan,
        ]);
    }
}
