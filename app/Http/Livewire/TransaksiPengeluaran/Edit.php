<?php

namespace App\Http\Livewire\TransaksiPengeluaran;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $transaksi;

    public $tanggal; // Tanggal transaksi yang akan diedit
    public $nominal; // Tanggal transaksi yang akan diedit
    public $deskripsi;

    protected $listeners = [
        'editPengeluaran',
    ];

    public function editPengeluaran($ms_pengeluaran_id)
    {
        $transaksi = Pengeluaran::findOrFail($ms_pengeluaran_id);

        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
            return;
        }

        $this->ms_jenjang_id = $transaksi->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $transaksi->ms_tahun_ajar_id ?? null;

        $this->transaksi = $transaksi;
        $this->tanggal = $transaksi->tanggal;
        $this->nominal = $transaksi->nominal;
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

        $newTanggalTransaksi = Carbon::parse($this->tanggal)->format('Y-m-d H:i:s');
        $this->transaksi->tanggal = $newTanggalTransaksi;
        $deskripsiJurnal =  "Pengeluaran Operasional " . $this->nominal . ", " . $this->deskripsi;

        // Perbarui deskripsi jika ada perubahan
        if (!empty($this->deskripsi)) {
            $this->transaksi->deskripsi = $deskripsiJurnal;
        }

        $this->transaksi->save();

        $this->deskripsi = '';

        $jurnalIds = [
            $this->transaksi->akuntansi_jurnal_detail_debit_id,
            $this->transaksi->akuntansi_jurnal_detail_kredit_id,
        ];

        AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)
            ->update([
                'tanggal_transaksi' => $newTanggalTransaksi,
                'deskripsi' => $deskripsiJurnal
            ]);

        $this->emit('refreshTransaksiPengeluaran');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi berhasil diperbarui.']);
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'editPengeluaran']);
    }

    public function render()
    {
        return view('livewire.transaksi-pengeluaran.edit');
    }
}
