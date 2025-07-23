<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Http\Controllers\HelperController;
use App\Models\KuitansiPembayaranTagihanSiswa;
use App\Models\TransaksiTagihanSiswa;
use App\Models\WhatsAppPembayaranTagihanSiswa;
use Livewire\WithPagination;
use Livewire\Component;

class Histori extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $ms_penempatan_siswa_id;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKategori;

    public $search = '';

    // Listener untuk Livewire
    protected $listeners = [
        'showHistoriTagihan',
        'historiUpdated'
    ];

    public function historiUpdated()
    {
        $this->emitSelf('$refresh'); //ringan

        // $this->render(); // Memanggil render untuk memperbarui data keranjang
    }

    public function showHistoriTagihan($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->ms_penempatan_siswa_id = $params['ms_penempatan_siswa_id'];
        // $this->resetPage();
        $this->emitSelf('$refresh'); //ringan
    }

    // whatsapp
    public function kirimWhatsapp($transaksiId)
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);

        // Ambil data transaksi berdasarkan ID
        $transaksi = TransaksiTagihanSiswa::find($transaksiId);

        // Pastikan transaksi ditemukan
        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan']);
            return;
        }

        // Ambil nomor telepon siswa
        $telepon = $transaksi->ms_penempatan_siswa->ms_siswa->telepon;

        // Pastikan nomor telepon ada
        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Nomor telepon siswa tidak ditemukan']);
            return;
        }

        // Replace nomor telepon yang diawali dengan '0' menjadi '+62'
        if (substr($telepon, 0, 1) === '0') {
            $telepon = '+62' . substr($telepon, 1); // Ganti '0' pertama dengan '+62'
        }

        // Ambil template pesan dari WhatsAppHistoriTagihan
        $templatePesan = WhatsAppPembayaranTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
            ->latest()
            ->first();

        // Pastikan template ditemukan
        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        $pesan = "*" . $templatePesan->judul . "*\n\n"; // Judul (dengan format *)
        $pesan .= $templatePesan->salam_pembuka . "\n\n"; // Salam pembuka
        $pesan .= $templatePesan->kalimat_pembuka . "\n\n"; // Kalimat pembuka
        // Tambahkan detail transaksi
        $pesan .= "Kami informasikan bahwa pembayaran melalui *" . $transaksi->metode_pembayaran . "* atas nama siswa *" . $transaksi->ms_penempatan_siswa->ms_siswa->nama_siswa . "* telah berhasil diproses. Berikut adalah rincian transaksinya :\n\n";
        foreach ($transaksi->dt_transaksi_tagihan_siswa as $detail) {
            // $pesan .= " - " . $detail->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa . " (" . $detail->ms_tagihan_siswa->nama_kategori_tagihan() . ") : ";
            $pesan .= " - *" . $detail->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa . " - ";
            $pesan .= "Rp" . number_format($detail->jumlah_bayar, 0, ',', '.') . "*," . "\n";
        }

        $pesan .= "\n*Infaq Rp" . number_format($transaksi->infaq, 0, ',', '.') . "*\n";
        $pesan .= "\n" . $templatePesan->kalimat_penutup . "\n"; // Kalimat penutup
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n"; // Salam penutup
        $pesan .= "Tata Usaha - " . $transaksi->ms_pengguna->nama . "\n";
        $pesan .= HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi, 'd F Y');

        // Format URL WhatsApp
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        // Emit event ke frontend untuk membuka URL di tab baru
        $this->emit('openNewTab', $url);
    }

    // Fungsi untuk menangani tombol cetak
    public function cetakTransaksi($transaksiId)
    {
        $surat = KuitansiPembayaranTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)->first();

        if (!$surat) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Kuitansi tidak ada. Cek Dokumen Administrasi'
            ]);
            return;
        }

        // Ambil data transaksi
        $transaksi = TransaksiTagihanSiswa::find($transaksiId);

        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Transaksi tidak ditemukan.'
            ]);
            return;
        }

        // Dispatch event alertify sukses
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kuitansi sedang diproses.']);

        // Menggunakan route untuk mengarahkan ke controller cetak
        $url = route('transaksi.tagihan-siswa.kuitansiPDF', [
            'transaksiId' => $transaksiId,
            'selectedJenjang' => $this->selectedJenjang,
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
    }


    public function render()
    {
        // Query Transaksi dengan relasi
        $query = TransaksiTagihanSiswa::with([
            'ms_pengguna',
            'ms_penempatan_siswa',
            'dt_transaksi_tagihan_siswa.ms_tagihan_siswa' // Include relasi detail dan tagihan jika dibutuhkan
        ])->whereHas('ms_penempatan_siswa', function ($q) {
            $q->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id);
        });

        // Filter berdasarkan pencarian nama jenis tagihan
        if ($this->search) {
            $query->whereHas('ms_tagihan_siswa.ms_jenis_tagihan_siswa', function ($q) {
                $q->where('nama_jenis_tagihan_siswa', 'like', '%' . $this->search . '%');
            });
        }

        // Paginasi dan urutan berdasarkan kategori tagihan
        $historis = $query->orderBy('ms_transaksi_tagihan_siswa_id', 'ASC')->get();

        return view('livewire.tagihan-siswa.histori', [
            'historis' => $historis
        ]);
    }
}
