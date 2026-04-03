<?php

namespace App\Http\Livewire\TransaksiTabunganPegawai;

use App\Http\Controllers\HelperController;
use App\Models\KuitansiTransaksiTabungan;
use App\Models\Pegawai;
use App\Models\TransaksiTabungan;
use App\Models\WhatsAppTransaksiTabungan;
use Carbon\Carbon;
use Livewire\Component;

class DataTabungan extends Component
{
    public $ms_pegawai_id;

    public $startDate = null;
    public $endDate = null;

    public $selectedJenjang = null;

    protected $listeners = [
        'refreshTabungans',
        'pegawaiSelected'
    ];

    public function refreshTabungans()
    {
        $this->emitSelf('$refresh');
    }

    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard', 'ms_transaksi_tabungan')
            ->findOrFail($ms_pegawai_id);

        if (!$pegawai) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pegawai tidak ditemukan.']);
            return;
        }

        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->selectedJenjang = $pegawai->ms_jenjang_id;

        // Emit refresh agar data di render diperbaruip
        $this->emitSelf('$refresh');
    }

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function resetTanggal()
    {
        // $this->startDate = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function kirimWhatsapp($tabunganId)
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);

        // Ambil data tabungan berdasarkan ID
        $tabungan = TransaksiTabungan::where('user_id', $this->ms_pegawai_id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('ms_transaksi_tabungan_id', 'asc')
            ->get();

        // Pastikan data tabungan ditemukan
        if (!$tabungan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tabungan tidak ditemukan']);
            return;
        }

        // Cari transaksi spesifik berdasarkan ID
        $targetTransaksi = $tabungan->where('ms_transaksi_tabungan_id', $tabunganId)->first();

        if (!$targetTransaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tabungan tidak ditemukan']);
            return;
        }

        // Hitung saldo berdasarkan urutan transaksi
        $saldo = 0;
        foreach ($tabungan as $transaksi) {
            $saldo += $transaksi->jenis_transaksi === 'setoran' ? $transaksi->nominal : -$transaksi->nominal;

            // Simpan saldo saat mencapai transaksi yang diminta
            if ($transaksi->ms_transaksi_tabungan_id === $tabunganId) {
                break;
            }
        }

        // Ambil nomor telepon 
        $telepon = $targetTransaksi->ms_pegawai->telepon;

        // Pastikan nomor telepon ada
        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Nomor telepon tidak ditemukan']);
            return;
        }

        // Replace nomor telepon yang diawali dengan '0' menjadi '+62'
        if (substr($telepon, 0, 1) === '0') {
            $telepon = '+62' . substr($telepon, 1); // Ganti '0' pertama dengan '+62'
        }

        // Ambil template pesan dari PesanTransaksiTabungan
        $templatePesan = WhatsAppTransaksiTabungan::where('ms_jenjang_id', $this->selectedJenjang)->first();

        // Pastikan template ditemukan
        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        // Tentukan label jenis transaksi
        $jenisTransaksi = ucfirst($targetTransaksi->jenis_transaksi) === 'Setoran' ? 'Setoran' : 'Penarikan';

        // Rincian deskripsi (jika ada)
        $deskripsi = !empty($targetTransaksi->deskripsi)
            ? "\n\n*{$targetTransaksi->deskripsi}*"
            : "";

        // Siapkan pesan yang ingin dikirim
        $pesan = "*" . $templatePesan->judul . "*\n\n"; // Judul (dengan format *)
        $pesan .= $templatePesan->salam_pembuka . "\n\n"; // Salam pembuka
        $pesan .= $templatePesan->kalimat_pembuka . "\n\n"; // Kalimat pembuka
        $pesan .= "Kami informasikan bahwa *Transaksi Tabungan* atas nama *" . $targetTransaksi->ms_pegawai->nama_pegawai . "* telah berhasil. Berikut adalah rincian transaksinya:\n\n";
        $pesan .= "*" . $jenisTransaksi . ": Rp" . number_format($targetTransaksi->nominal, 0, ',', '.') . "*\n";
        $pesan .= "*Saldo Akhir: Rp" . number_format($saldo, 0, ',', '.') . "*";
        $pesan .= $deskripsi . "\n\n";
        $pesan .= $templatePesan->kalimat_penutup . "\n"; // Kalimat penutup
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n"; // Salam penutup
        $pesan .= "Tata Usaha - " . $targetTransaksi->ms_pengguna->nama . "\n"; // Informasi petugas
        $pesan .= HelperController::formatTanggalIndonesia($targetTransaksi->tanggal, 'd F Y'); // Tanggal transaksi

        // Format URL WhatsApp
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        // Emit event ke frontend untuk membuka URL di tab baru
        $this->emit('openNewTab', $url);
    }

    public function cetakTransaksi($tabunganId)
    {
        $surat = KuitansiTransaksiTabungan::where('ms_jenjang_id', $this->selectedJenjang)->first();

        if (!$surat) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Kuitansi tidak ada. Cek Dokumen Administrasi'
            ]);
            return;
        }
        // Dispatch event alertify sukses
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kuitansi sedang diproses.']);

        // Menggunakan route untuk mengarahkan ke controller cetak
        $url = route('transaksi.tabungan-pegawai.kuitansiPDF', [
            'tabunganId' => $tabunganId,
            'selectedJenjang' => $this->selectedJenjang,
            'userId' => $this->ms_pegawai_id
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
    }

    private function baseQuery()
    {
        return TransaksiTabungan::query()
            ->with('ms_pengguna')
            ->where('user_id', $this->ms_pegawai_id);
    }

    public function render()
    {
        $saldoAwal = 0;
        $totalSetoranSebelum = 0;
        $totalPenarikanSebelum = 0;

        if ($this->ms_pegawai_id && $this->startDate) {

            $baseSebelum = $this->baseQuery();

            $totalSetoranSebelum = (clone $baseSebelum)
                ->whereDate('tanggal', '<', $this->startDate)
                ->where('jenis_transaksi', 'setoran')
                ->sum('nominal');

            $totalPenarikanSebelum = (clone $baseSebelum)
                ->whereDate('tanggal', '<', $this->startDate)
                ->where('jenis_transaksi', 'penarikan')
                ->sum('nominal');

            $saldoAwal = $totalSetoranSebelum - $totalPenarikanSebelum;
        }

        $saldo = $saldoAwal;

        $transaksiTabungan = $this->ms_pegawai_id
            ? $this->baseQuery()

            ->when($this->startDate && $this->endDate, function ($q) {

                $startDate = Carbon::parse($this->startDate)->startOfDay();
                $endDate   = Carbon::parse($this->endDate)->endOfDay();

                $q->whereBetween('tanggal', [$startDate, $endDate]);
            })

            ->orderBy('tanggal', 'ASC')
            ->orderBy('ms_transaksi_tabungan_id', 'ASC')

            ->get()

            ->map(function ($item) use (&$saldo) {

                if ($item->jenis_transaksi === 'setoran') {
                    $saldo += $item->nominal;
                } else {
                    $saldo -= $item->nominal;
                }

                $item->saldo = $saldo;

                return $item;
            })

            : collect();

        return view('livewire.transaksi-tabungan-pegawai.data-tabungan', [
            'transaksiTabungan' => $transaksiTabungan,
            'saldoAwal' => $saldoAwal,
            'totalSetoranSebelum' => $totalSetoranSebelum,
            'totalPenarikanSebelum' => $totalPenarikanSebelum,
        ]);
    }
}
