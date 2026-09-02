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
        'successTransaksiTabungan' => '$refresh',
        'pegawaiSelected'
    ];

    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard', 'ms_transaksi_tabungan')
            ->findOrFail($ms_pegawai_id);

        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->selectedJenjang = $pegawai->ms_jenjang_id;
    }

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode mulai diperbarui'
        ]);
    }

    public function updatedEndDate()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Periode selesai diperbarui'
        ]);
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

    public function render()
    {
        $baseQuery = TransaksiTabungan::query()
            ->where('user_id', $this->ms_pegawai_id)
            ->where('user_type', 'pegawai');

        // 🔥 WAJIB: default state
        $summary = [
            'saldoAwal' => 0,
            'totalSetoranSebelum' => 0,
            'totalPenarikanSebelum' => 0,
        ];

        if ($this->startDate && $this->ms_pegawai_id) {
            $result = (clone $baseQuery)
                ->where('status_transaksi', '!=', 'dibatalkan')
                ->where('tanggal', '<', $this->startDate)
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN jenis_transaksi = 'setoran'
                            THEN nominal
                            ELSE 0
                        END
                    ) as total_setoran,

                    SUM(
                        CASE
                            WHEN jenis_transaksi = 'penarikan'
                            THEN nominal
                            ELSE 0
                        END
                    ) as total_penarikan
                ")
                ->first();

            $setoran = $result->total_setoran ?? 0;
            $penarikan = $result->total_penarikan ?? 0;

            $summary = [
                'saldoAwal' => $setoran - $penarikan,
                'totalSetoranSebelum' => $setoran,
                'totalPenarikanSebelum' => $penarikan,
            ];
        }

        // 🔥 TRANSAKSI
        $transaksiTabungan = collect();

        if ($this->ms_pegawai_id) {
            $transaksiTabungan = (clone $baseQuery)
                ->when(
                    $this->startDate && $this->endDate,
                    fn($q) => $q->whereBetween('tanggal', [
                        $this->startDate . ' 00:00:00',
                        $this->endDate . ' 23:59:59'
                    ])
                )
                ->orderBy('tanggal')
                ->orderBy('ms_transaksi_tabungan_id')
                ->get();

            // =====================================================
            // SALDO BERJALAN
            // Transaksi dibatalkan tidak mengubah saldo
            // =====================================================
            $saldo = $summary['saldoAwal'];

            $transaksiTabungan = $transaksiTabungan->map(function ($item) use (&$saldo) {

                if ($item->status_transaksi !== 'dibatalkan') {

                    $saldo += $item->jenis_transaksi === 'setoran'
                        ? $item->nominal
                        : -$item->nominal;
                }

                // Saldo transaksi dibatalkan tetap menggunakan
                // saldo terakhir sebelum transaksi tersebut.
                $item->saldo = $saldo;

                return $item;
            });
        }

        return view('livewire.transaksi-tabungan-pegawai.data-tabungan', [
            'transaksiTabungan' => $transaksiTabungan,
            ...$summary
        ]);
    }
}
