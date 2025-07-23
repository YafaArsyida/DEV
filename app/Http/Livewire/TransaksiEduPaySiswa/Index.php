<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\EduPaySiswa;
use App\Models\KuitansiEduPaySiswa;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\WhatsAppEduPaySiswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $ms_penempatan_siswa_id;

    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $ms_siswa_id = null, $nama_siswa = null, $nama_kelas = null, $nisn = null, $tanggal_lahir = null, $alamat = null, $telepon = null;

    public $saldo_edupay_siswa;
    public $total_pemasukan_edupay_siswa;
    public $total_penarikan_edupay_siswa;
    public $total_pembayaran_edupay_siswa;
    public $total_pengeluaran_edupay_siswa;

    // save topup
    public $nominal_topup;
    public $deskripsi_topup;

    // save penarikan
    public $nominal_penarikan;
    public $deskripsi_penarikan;

    protected $listeners = [
        'showEduPay',
        'refreshEduPays'
    ];

    public function refreshEduPays()
    {
        // Refresh saldo siswa
        $siswa = Siswa::find($this->ms_siswa_id);
        $this->saldo_edupay_siswa = $siswa->saldo_edupay_siswa(); // Menghitung saldo secara dinamis
        $this->total_pemasukan_edupay_siswa = $siswa->total_pemasukan_edupay_siswa();
        $this->total_penarikan_edupay_siswa = $siswa->total_penarikan_edupay_siswa();
        $this->total_pembayaran_edupay_siswa = $siswa->total_pembayaran_edupay_siswa();
        $this->total_pengeluaran_edupay_siswa = $siswa->total_pengeluaran_edupay_siswa();
    }

    public function showEduPay($params)
    {
        $this->ms_jenjang_id = $params['jenjang'];
        $this->ms_tahun_ajar_id = $params['tahunAjar'];

        $this->ms_penempatan_siswa_id = $params['ms_penempatan_siswa_id'];
        $this->ms_siswa_id = $params['ms_siswa_id'];

        // Mengambil detail siswa berdasarkan ms_siswa_id
        $siswa = PenempatanSiswa::with('ms_siswa', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($this->ms_penempatan_siswa_id);

        $this->nama_siswa = $siswa->ms_siswa->nama_siswa;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas;
        $this->nisn = $siswa->ms_siswa->nisn;
        $this->tanggal_lahir = $siswa->ms_siswa->tanggal_lahir;
        $this->alamat = $siswa->ms_siswa->alamat;
        $this->telepon = $siswa->ms_siswa->telepon;

        // Hitung saldo tabungan siswa
        $this->saldo_edupay_siswa = $siswa->ms_siswa->saldo_edupay_siswa(); // Menghitung saldo secara dinamis
        $this->total_pemasukan_edupay_siswa = $siswa->ms_siswa->total_pemasukan_edupay_siswa();
        $this->total_pengeluaran_edupay_siswa = $siswa->ms_siswa->total_pengeluaran_edupay_siswa();
    }

    public function simpanTopUp()
    {
        try {
            // Pastikan siswa dipilih
            if (!$this->ms_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih siswa terlebih dahulu.']);
                return;
            }
            if (!$this->ms_penempatan_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih PENEMPATAN terlebih dahulu.']);
                return;
            }

            // Validasi input untuk topup
            $validatedData = $this->validate([
                'nominal_topup' => 'required|numeric|min:1000',
                'deskripsi_topup' => 'nullable|string|max:255',
            ], [
                'nominal_topup.required' => 'Nominal top-up harus diisi.',
                'nominal_topup.numeric' => 'Nominal top-up harus berupa angka.',
                'nominal_topup.min' => 'Nominal top-up harus minimal 1000.',
                'deskripsi_topup.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_edupay = 22002;
            $deskripsiJurnal = "Top Up Tunai EduPay Rp {$this->nominal_topup} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'debit',
                'nominal' => $this->nominal_topup,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data untuk jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_edupay,
                'posisi' => 'kredit',
                'nominal' => $this->nominal_topup,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi topup
            EduPaySiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'topup tunai', // Jenis transaksi untuk top-up
                'nominal' => $this->nominal_topup,
                'deskripsi' => $this->deskripsi_topup,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);

            // Reset input
            $this->reset(['nominal_topup', 'deskripsi_topup']);

            // Refresh saldo siswa EduPay
            $siswa = Siswa::find($this->ms_siswa_id);
            $this->saldo_edupay_siswa = $siswa->saldo_edupay_siswa(); // Menghitung saldo secara dinamis
            $this->total_pemasukan_edupay_siswa = $siswa->total_pemasukan_edupay_siswa();
            $this->total_penarikan_edupay_siswa = $siswa->total_penarikan_edupay_siswa();
            $this->total_pembayaran_edupay_siswa = $siswa->total_pembayaran_edupay_siswa();
            $this->total_pengeluaran_edupay_siswa = $siswa->total_pengeluaran_edupay_siswa();

            // Notifikasi sukses
            $this->emit('tagihanUpdated');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi top-up berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function simpanPengeluaran()
    {
        try {
            // Pastikan siswa dipilih
            if (!$this->ms_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih siswa terlebih dahulu.']);
                return;
            }
            if (!$this->ms_penempatan_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih PENEMPATAN terlebih dahulu.']);
                return;
            }

            // Validasi input untuk pengeluaran
            $validatedData = $this->validate([
                'nominal_penarikan' => 'required|numeric|min:1000',
                'deskripsi_penarikan' => 'nullable|string|max:255',
            ], [
                'nominal_penarikan.required' => 'Nominal tarik tunai harus diisi.',
                'nominal_penarikan.numeric' => 'Nominal tarik tunai harus berupa angka.',
                'nominal_penarikan.min' => 'Nominal tarik tunai harus minimal 1000.',
                'deskripsi_penarikan.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            // Periksa saldo siswa saat ini
            $siswa = Siswa::find($this->ms_siswa_id);
            $saldo_sekarang = $siswa->saldo_edupay_siswa(); // Saldo dihitung secara dinamis dari saldo EduPay

            // Periksa apakah saldo cukup
            if ($this->nominal_penarikan > $saldo_sekarang) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo tidak mencukupi untuk transaksi ini.']);
                return;
            }

            $ms_pengguna_id = Auth::id();

            $kode_rekening_kas = 11001;
            $kode_rekening_edupay = 22002;

            $deskripsiJurnal = "Penarikan Tunai EduPay Rp {$this->nominal_penarikan} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_edupay,
                'posisi' => 'debit',
                'nominal' => $this->nominal_penarikan,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data untuk jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'kredit',
                'nominal' => $this->nominal_penarikan,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi pengeluaran
            EduPaySiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'penarikan', // Jenis transaksi untuk tarik tunai
                'nominal' => $this->nominal_penarikan,
                'deskripsi' => $this->deskripsi_penarikan,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);

            // Reset input
            $this->reset(['nominal_penarikan', 'deskripsi_penarikan']);

            // Refresh saldo siswa
            $this->saldo_edupay_siswa = $siswa->saldo_edupay_siswa(); // Menghitung saldo terkini
            $this->total_pemasukan_edupay_siswa = $siswa->total_pemasukan_edupay_siswa();
            $this->total_penarikan_edupay_siswa = $siswa->total_penarikan_edupay_siswa();
            $this->total_pembayaran_edupay_siswa = $siswa->total_pembayaran_edupay_siswa();
            $this->total_pengeluaran_edupay_siswa = $siswa->total_pengeluaran_edupay_siswa();

            // Notifikasi sukses
            $this->emit('tagihanUpdated');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tarik tunai berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
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
                case 'pengembalian dana':
                case 'topup':
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

        // Ambil template pesan dari PesanTransaksiEduPay
        $templatePesan = WhatsAppEduPaySiswa::where('ms_jenjang_id', $this->ms_jenjang_id)
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

    // Fungsi untuk menangani tombol cetak
    public function cetakTransaksi($eduPayId)
    {
        $surat = KuitansiEduPaySiswa::where('ms_jenjang_id', $this->ms_jenjang_id)->first();

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
            'selectedJenjang' => $this->ms_jenjang_id,
            'eduPayId' => $eduPayId,
            'selectedSiswa' => $this->ms_siswa_id
        ]);

        // Emit URL untuk membuka tab baru
        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $saldo = 0; // Inisialisasi di luar closure

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

        return view('livewire.transaksi-edu-pay-siswa.index', [
            'transaksiEduPay' => $transaksiEduPay,
        ]);
    }
}
