<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AksiBayar extends Component
{
    public $tagihan;
    public $jumlah_bayar = 0;

    protected $listeners = [
        'loadTagihan' => 'loadTagihan',
    ];

    public function loadTagihan($ms_tagihan_siswa_id)
    {
        // Ambil data tagihan
        $this->tagihan = TagihanSiswa::where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)
            ->first();

        if (!$this->tagihan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan tidak ditemukan.']);
            return;
        }

        // Reset jumlah bayar saat tagihan di-load
        $this->jumlah_bayar = $this->tagihan->jumlah_tagihan_siswa - $this->tagihan->jumlah_sudah_dibayar();
    }

    public function aksiBayar($ms_tagihan_siswa_id)
    {
        if (!$this->tagihan || $this->tagihan->ms_tagihan_siswa_id !== $ms_tagihan_siswa_id) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan tidak valid.']);
            return;
        }

        if ($this->jumlah_bayar <= 0) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jumlah bayar harus lebih besar dari 0.']);
            return;
        }

        if ($this->jumlah_bayar > $this->tagihan->jumlah_tagihan_siswa) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jumlah bayar tidak boleh melebihi nominal tagihan.']);
            return;
        }

        // Periksa apakah tagihan sudah ada di keranjang
        $keranjangExist = KeranjangTagihanSiswa::where('ms_penempatan_siswa_id', $this->tagihan->ms_penempatan_siswa_id)
            ->where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)
            ->first();

        if ($keranjangExist) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan ini sudah ada di keranjang.']);
            return;
        }

        // Insert ke keranjang
        $ms_pengguna_id = Auth::id();

        KeranjangTagihanSiswa::create([
            'ms_penempatan_siswa_id' => $this->tagihan->ms_penempatan_siswa_id,
            'ms_tagihan_siswa_id' => $ms_tagihan_siswa_id,
            'ms_pengguna_id' => $ms_pengguna_id,
            'jumlah_bayar' => $this->jumlah_bayar,
            'tanggal_dibayar' => now(),
            'status' => 'Masih Dicicil',
            'deskripsi' => 'Tagihan #' . $ms_tagihan_siswa_id . ' dibayar sebagian.',
        ]);

        // Ambil jumlah yang sudah dibayarkan sebelumnya
        $jumlah_bayar_sebelumnya = $this->tagihan->jumlah_sudah_dibayar();

        // Hitung sisa tagihan
        $sisa_tagihan = $this->tagihan->jumlah_tagihan_siswa - ($jumlah_bayar_sebelumnya + $this->jumlah_bayar);

        // Update status tagihan
        $this->tagihan->update([
            'status' => $sisa_tagihan > 0 ? 'Masih Dicicil' : 'Masuk Keranjang',
            'deskripsi' => $sisa_tagihan > 0
                ? 'Sebagian tagihan dibayar, sisa: Rp' . number_format($sisa_tagihan, 0, ',', '.')
                : 'Tagihan telah Masuk Keranjang.',
        ]);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pembayaran berhasil diproses.']);
        // Tutup modal
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAksiBayar']);

        $this->emit('keranjangUpdated'); // Emit event ke komponen Livewire terkait
        $this->emit('tagihanUpdated');
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.aksi-bayar', [
            'tagihan' => $this->tagihan,
        ]);
    }
}
