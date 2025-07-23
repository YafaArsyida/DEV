<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TabunganSiswa;
use Livewire\Component;

class Delete extends Component
{
    public $ms_tabungan_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    protected $listeners = [
        'confirmDelete'
    ];

    public function confirmDelete($ms_tabungan_siswa_id)
    {
        // Ambil transaksi berdasarkan ID
        $transaksi = TabunganSiswa::findOrFail($ms_tabungan_siswa_id);

        // Atur properti jenjang dan tahun ajar berdasarkan penempatan siswa
        $penempatanSiswa = $transaksi->ms_penempatan_siswa;
        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id ?? null;

        // Simpan ID tabungan untuk dihapus
        $this->ms_tabungan_siswa_id = $ms_tabungan_siswa_id;
    }

    public function deleteTabungan()
    {
        try {
            // Validasi apakah ID tabungan ada
            if (!$this->ms_tabungan_siswa_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
                return;
            }

            // Ambil data transaksi berdasarkan ID
            $transaksi = TabunganSiswa::find($this->ms_tabungan_siswa_id);

            if (!$transaksi) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
                return;
            }

            // Hitung saldo siswa saat ini
            $saldoSaatIni = $transaksi->ms_siswa->saldo_tabungan_siswa();

            // Hitung saldo setelah penghapusan transaksi
            $saldoSetelahHapus = $saldoSaatIni - ($transaksi->jenis_transaksi === 'setoran' ? $transaksi->nominal : -$transaksi->nominal);

            // Validasi apakah saldo menjadi negatif setelah penghapusan
            if ($saldoSetelahHapus < 0) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal! Penghapusan tidak dapat dilakukan karena saldo telah digunakan.']);
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

            // Emit event untuk memperbarui data di tabel
            $this->emit('refreshTabungans');
            $this->emit('refreshSaldo');
            $this->emit('refreshTabunganSiswa');
            $this->emit('tagihanUpdated');
            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteTabungan']);

            // Berikan notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi berhasil dihapus.']);
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.transaksi-tabungan-siswa.delete');
    }
}
