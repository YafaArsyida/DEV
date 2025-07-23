<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Http\Controllers\HelperController;
use App\Models\AkuntansiJurnalDetail;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TabunganSiswa;
use App\Models\WhatsAppHistoriTabunganSiswa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $ms_penempatan_siswa_id;

    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $ms_siswa_id = null, $nama_siswa = null, $nisn = null, $tanggal_lahir = null, $alamat = null, $telepon = null;

    public $saldo_tabungan_siswa;
    public $total_kredit_tabungan;
    public $total_debit_tabungan;

    // save kredit
    public $nominal_kredit;
    public $deskripsi_kredit;

    // save debit
    public $nominal_debit;
    public $deskripsi_debit;

    protected $listeners = [
        'showTabungan',
        'refreshTabungans'
    ];

    public function refreshTabungans()
    {
        // Refresh saldo siswa
        $siswa = Siswa::find($this->ms_siswa_id);
        $this->saldo_tabungan_siswa = $siswa->saldo_tabungan_siswa(); // Menghitung saldo secara dinamis
        $this->total_kredit_tabungan = $siswa->total_kredit_tabungan();
        $this->total_debit_tabungan = $siswa->total_debit_tabungan();
    }

    public function showTabungan($ms_siswa_id, $ms_penempatan_siswa_id)
    {
        try {
            // Mengambil detail siswa berdasarkan ms_siswa_id
            $siswa = Siswa::findOrFail($ms_siswa_id);

            // Mengambil detail penempatan siswa berdasarkan ms_penempatan_siswa_id
            $penempatanSiswa = PenempatanSiswa::where('ms_penempatan_siswa_id', $ms_penempatan_siswa_id)->firstOrFail();

            // Mengisi properti dengan data penempatan siswa
            $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id;
            $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id;
            $this->ms_penempatan_siswa_id = $ms_penempatan_siswa_id;

            // Mengisi properti dengan data siswa
            $this->ms_siswa_id = $ms_siswa_id;

            $this->nama_siswa = $siswa->nama_siswa;
            $this->nisn = $siswa->nisn;
            $this->tanggal_lahir = $siswa->tanggal_lahir;
            $this->alamat = $siswa->alamat;
            $this->telepon = $siswa->telepon;

            // Menghitung saldo dan riwayat tabungan siswa
            $this->saldo_tabungan_siswa = $siswa->saldo_tabungan_siswa();
            $this->total_kredit_tabungan = $siswa->total_kredit_tabungan();
            $this->total_debit_tabungan = $siswa->total_debit_tabungan();

            // Emit event untuk memperbarui tampilan tabungan siswa
            $this->emit('tabunganUpdated');
            $this->emit('refreshSaldo');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Penanganan jika siswa atau penempatan siswa tidak ditemukan
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Data siswa atau penempatan siswa tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            // Penanganan umum untuk kesalahan lainnya
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    // kredit
    public function simpanKredit()
    {
        try {
            // Pastikan siswa dipilih
            if (!$this->ms_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih siswa terlebih dahulu.']);
                return;
            }

            // Validasi input untuk kredit
            $validatedData = $this->validate([
                'nominal_kredit' => 'required|numeric|min:1000',
                'deskripsi_kredit' => 'nullable|string|max:55',
            ], [
                'nominal_kredit.required' => 'Nominal kredit harus diisi.',
                'nominal_kredit.numeric' => 'Nominal kredit harus berupa angka.',
                'nominal_kredit.min' => 'Nominal kredit harus minimal 1.000',
                'deskripsi_kredit.max' => 'Deskripsi tidak boleh lebih dari 50 karakter.',
            ]);

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_tabungan = 22001;

            $deskripsiJurnal = "Setoran Tunai Tabungan Rp {$this->nominal_kredit} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_kas,
                'posisi' => 'debit',
                'nominal' => $this->nominal_kredit,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data untuk jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_tabungan,
                'posisi' => 'kredit',
                'nominal' => $this->nominal_kredit,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            // Simpan transaksi kredit
            TabunganSiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'setoran', // Jenis transaksi untuk kredit
                'nominal' => $this->nominal_kredit,
                'deskripsi' => $this->deskripsi_kredit,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);

            // Reset input
            $this->reset(['nominal_kredit', 'deskripsi_kredit']);

            // Refresh saldo siswa
            $siswa = Siswa::find($this->ms_siswa_id);
            $this->saldo_tabungan_siswa = $siswa->saldo_tabungan_siswa(); // Menghitung saldo secara dinamis
            $this->total_kredit_tabungan = $siswa->total_kredit_tabungan();
            $this->total_debit_tabungan = $siswa->total_debit_tabungan();

            // Notifikasi sukses
            $this->emit('tagihanUpdated');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi kredit berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function simpanDebit()
    {
        try {
            // Pastikan siswa dipilih
            if (!$this->ms_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Silakan pilih siswa terlebih dahulu.']);
                return;
            }

            // Validasi input untuk debit
            $validatedData = $this->validate([
                'nominal_debit' => 'required|numeric|min:1000',
                'deskripsi_debit' => 'nullable|string|max:255',
            ], [
                'nominal_debit.required' => 'Nominal debit harus diisi.',
                'nominal_debit.numeric' => 'Nominal debit harus berupa angka.',
                'nominal_debit.min' => 'Nominal debit harus minimal 1000.',
                'deskripsi_debit.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
            ]);

            // Periksa saldo siswa saat ini
            $siswa = Siswa::find($this->ms_siswa_id);
            $saldo_sekarang = $siswa->saldo_tabungan_siswa(); // Saldo dihitung secara dinamis

            // Periksa apakah saldo cukup
            if ($this->nominal_debit > $saldo_sekarang) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Saldo tidak mencukupi untuk transaksi ini.']);
                return;
            }

            $ms_pengguna_id = Auth::id();

            // simpan jurnal
            $kode_rekening_kas = 11001;
            $kode_rekening_tabungan = 22001;
            $deskripsiJurnal = "Penarikan Tunai Tabungan Rp {$this->nominal_debit} siswa {$this->nama_siswa}";

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_tabungan,
                'posisi' => 'debit',
                'nominal' => $this->nominal_debit,
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
                'nominal' => $this->nominal_debit,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'is_canceled' => 'active',
                'deskripsi' => $deskripsiJurnal,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            TabunganSiswa::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'jenis_transaksi' => 'penarikan', // Jenis transaksi untuk debit
                'nominal' => $this->nominal_debit,
                'deskripsi' => $this->deskripsi_debit,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                'tanggal' => now(),
            ]);

            // Reset input
            $this->reset(['nominal_debit', 'deskripsi_debit']);

            // Refresh saldo siswa
            $this->saldo_tabungan_siswa = $siswa->saldo_tabungan_siswa(); // Menghitung saldo terkini
            $this->total_kredit_tabungan = $siswa->total_kredit_tabungan();
            $this->total_debit_tabungan = $siswa->total_debit_tabungan();

            // Notifikasi sukses
            $this->emit('tagihanUpdated');
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi debit berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function kirimWhatsapp($tabunganId)
    {
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
        $templatePesan = WhatsAppHistoriTabunganSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)->first();

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
        $saldo = 0; // Letakkan di luar closure

        $transaksiTabunganSiswa = $this->ms_siswa_id
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

        return view('livewire.transaksi-tabungan-siswa.index', [
            'transaksiTabungan' => $transaksiTabunganSiswa,
        ]);
    }
}
