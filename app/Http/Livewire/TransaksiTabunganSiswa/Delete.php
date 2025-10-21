<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TabunganSiswa;
use App\Models\TransaksiTabungan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    public $ms_transaksi_tabungan_id;

    protected $listeners = [
        'confirmDeleteTabungan'
    ];

    public function confirmDeleteTabungan($ms_transaksi_tabungan_id)
    {
        $this->ms_transaksi_tabungan_id = $ms_transaksi_tabungan_id;
    }

    public function deleteTabungan()
    {
        DB::beginTransaction();

        try {
            // Validasi apakah ID tabungan ada
            if (!$this->ms_transaksi_tabungan_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
                return;
            }

            // Ambil data transaksi berdasarkan ID
            $transaksi = TransaksiTabungan::find($this->ms_transaksi_tabungan_id);

            if (!$transaksi) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
                return;
            }

            if ($transaksi->user_type == 'siswa') {
                $saldoSaatIni = $transaksi->ms_siswa->saldo_tabungan_siswa();
            } elseif ($transaksi->user_type == 'pegawai') {
                $saldoSaatIni = $transaksi->ms_pegawai->saldo_tabungan_pegawai();
            } else {
                // default 0 atau error
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'User tidak ditemukan']);
            }

            // Hitung saldo setelah penghapusan transaksi
            $saldoSetelahHapus = $saldoSaatIni - ($transaksi->jenis_transaksi === 'setoran' ? $transaksi->nominal : -$transaksi->nominal);

            // Validasi apakah saldo menjadi negatif setelah penghapusan
            if ($saldoSetelahHapus < 0) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => "Gagal! saldo telah digunakan. Saldo saat ini {$saldoSaatIni}, batas {$saldoSetelahHapus}"
                ]);
                return;
            }

            // Tambahkan log di deskripsi transaksi sebelum penghapusan
            $transaksi->deskripsi = $transaksi->deskripsi . ' (Dihapus oleh petugas ID: ' . auth()->id() . ')';
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

            DB::commit();

            // Emit event untuk memperbarui data di tabel
            $this->emit('refreshTabungans');
            $this->emit('refreshSaldo');
            $this->emit('refreshTabunganSiswa');
            $this->emit('tagihanUpdated');
            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteTabungan']);

            // Berikan notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Notifikasi error jika terjadi kesalahan
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.transaksi-tabungan-siswa.delete');
    }
}
