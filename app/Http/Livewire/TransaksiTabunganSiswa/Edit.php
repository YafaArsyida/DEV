<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $transaksi;
    public $tanggal; // Tanggal transaksi yang akan diedit
    public $deskripsi;

    protected $listeners = [
        'loadTransaksiTabungan',
    ];

    public function loadTransaksiTabungan($ms_transaksi_tabungan_id)
    {
        // Ambil data transaksi
        $transaksi = TransaksiTabungan::find($ms_transaksi_tabungan_id);

        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
            return;
        }

        // Ambil data penempatan siswa melalui relasi
        $siswa = $transaksi->user_id;

        if (!$siswa) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'User tidak ditemukan.']);
            return;
        }

        $this->transaksi = $transaksi;
        $this->tanggal = $transaksi->tanggal;
    }
    protected $rules = [
        'tanggal' => 'required|date',
        'deskripsi' => 'nullable|string|max:255',
    ];

    public function updateTanggal()
    {
        $this->validate();

        if (!$this->transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak ada data transaksi untuk diperbarui.']);
            return;
        }

        // Format tanggal baru
        $newTanggal = Carbon::parse($this->tanggal)->format('Y-m-d H:i:s');
        $this->transaksi->tanggal = $newTanggal;

        // Perbarui deskripsi jika ada perubahan
        if (!empty($this->deskripsi)) {
            $this->transaksi->deskripsi = $this->deskripsi;
        }

        // Simpan transaksi
        $this->transaksi->save();

        // Reset deskripsi
        $this->deskripsi = '';

        // Update jurnal terkait
        $jurnalIds = [
            $this->transaksi->akuntansi_jurnal_detail_debit_id,
            $this->transaksi->akuntansi_jurnal_detail_kredit_id,
        ];

        AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)
            ->update(['tanggal_transaksi' => $newTanggal]);

        $this->emit('refreshTabungans');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tanggal transaksi berhasil diperbarui.']);
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'loadTransaksiTabungan']);
    }

    public function render()
    {
        return view('livewire.transaksi-tabungan-siswa.edit');
    }
}
