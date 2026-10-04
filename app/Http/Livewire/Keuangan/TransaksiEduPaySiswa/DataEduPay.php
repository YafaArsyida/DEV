<?php

namespace App\Http\Livewire\Keuangan\TransaksiEduPaySiswa;

use App\Http\Controllers\HelperController;
use App\Models\KuitansiTransaksiEduPay;
use App\Models\PenempatanSiswa;
use App\Models\TransaksiEduPay;
use App\Models\WhatsAppTransaksiEduPay;
use Livewire\Component;

class DataEduPay extends Component
{
    public $ms_siswa_id;

    public $ms_penempatan_siswa_id;
    
    public $startDate = null;
    public $endDate = null;
    
    public $selectedJenjang = null;

    public $selectedJenis = '';     // topup tunai, pembayaran, kantin, dll
    public $search = '';

    protected $listeners = [
        'successTransaksiEduPay' => '$refresh',
        'siswaSelected'
    ];

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        // Simpan ID siswa yang dipilih
        $penempatan = PenempatanSiswa::select(
            'ms_siswa_id',
            'ms_jenjang_id'
        )
            ->findOrFail($ms_penempatan_siswa_id);

        $this->ms_penempatan_siswa_id = $penempatan->ms_penempatan_siswa_id;

        $this->ms_siswa_id = $penempatan->ms_siswa_id;
        $this->selectedJenjang = $penempatan->ms_jenjang_id;
    }

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');
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

    // public function updatingSearch()
    // {
    //     $this->dispatchBrowserEvent('alertify-success', [
    //         'message' => 'Memperbarui'
    //     ]);
    // }

    public function resetTanggal()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function kirimWhatsapp($edupayId)
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);

        // Ambil transaksi target
        $targetTransaksi = TransaksiEduPay::with(['ms_siswa', 'ms_pengguna'])
            ->where('user_type', 'siswa')
            ->where('ms_transaksi_edupay_id', $edupayId)
            ->where('user_id', $this->ms_siswa_id)
            ->first();

        if (!$targetTransaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan']);
            return;
        }

        // Ambil semua transaksi sebelum transaksi target (urut ASC)
        $edupayTransaksi = TransaksiEduPay::where('user_id', $this->ms_siswa_id)
            ->where(function ($q) use ($targetTransaksi) {
                $q->where('tanggal', '<', $targetTransaksi->tanggal)
                    ->orWhere(function ($sub) use ($targetTransaksi) {
                        $sub->where('tanggal', $targetTransaksi->tanggal)
                            ->where('ms_transaksi_edupay_id', '<', $targetTransaksi->ms_transaksi_edupay_id);
                    });
            })
            ->orderBy('tanggal', 'ASC')
            ->orderBy('ms_transaksi_edupay_id', 'ASC')
            ->get();

        // ============================
        // 1️⃣ Saldo sebelum transaksi
        // ============================
        $saldoSebelum = 0;
        foreach ($edupayTransaksi as $trx) {
            if (in_array($trx->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
                $saldoSebelum += $trx->nominal;
            } elseif (in_array($trx->jenis_transaksi, ['penarikan', 'pembayaran', 'kantin'])) {
                $saldoSebelum -= $trx->nominal;
            }
        }

        // ============================
        // 2️⃣ Saldo setelah transaksi
        // ============================
        if (in_array($targetTransaksi->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
            $saldoSetelah = $saldoSebelum + $targetTransaksi->nominal;
        } else {
            $saldoSetelah = $saldoSebelum - $targetTransaksi->nominal;
        }

        // Ambil nomor telepon siswa
        $telepon = $targetTransaksi->ms_siswa->telepon;
        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Nomor telepon siswa tidak ditemukan']);
            return;
        }

        // Ubah format nomor jadi +62
        if (substr($telepon, 0, 1) === '0') {
            $telepon = '+62' . substr($telepon, 1);
        }

        // Ambil template WA
        $templatePesan = WhatsAppTransaksiEduPay::where('ms_jenjang_id', $this->selectedJenjang ?? null)
            ->latest()
            ->first();

        if (!$templatePesan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Template pesan tidak ditemukan']);
            return;
        }

        // Tentukan label jenis transaksi
        $jenisTransaksi = ucfirst($targetTransaksi->jenis_transaksi);

        // Rincian deskripsi (jika ada)
        $rincianPembayaran = !empty($targetTransaksi->deskripsi)
            ? "\n\n*{$targetTransaksi->deskripsi}*"
            : "";

        // Siapkan isi pesan
        $pesan = "*" . $templatePesan->judul . "*\n\n";
        $pesan .= $templatePesan->salam_pembuka . "\n\n";
        $pesan .= $templatePesan->kalimat_pembuka . "\n\n";
        $pesan .= "Kami informasikan bahwa *Transaksi EduPay* atas nama siswa *" . $targetTransaksi->ms_siswa->nama_siswa . "* telah berhasil. Berikut adalah rincian transaksinya:\n\n";
        $pesan .= "*" . $jenisTransaksi . " : Rp" . number_format($targetTransaksi->nominal, 0, ',', '.') . "*\n";
        $pesan .= "*Saldo EduPay : Rp"
            . number_format($saldoSebelum, 0, ',', '.')
            . " → Rp"
            . number_format($saldoSetelah, 0, ',', '.')
            . "*";
        // $pesan .= "*Saldo EduPay : Rp" . number_format($saldoSetelah, 0, ',', '.') . "*";

        $pesan .= $rincianPembayaran . "\n\n";
        $pesan .= $templatePesan->kalimat_penutup . "\n";
        $pesan .= "\n" . $templatePesan->salam_penutup . "\n\n";
        $pesan .= "Tata Usaha - " . $targetTransaksi->ms_pengguna->nama . "\n";
        $pesan .= HelperController::formatTanggalIndonesia($targetTransaksi->tanggal, 'd F Y');

        // Buat URL WA
        $url = "https://wa.me/{$telepon}?text=" . urlencode($pesan);

        // Emit ke frontend
        $this->emit('openNewTab', $url);
    }

    // Fungsi untuk menangani tombol cetak
    public function cetakTransaksi($eduPayId)
    {
        $surat = KuitansiTransaksiEduPay::where('ms_jenjang_id', $this->selectedJenjang)->first();

        if (!$surat) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Kuitansi tidak ada. Cek Dokumen Administrasi'
            ]);
            return;
        }
        // Dispatch event alertify sukses
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kuitansi sedang diproses.']);

        // Menggunakan route untuk mengarahkan ke controller cetak
        $url = route('keuangan.transaksi.edupay-siswa.kuitansi', [
            'eduPayId' => $eduPayId,
            'selectedJenjang' => $this->selectedJenjang,
            'userId' => $this->ms_siswa_id
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $baseQuery = TransaksiEduPay::query()
            ->with('ms_pengguna')
            ->where('user_type', 'siswa')
            ->where('user_id', $this->ms_siswa_id);

        $summary = [
            'saldoAwal' => 0,
            'totalMasukSebelum' => 0,
            'totalKeluarSebelum' => 0,
        ];

        // saldo awal
        if ($this->ms_siswa_id && $this->startDate) {
            $result = (clone $baseQuery)
                ->where('status_transaksi', '!=', 'dibatalkan')
                ->where('tanggal', '<', $this->startDate)
                ->selectRaw("
                SUM(CASE 
                    WHEN jenis_transaksi IN ('topup tunai','topup online','pengembalian dana') 
                    THEN nominal ELSE 0 END) as total_masuk,

                SUM(CASE 
                    WHEN jenis_transaksi IN ('penarikan','pembayaran','kantin') 
                    THEN nominal ELSE 0 END) as total_keluar
            ")
                ->first();

            $masuk = $result->total_masuk ?? 0;
            $keluar = $result->total_keluar ?? 0;

            $summary = [
                'saldoAwal' => $masuk - $keluar,
                'totalMasukSebelum' => $masuk,
                'totalKeluarSebelum' => $keluar,
            ];
        }

        // transaksi
        $transaksiEduPay = collect();

        if ($this->ms_siswa_id) {
            $transaksiEduPay = (clone $baseQuery)
                ->when($this->startDate && $this->endDate, fn($q) => $q->whereBetween('tanggal', [
                    $this->startDate . ' 00:00:00',
                    $this->endDate . ' 23:59:59'
                ]))
                ->orderBy('tanggal')
                ->orderBy('ms_transaksi_edupay_id')
                ->get();

            // saldo berjalan
            $saldo = $summary['saldoAwal'];

            $transaksiEduPay = $transaksiEduPay->map(function ($item) use (&$saldo) {
                if ($item->status_transaksi !== 'dibatalkan') {
                    $isMasuk = in_array($item->jenis_transaksi, [
                        'topup tunai',
                        'topup online',
                        'pengembalian dana',
                    ]);

                    $saldo += $isMasuk
                        ? $item->nominal
                        : -$item->nominal;
                }

                $item->saldo = $saldo;

                return $item;
            });
        }

        return view('livewire.keuangan.transaksi-edu-pay-siswa.data-edu-pay', [
            'transaksiEduPay' => $transaksiEduPay,
            ...$summary
        ]);
    }
}
