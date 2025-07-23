<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

use App\Models\PenempatanSiswa;
use App\Models\Tabungan;
use App\Models\TabunganSiswa;

class Withdraw extends Component
{
    protected $listeners = ['withdrawTabunganSiswa'];

    public $selectedKelas; // Untuk menyimpan data kelas yang diterima
    public $siswa;
    public $saldoKas;
    public $totalSaldoTabungan;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public function withdrawTabunganSiswa($data)
    {
        $this->selectedKelas = $data['selectedKelas'] ?? null;
        $this->selectedJenjang = $data['selectedJenjang'] ?? null;
        $this->selectedTahunAjar = $data['selectedTahunAjar'] ?? null;

        // Lakukan validasi atau proses berdasarkan parameter
        if (!$this->selectedKelas || !$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Data kelas, jenjang, atau tahun ajar tidak valid.'
            ]);
            return;
        }

        // Ambil siswa berdasarkan kelas
        $this->siswa = PenempatanSiswa::where('ms_kelas_id', $this->selectedKelas)
            ->whereHas('ms_siswa.ms_tabungan_siswa', function (Builder $query) {
                $query->where('jenis_transaksi', '!=', 'penarikan') // Pastikan transaksi bukan penarikan
                    ->where('nominal', '>', 0); // Pastikan saldo positif
            })
            ->with(['ms_siswa'])
            ->get();

        // Jika tidak ada siswa dengan saldo
        if ($this->siswa->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Tidak ada saldo siswa di kelas ini.'
            ]);
            return;
        }

        // Hitung total saldo yang akan ditarik menggunakan saldo_tabungan_siswa
        $this->totalSaldoTabungan = $this->siswa->reduce(function ($carry, $siswa) {
            return $carry + $siswa->ms_siswa->saldo_tabungan_siswa();
        }, 0);

        // Cek saldo kas
        $this->saldoKas = AkuntansiJurnalDetail::where('kode_rekening', 101) // Rekening kas
            ->where('posisi', 'debit') // Saldo masuk
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->sum('nominal') - AkuntansiJurnalDetail::where('kode_rekening', 101) // Rekening kas
            ->where('posisi', 'kredit') // Saldo keluar
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->sum('nominal');

        if ($this->saldoKas < $this->totalSaldoTabungan) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Saldo kas tidak mencukupi untuk penarikan.'
            ]);
            return;
        }

        // Jika saldo mencukupi, lanjutkan dengan proses penarikan
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => "Saldo siswa tersedia. Total penarikan: Rp " . number_format($this->totalSaldoTabungan, 0, ',', '.') . "."
            // 'message' => "Saldo siswa tersedia untuk {$this->siswa->count()} siswa. Total penarikan: Rp " . number_format($this->totalSaldoTabungan, 0, ',', '.') . "."
        ]);
    }

    public function confirmWithdraw()
    {
        if ($this->saldoKas < $this->totalSaldoTabungan) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Saldo kas tidak mencukupi untuk penarikan.'
            ]);
            return;
        }
        if (!$this->siswa || $this->siswa->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak ada siswa untuk diproses.']);
            return;
        }
        try {
            foreach ($this->siswa as $student) {
                $saldoSekarang = $student->ms_siswa->saldo_tabungan_siswa();

                // Cek jika saldo positif
                if ($saldoSekarang > 0) {
                    // jurnal ges
                    $kode_kas = 101;
                    $kode_saldo_tabungan_siswa = 203;

                    // Data untuk jurnal debit
                    $jurnalDebit = [
                        'kode_rekening' => $kode_saldo_tabungan_siswa,
                        'posisi' => 'debit',
                        'nominal' => $saldoSekarang,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $student->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $student->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => "Penarikan Tunai tabungan Rp {$saldoSekarang} siswa {$student->ms_siswa->nama_siswa}",
                    ];
                    $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

                    // Data untuk jurnal kredit
                    $jurnalKredit = [
                        'kode_rekening' => $kode_kas,
                        'posisi' => 'kredit',
                        'nominal' => $saldoSekarang,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $student->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $student->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => "Penarikan Tunai tabungan Rp {$saldoSekarang} siswa {$student->ms_siswa->nama_siswa}",
                    ];
                    $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

                    // Simpan transaksi penarikan ke tabel ms_tabungan_siswa
                    TabunganSiswa::create([
                        'ms_penempatan_siswa_id' => $student->ms_penempatan_siswa_id,
                        'ms_siswa_id' => $student->ms_siswa->ms_siswa_id,
                        'ms_pengguna_id' => auth()->id(), // ID pengguna saat ini
                        'jenis_transaksi' => 'penarikan',
                        'nominal' => $saldoSekarang,
                        'deskripsi' => 'Penarikan saldo tabungan',
                        'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                        'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                        'tanggal' => now(),
                    ]);
                }
            }
            $this->emit('refreshSaldo');
            $this->emit('refreshSaldoTabunganSiswa'); // Emit event ke komponen Livewire terkait
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Saldo berhasil dikosongkan untuk semua siswa.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.laporan-tabungan-siswa.withdraw');
    }
}
