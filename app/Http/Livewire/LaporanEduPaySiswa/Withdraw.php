<?php

namespace App\Http\Livewire\LaporanEduPaySiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\PenempatanSiswa;
use App\Models\SaldoEduPay;
use App\Models\TransaksiEduPay;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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

        $url = route('laporan.edupay-siswa.saldo.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun'   => $this->selectedTahunAjar,
            'kelas'   => $this->selectedKelas,
        ]);

        $this->arsipDownloaded = true;

        $this->emit('openNewTab', $url);
    }

    public function withdrawEduPaySiswa()
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
                $kodeSaldoEduPay = 22002;

                $berhasil = 0;

                foreach ($students as $student) {

                    $saldoEduPay = SaldoEduPay::siswa()
                        ->lockForUpdate()
                        ->firstOrCreate(
                            [
                                'user_id'   => $student->ms_siswa_id,
                                'user_type' => 'siswa',
                            ],
                            [
                                'saldo_edupay' => 0,
                            ]
                        );

                    $saldo = $saldoEduPay->saldo_edupay;

                    if ($saldo <= 0) {
                        continue;
                    }

                    $deskripsi = sprintf(
                        'Withdraw saldo EduPay akhir tahun ajaran - %s',
                        $student->ms_siswa->nama_siswa
                    );

                    // Jurnal Debit
                    $debit = AkuntansiJurnalDetail::create([
                        'kode_rekening' => $kodeSaldoEduPay,
                        'posisi' => 'debit',
                        'nominal' => $saldo,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $student->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $student->ms_jenjang_id,
                        'ms_departemen_id' => 'SEKOLAH',
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsi,
                    ]);

                    // Jurnal Kredit
                    $kredit = AkuntansiJurnalDetail::create([
                        'kode_rekening' => $kodeKas,
                        'posisi' => 'kredit',
                        'nominal' => $saldo,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $student->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $student->ms_jenjang_id,
                        'ms_departemen_id' => 'SEKOLAH',
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsi,
                    ]);

                    // Simpan transaksi penarikan EduPay
                    TransaksiEduPay::create([
                        'user_type' => 'siswa',
                        'user_id' => $student->ms_siswa_id,
                        'ms_penempatan_siswa_id' => $student->ms_penempatan_siswa_id,
                        'ms_pengguna_id' => auth()->id(),
                        'jenis_transaksi' => 'penarikan',
                        'nominal' => $saldo,
                        'tanggal' => now(),
                        'deskripsi' => $deskripsi,
                        'akuntansi_jurnal_detail_debit_id' => $debit->akuntansi_jurnal_detail_id,
                        'akuntansi_jurnal_detail_kredit_id' => $kredit->akuntansi_jurnal_detail_id,
                    ]);

                    $saldoEduPay->update([
                            'saldo_edupay' => 0
                        ]);

                    $berhasil++;
                }

                $this->dispatchBrowserEvent('hide-modal', [
                    'modalId' => 'WithdrawEduPay'
                ]);

                $this->dispatchBrowserEvent('alertify-success', [
                    'message' => "{$berhasil} siswa berhasil diproses."
                ]);

            });

            // Refresh komponen Livewire
            $this->emit('refreshSaldo');
            $this->emit('refreshIndex');
            $this->emit('refreshOverview');

        } catch (\Throwable $e) {

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan saat melakukan withdraw EduPay.'
            ]);

        }
    }
    
    public function render()
    {
        return view('livewire.laporan-edu-pay-siswa.withdraw');
    }
}
