<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiTagihanSiswa;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $transaksi;
    public $tanggalTransaksi; // Tanggal transaksi yang akan diedit
    public $deskripsi;

    protected $listeners = [
        'loadHistoriTransaksi',
    ];

    protected $rules = [
        'tanggalTransaksi' => 'required|date',
        'deskripsi' => 'nullable|string|max:255',
    ];


    public function loadHistoriTransaksi($ms_transaksi_tagihan_siswa_id)
    {
        // Ambil data transaksi
        $transaksi = TransaksiTagihanSiswa::find($ms_transaksi_tagihan_siswa_id);

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
        $this->tanggalTransaksi = $transaksi->tanggal_transaksi;
    }

    public function updateTanggalTransaksi()
    {
        $this->validate();

        if (!$this->transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak ada data transaksi untuk diperbarui.']);
            return;
        }

        // Format tanggal transaksi
        $newTanggalTransaksi = Carbon::parse($this->tanggalTransaksi)->format('Y-m-d H:i:s');
        $this->transaksi->tanggal_transaksi = $newTanggalTransaksi;

        // Perbarui deskripsi jika ada perubahan
        if (!empty($this->deskripsi)) {
            $this->transaksi->deskripsi = $this->deskripsi;
        }

        $this->transaksi->save();

        $this->deskripsi = '';

        // Update jurnal terkait
        $jurnalIds = [
            $this->transaksi->akuntansi_jurnal_detail_debit_id,
            $this->transaksi->akuntansi_jurnal_detail_kredit_id,
        ];

        AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)
            ->update(['tanggal_transaksi' => $newTanggalTransaksi]);

        $this->emit('historiUpdated');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tanggal transaksi berhasil diperbarui.']);
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'editHistoriTagihan']);
    }

    public function render()
    {
        return view('livewire.tagihan-siswa.edit');
    }
}
