<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Http\Controllers\HelperController;
use App\Models\KuitansiTransaksiEduPay;
use App\Models\PenempatanSiswa;
use App\Models\TransaksiEduPay;
use App\Models\WhatsAppTransaksiEduPay;
use Carbon\Carbon;
use Livewire\Component;

class DataEduPay extends Component
{
    public $ms_siswa_id;

    public $ms_penempatan_siswa_id;
    public $selectedJenjang = null;

    public $startDate = null;
    public $endDate = null;

    public $selectedJenis = '';     // topup tunai, pembayaran, kantin, dll
    public $search = '';
    public $selectedRekening = '';  // kas/bank (opsional kalau ada kolomnya)

    protected $listeners = [
        'refreshEduPays',
        'siswaSelected'
    ];

    public function updated($property)
    {
        if (in_array($property, ['startDate', 'endDate', 'selectedJenis', 'selectedRekening', 'search'])) {
            $this->emitSelf('$refresh');
        }
    }

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        // Simpan ID siswa yang dipilih
        $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;
        $penempatanSiswa = PenempatanSiswa::with('ms_siswa', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($ms_penempatan_siswa_id);

        $this->ms_siswa_id = $penempatanSiswa->ms_siswa_id;
        $this->selectedJenjang = $penempatanSiswa->ms_jenjang_id;

        // Emit refresh agar data di render diperbaruip
        $this->emitSelf('$refresh');
    }
    
    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
    }

    public function refreshEduPays()
    {
        $this->emitSelf('$refresh');
    }

    public function resetTanggal()
    {
        // $this->startDate = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    protected function baseQuery()
    {
        return TransaksiEduPay::query()
            ->with('ms_pengguna')
            ->where('user_type', 'siswa')
            ->where('user_id', $this->ms_siswa_id);
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
        $url = route('transaksi.edupay-siswa.kuitansiPDF', [
            'eduPayId' => $eduPayId,
            'selectedJenjang' => $this->selectedJenjang,
            'userId' => $this->ms_siswa_id
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $saldoAwal = 0;
        $totalMasukSebelum = 0;
        $totalKeluarSebelum = 0;

        if ($this->ms_siswa_id && $this->startDate) {

            $baseSebelum = $this->baseQuery()
                ->when(
                    $this->selectedRekening,
                    fn($q) =>
                    $q->where('rekening_id', $this->selectedRekening)
                );

            $totalMasukSebelum = (clone $baseSebelum)
                ->whereDate('tanggal', '<', $this->startDate)
                ->whereIn('jenis_transaksi', ['topup tunai', 'topup online', 'pengembalian dana'])
                ->sum('nominal');

            $totalKeluarSebelum = (clone $baseSebelum)
                ->whereDate('tanggal', '<', $this->startDate)
                ->whereIn('jenis_transaksi', ['penarikan', 'pembayaran', 'kantin'])
                ->sum('nominal');

            $saldoAwal = $totalMasukSebelum - $totalKeluarSebelum;
        }

        $saldo = $saldoAwal;

        $transaksiEduPay = $this->ms_siswa_id
            ? $this->baseQuery()
            ->when($this->startDate && $this->endDate, function ($q) {
                $startDate = Carbon::parse($this->startDate)->startOfDay();
                $endDate   = Carbon::parse($this->endDate)->endOfDay();

                $q->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->when(
                $this->selectedJenis,
                fn($q) =>
                $q->where('jenis_transaksi', $this->selectedJenis)
            )
            ->when(
                $this->selectedRekening,
                fn($q) =>
                $q->where('rekening_id', $this->selectedRekening)
            )
            ->when(
                $this->search,
                fn($q) =>
                $q->where(function ($sub) {
                    $sub->where('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhereHas(
                            'ms_pengguna',
                            fn($u) =>
                            $u->where('nama', 'like', '%' . $this->search . '%')
                        );
                })
            )
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->map(function ($item) use (&$saldo) {
                if (in_array($item->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
                    $saldo += $item->nominal;
                } else {
                    $saldo -= $item->nominal;
                }
                $item->saldo = $saldo;
                return $item;
            })
            : collect();

        return view('livewire.transaksi-edu-pay-siswa.data-edu-pay', [
            'transaksiEduPay' => $transaksiEduPay,
            'saldoAwal' => $saldoAwal,
            'totalMasukSebelum' => $totalMasukSebelum,
            'totalKeluarSebelum' => $totalKeluarSebelum,
        ]);
    }
}
