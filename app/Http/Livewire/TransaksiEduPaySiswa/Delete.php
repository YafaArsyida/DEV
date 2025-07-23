<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\EduPaySiswa;
use Livewire\Component;

class Delete extends Component
{
    public $ms_edupay_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'confirmDeleteEduPay'
    ];

    public function confirmDeleteEduPay($ms_edupay_siswa_id)
    {
        $transaksi = EduPaySiswa::findOrFail($ms_edupay_siswa_id);

        $penempatanSiswa = $transaksi->ms_penempatan_siswa;
        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id ?? null;

        $this->ms_edupay_siswa_id = $ms_edupay_siswa_id;
    }

    public function deleteEduPay()
    {
        try {
            // Validasi apakah ID EduPay ada
            if (!$this->ms_edupay_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan.']);
                return;
            }

            // Ambil data transaksi berdasarkan ID
            $transaksi = EduPaySiswa::find($this->ms_edupay_siswa_id);

            if (!$transaksi) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi EduPay tidak ditemukan.']);
                return;
            }

            // Hitung saldo EduPay siswa saat ini
            $saldoSaatIni = $transaksi->ms_siswa->saldo_edupay_siswa();

            // Hitung saldo setelah penghapusan transaksi
            if (in_array($transaksi->jenis_transaksi, ['topup', 'topup online', 'pengembalian dana'])) {
                // Untuk jenis transaksi yang mengurangi saldo
                $saldoSetelahHapus = $saldoSaatIni - $transaksi->nominal;
            } elseif (in_array($transaksi->jenis_transaksi, ['penarikan', 'pembayaran'])) {
                // Untuk jenis transaksi yang menambah saldo
                $saldoSetelahHapus = $saldoSaatIni + $transaksi->nominal;
            } else {
                // Jika jenis transaksi tidak dikenali, tetap menggunakan saldo saat ini
                $saldoSetelahHapus = $saldoSaatIni;
            }
            // Validasi apakah saldo menjadi negatif setelah penghapusan
            if ($saldoSetelahHapus < 0) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal! saldo telah digunakan.' . $saldoSaatIni . ' batas ' . $saldoSetelahHapus]);
                return;
            }

            // Tambahkan log di deskripsi transaksi sebelum penghapusan
            $transaksi->deskripsi = $transaksi->deskripsi . " (Dihapus oleh petugas {$this->nama_petugas})";
            $transaksi->save();

            // Dapatkan ID jurnal terkait
            $jurnalIds = [
                $transaksi->akuntansi_jurnal_detail_debit_id,
                $transaksi->akuntansi_jurnal_detail_kredit_id,
            ];

            // Validasi dan soft delete jurnal
            AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)->get()->each(function ($jurnal) {
                $jurnal->delete();
            });

            // Hapus transaksi
            $transaksi->delete();

            // Emit event untuk memperbarui data di tabel
            $this->emit('refreshEduPays');
            $this->emit('tagihanUpdated');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteEduPay']);

            // Berikan notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi EduPay berhasil dihapus.']);
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-edu-pay-siswa.delete');
    }
}
