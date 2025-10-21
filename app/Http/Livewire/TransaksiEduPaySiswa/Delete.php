<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiEduPay;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    public $ms_transaksi_edupay_id;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'confirmDeleteEduPay'
    ];

    public function confirmDeleteEduPay($ms_transaksi_edupay_id)
    {
        $this->ms_transaksi_edupay_id = $ms_transaksi_edupay_id;
    }

    public function deleteEduPay()
    {
        DB::beginTransaction();

        try {
            if (!$this->ms_transaksi_edupay_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan.']);
                return;
            }

            $transaksi = TransaksiEduPay::find($this->ms_transaksi_edupay_id);

            if (!$transaksi) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan.']);
                return;
            }
            if ($transaksi->user_type == 'siswa') {
                $saldoSaatIni = $transaksi->ms_siswa->saldo_edupay_siswa();
            } elseif ($transaksi->user_type == 'pegawai') {
                $saldoSaatIni = $transaksi->ms_pegawai->saldo_edupay_pegawai();
            } else {
                // default 0 atau error
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'User tidak ditemukan']);
            }

            // Hitung saldo setelah penghapusan
            if (in_array($transaksi->jenis_transaksi, ['topup tunai', 'topup online', 'pengembalian dana'])) {
                $saldoSetelahHapus = $saldoSaatIni - $transaksi->nominal;
            } elseif (in_array($transaksi->jenis_transaksi, ['penarikan', 'pembayaran', 'kantin'])) {
                $saldoSetelahHapus = $saldoSaatIni + $transaksi->nominal;
            } else {
                $saldoSetelahHapus = $saldoSaatIni;
            }

            if ($saldoSetelahHapus < 0) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => "Gagal! saldo telah digunakan. Saldo saat ini {$saldoSaatIni}, batas {$saldoSetelahHapus}"
                ]);
                return;
            }

            // Update deskripsi transaksi
            $transaksi->deskripsi .= " (Dihapus oleh petugas {$this->nama_petugas})";
            $transaksi->save();

            // Hapus jurnal terkait
            $jurnalIds = [
                $transaksi->akuntansi_jurnal_detail_debit_id,
                $transaksi->akuntansi_jurnal_detail_kredit_id,
            ];

            AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)->get()->each->delete();

            // Hapus transaksi
            $transaksi->delete();

            // Commit transaksi
            DB::commit();

            // Refresh UI
            $this->emit('refreshEduPays');
            $this->emit('tagihanUpdated');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteEduPay']);
           
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi EduPay berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-edu-pay-siswa.delete');
    }
}
