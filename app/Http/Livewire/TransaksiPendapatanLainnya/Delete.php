<?php

namespace App\Http\Livewire\TransaksiPendapatanLainnya;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Infaq;
use App\Models\PendapatanLainnya;
use Livewire\Component;

class Delete extends Component
{
    public $ms_pendapatan_lainnya_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'confirmDeletePendapatanLainnya'
    ];

    public function confirmDeletePendapatanLainnya($ms_pendapatan_lainnya_id)
    {
        $transaksi = PendapatanLainnya::findOrFail($ms_pendapatan_lainnya_id);

        $this->ms_jenjang_id = $transaksi->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $transaksi->ms_tahun_ajar_id ?? null;

        $this->ms_pendapatan_lainnya_id = $ms_pendapatan_lainnya_id;
    }

    public function deletePendapatanLainnya()
    {
        try {
            // Validasi apakah ID ada
            if (!$this->ms_pendapatan_lainnya_id) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
                return;
            }

            // Ambil data transaksi berdasarkan ID
            $transaksi = PendapatanLainnya::find($this->ms_pendapatan_lainnya_id);

            if (!$transaksi) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
                return;
            }

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
            $this->emit('refreshTransaksiPendapatanLainnya');
            $this->emit('refreshSaldo');
            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'deletePendapatanLainnya']);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi berhasil dihapus.']);
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-pendapatan-lainnya.delete');
    }
}
