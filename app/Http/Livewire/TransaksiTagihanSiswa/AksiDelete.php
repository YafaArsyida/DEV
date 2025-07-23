<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AksiDelete extends Component
{
    public $ms_tagihan_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $nama_siswa;

    public $nama_jenis_tagihan_siswa;
    public $jumlah_tagihan_siswa;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'loadTagihanDelete' => 'loadTagihanDelete',
    ];

    public function loadTagihanDelete($ms_tagihan_siswa_id)
    {
        $tagihan = TagihanSiswa::findOrFail($ms_tagihan_siswa_id);

        $penempatanSiswa = $tagihan->ms_penempatan_siswa;
        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id ?? null;
        $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa ?? null;

        $this->nama_jenis_tagihan_siswa = $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa;
        $this->jumlah_tagihan_siswa = $tagihan->jumlah_tagihan_siswa;

        $this->ms_tagihan_siswa_id = $ms_tagihan_siswa_id;
    }

    public function deleteTagihan()
    {
        DB::beginTransaction();

        try {
            // Validasi apakah tagihan ada
            $tagihan = TagihanSiswa::find($this->ms_tagihan_siswa_id);
            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan.');
            }

            // Periksa apakah tagihan ada di keranjang
            $keranjang = KeranjangTagihanSiswa::where('ms_tagihan_siswa_id', $this->ms_tagihan_siswa_id)->first();

            if ($keranjang) {
                // Jika sudah ada di keranjang, berikan notifikasi dan hentikan proses
                $this->dispatchBrowserEvent(
                    'alertify-error',
                    ['message' => 'Tagihan sudah ada di keranjang.']
                );
                return;
            }

            // Cek apakah tagihan memiliki pembayaran
            $pembayaran = DetailTransaksiTagihanSiswa::where('ms_tagihan_siswa_id', $this->ms_tagihan_siswa_id)->exists();
            if ($pembayaran) {
                throw new \Exception('Tidak dapat dihapus, terdapat riwayat pembayaran.');
            }

            // Update data tagihan sebelum dihapus
            $tagihan->ms_pengguna_id = auth()->user()->ms_pengguna_id; // Set pengguna yang menghapus
            $tagihan->deskripsi = "Tagihan dihapus oleh petugas {$this->nama_petugas}"; // Set deskripsi
            $tagihan->save();

            $jurnalIds = [
                $tagihan->akuntansi_jurnal_detail_debit_id,
                $tagihan->akuntansi_jurnal_detail_kredit_id,
            ];

            // Validasi dan soft delete jurnal
            AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)->get()->each(function ($jurnal) {
                $jurnal->delete();
            });

            // Hapus tagihan
            $tagihan->delete();

            // Commit transaksi
            DB::commit();

            // Emit event untuk refresh data di komponen lain
            $this->emit('tagihanUpdated');
            $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalAksiDelete']);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil dihapus.']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Berikan notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.aksi-delete');
    }
}
