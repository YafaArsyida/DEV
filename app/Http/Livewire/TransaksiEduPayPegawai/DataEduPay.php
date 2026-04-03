<?php

namespace App\Http\Livewire\TransaksiEduPayPegawai;

use App\Http\Controllers\HelperController;
use App\Models\KuitansiTransaksiEduPay;
use App\Models\Pegawai;
use App\Models\TransaksiEduPay;
use App\Models\WhatsAppTransaksiEduPay;
use Carbon\Carbon;
use Livewire\Component;

class DataEduPay extends Component
{
    public $ms_pegawai_id;

    public $selectedJenjang = null;

    public $startDate = null;
    public $endDate = null;

    public $selectedJenis = '';     // topup tunai, pembayaran, kantin, dll
    public $search = '';

    protected $listeners = [
        'refreshEduPays',
        'pegawaiSelected'
    ];

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


    public function pegawaiSelected($ms_pegawai_id)
    {
        $pegawai = Pegawai::with('ms_jabatan', 'ms_educard')
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

    public function kirimWhatsapp($edupayId)
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pesan sedang diproses.']);

        // Ambil transaksi berdasarkan ID
        $targetTransaksi = TransaksiEduPay::with(['ms_pegawai', 'ms_pegawai.ms_jabatan', 'ms_pengguna'])
            ->where('user_type', 'pegawai')
            ->where('ms_transaksi_edupay_id', $edupayId)
            ->first();

        if (!$targetTransaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan']);
            return;
        }

        // Ambil semua transaksi pegawai untuk hitung saldo
        $edupayTransaksi = TransaksiEduPay::where('user_id', $this->ms_pegawai_id)
            // ->orderBy('ms_transaksi_edupay_id', 'ASC')
            ->get();

        // Hitung saldo sampai transaksi yang diminta
        $saldo = 0;
        foreach ($edupayTransaksi as $transaksi) {
            switch ($transaksi->jenis_transaksi) {
                case 'topup tunai':
                case 'topup online':
                case 'pengembalian dana':
                    $saldo += $transaksi->nominal;
                    break;
                case 'penarikan':
                case 'pembayaran':
                case 'kantin':
                    $saldo -= $transaksi->nominal;
                    break;
            }

            if ($transaksi->ms_transaksi_edupay_id === $edupayId) {
                break;
            }
        }

        // Ambil nomor telepon 
        $telepon = $targetTransaksi->ms_pegawai->telepon;
        if (!$telepon) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Nomor telepon tidak ditemukan']);
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
        $pesan .= "Kami informasikan bahwa *Transaksi EduPay* atas nama pegawai *" . $targetTransaksi->ms_pegawai->nama_pegawai . "* telah berhasil. Berikut adalah rincian transaksinya:\n\n";
        $pesan .= "*" . $jenisTransaksi . " : Rp" . number_format($targetTransaksi->nominal, 0, ',', '.') . "*\n";
        $pesan .= "*Saldo EduPay : Rp" . number_format($saldo, 0, ',', '.') . "*";
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
        $url = route('transaksi.edupay-pegawai.kuitansiPDF', [
            'eduPayId' => $eduPayId,
            'selectedJenjang' => $this->selectedJenjang,
            'userId' => $this->ms_pegawai_id
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
    }

    protected function baseQuery()
    {
        return TransaksiEduPay::query()
            ->with('ms_pengguna')
            ->where('user_type', 'pegawai')
            ->where('user_id', $this->ms_pegawai_id);
    }

    public function render()
    {
        $saldoAwal = 0;
        $totalMasukSebelum = 0;
        $totalKeluarSebelum = 0;

        if ($this->ms_pegawai_id && $this->startDate) {

            $baseSebelum = $this->baseQuery();

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

        $transaksiEduPay = $this->ms_pegawai_id
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

        return view('livewire.transaksi-edu-pay-pegawai.data-edu-pay', [
            'transaksiEduPay' => $transaksiEduPay,
            'saldoAwal' => $saldoAwal,
            'totalMasukSebelum' => $totalMasukSebelum,
            'totalKeluarSebelum' => $totalKeluarSebelum,
        ]);
    }
}
