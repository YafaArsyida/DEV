<?php

namespace App\Http\Livewire\Keuangan\LaporanTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use App\Models\PenempatanSiswa;
use App\Models\SaldoTabungan;
use App\Models\TransaksiTabungan;
use App\Services\AccountingService;

class Withdraw extends Component
{
    public $arsipDownloaded = false;

    public $selectedKelas;
    public $namaKelas;

    public $selectedJenjang;
    public $selectedTahunAjar;

    protected $listeners = [
        'showWithdrawModal',
    ];

    public function showWithdrawModal($data)
    {
        $this->selectedKelas      = $data['kelas'];
        $this->namaKelas          = $data['namaKelas'];
        $this->selectedJenjang    = $data['jenjang'];
        $this->selectedTahunAjar  = $data['tahunAjar'];

        $this->arsipDownloaded = false;

        $this->dispatchBrowserEvent('show-withdraw-modal');
    }

    public function cetakSaldo()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Laporan sedang diproses.'
        ]);

        $url = route('laporan.tabungan-siswa.saldo.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun'   => $this->selectedTahunAjar,
            'kelas'   => $this->selectedKelas,
        ]);

        $this->arsipDownloaded = true;

        $this->emit('openNewTab', $url);
    }

    public function withdrawTabunganSiswa()
    {
        try {

            DB::transaction(function () {

                $query = PenempatanSiswa::with([
                    'ms_siswa'
                ])
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

                if ($this->selectedKelas) {
                    $query->where('ms_kelas_id', $this->selectedKelas);
                }

                $students = $query->get();

                $kodeKas = 11001;
                $kodeSaldoTabungan = 22001;

                $berhasil = 0;

                foreach ($students as $student) {

                    $saldoTabungan = SaldoTabungan::siswa()
                        ->lockForUpdate()
                        ->firstOrCreate(
                            [
                                'user_id'   => $student->ms_siswa_id,
                                'user_type' => 'siswa',
                            ],
                            [
                                'saldo_tabungan' => 0,
                            ]
                        );

                    $saldo = $saldoTabungan->saldo_tabungan;

                    if ($saldo <= 0) {
                        continue;
                    }

                    $deskripsi = sprintf(
                        'Withdraw saldo tabungan siswa %s',
                        $student->ms_siswa->nama_siswa
                    );

                    // =====================================================
                    // BUAT JURNAL
                    // =====================================================
                    $jurnal = AccountingService::create([
                        'tanggal' => now(),
                        'deskripsi' => $deskripsi,
                        'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
                        'ms_tahun_ajaran_id' => $student->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $student->ms_jenjang_id,
                        'ms_departemen_id' => 'SEKOLAH',

                        'detail' => [
                            // Debit Saldo Tabungan
                            [
                                'kode_rekening' => $kodeSaldoTabungan,
                                'posisi' => 'debit',
                                'nominal' => $saldo,
                            ],

                            // Kredit Kas
                            [
                                'kode_rekening' => $kodeKas,
                                'posisi' => 'kredit',
                                'nominal' => $saldo,
                            ],
                        ],
                    ]);

                    // =====================================================
                    // SIMPAN TRANSAKSI TABUNGAN
                    // =====================================================
                    TransaksiTabungan::create([
                        'user_type' => 'siswa',

                        'user_id' => $student->ms_siswa_id,

                        'ms_penempatan_siswa_id' => $student->ms_penempatan_siswa_id,

                        'ms_pengguna_id' => auth()->user()->ms_pengguna_id,

                        'jenis_transaksi' => 'penarikan',

                        'nominal' => $saldo,

                        'tanggal' => now(),

                        'deskripsi' => $deskripsi,

                        'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
                    ]);

                    // =====================================================
                    // KOSONGKAN SALDO TABUNGAN
                    // =====================================================
                    $saldoTabungan->update([
                        'saldo_tabungan' => 0,
                    ]);

                    $berhasil++;
                }

                $this->dispatchBrowserEvent('hide-modal', [
                    'modalId' => 'WithdrawTabungan'
                ]);

                $this->dispatchBrowserEvent('alertify-success', [
                    'message' => "{$berhasil} siswa berhasil diproses."
                ]);

            });

            $this->emit('refreshSaldo');
            $this->emit('refreshIndex');
            $this->emit('refreshOverview');

        } catch (\Throwable $e) {

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan saat melakukan withdraw.'
            ]);

        }
    }

    public function render()
    {
        return view('livewire.keuangan.laporan-tabungan-siswa.withdraw');
    }
}
