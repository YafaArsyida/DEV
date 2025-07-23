<?php

namespace App\Http\Livewire\TransaksiEduPaySiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\EduPaySiswa;
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
        'loadTransaksiEduPay',
    ];

    public function loadTransaksiEduPay($ms_edupay_siswa_id)
    {
        // Ambil data transaksi
        $transaksi = EduPaySiswa::find($ms_edupay_siswa_id);

        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
            return;
        }

        // Ambil data penempatan siswa melalui relasi
        $penempatanSiswa = $transaksi->ms_penempatan_siswa;

        if (!$penempatanSiswa) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Penempatan siswa tidak ditemukan.']);
            return;
        }
        // Set properti dari penempatan siswa
        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id;

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

        $this->emit('refreshEduPays');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tanggal transaksi berhasil diperbarui.']);
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'editTransaksiEduPay']);
    }

    public function render()
    {
        return view('livewire.transaksi-edu-pay-siswa.edit');
    }
}
