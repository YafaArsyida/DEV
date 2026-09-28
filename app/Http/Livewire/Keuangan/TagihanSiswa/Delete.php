<?php

namespace App\Http\Livewire\Keuangan\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use App\Services\AccountingService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Delete extends Component
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
        $tagihan = TagihanSiswa::with([
            'ms_penempatan_siswa.ms_siswa',
            'ms_jenis_tagihan_siswa'
        ])->findOrFail($ms_tagihan_siswa_id);

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
            // =====================================================
            // 1. LOCK TAGIHAN
            // =====================================================
            $tagihan = TagihanSiswa::with([
                'ms_penempatan_siswa.ms_siswa',
                'ms_jenis_tagihan_siswa',
                'akuntansi_jurnal',
            ])
                ->lockForUpdate()
                ->find($this->ms_tagihan_siswa_id);

            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan.');
            }

            // =====================================================
            // 2. VALIDASI STATUS TAGIHAN
            // =====================================================
            // if ($tagihan->status_transaksi === 'dibatalkan') {
            //     throw new \Exception('Tagihan sudah dibatalkan.');
            // }

            // =====================================================
            // 3. CEK KERANJANG
            // =====================================================
            $keranjangExists = KeranjangTagihanSiswa::where(
                'ms_tagihan_siswa_id',
                $tagihan->ms_tagihan_siswa_id
            )->exists();

            if ($keranjangExists) {
                throw new \Exception(
                    'Tagihan sudah ada di keranjang.'
                );
            }

            // =====================================================
            // 4. CEK PEMBAYARAN
            // =====================================================
            $pembayaranExists = DetailTransaksiTagihanSiswa::where(
                'ms_tagihan_siswa_id',
                $tagihan->ms_tagihan_siswa_id
            )->exists();

            if ($pembayaranExists) {
                throw new \Exception(
                    'Tidak dapat menghapus tagihan, terdapat riwayat pembayaran.'
                );
            }

            // =====================================================
            // 5. HAPUS JURNAL
            // =====================================================
            if ($tagihan->akuntansi_jurnal_id) {
                AccountingService::delete(
                    $tagihan->akuntansi_jurnal_id
                );
            }

            // =====================================================
            // 6. UPDATE INFORMASI PENGHAPUS
            // =====================================================
            $namaJenisTagihan =
                $tagihan->ms_jenis_tagihan_siswa
                    ->nama_jenis_tagihan_siswa
                    ?? 'Tagihan';

            $tagihan->update([
                'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
                'deskripsi' => sprintf(
                    'Tagihan %s dihapus oleh %s',
                    $namaJenisTagihan,
                    $this->nama_petugas
                ),
            ]);

            // =====================================================
            // 7. SOFT DELETE TAGIHAN
            // =====================================================
            $tagihan->delete();

            // =====================================================
            // 8. COMMIT
            // =====================================================
            DB::commit();

            // =====================================================
            // 9. RESET STATE
            // =====================================================
            $this->reset([
                'ms_tagihan_siswa_id',
                'ms_jenjang_id',
                'ms_tahun_ajar_id',
                'nama_siswa',
                'nama_jenis_tagihan_siswa',
                'jumlah_tagihan_siswa',
            ]);

            // =====================================================
            // 10. REFRESH DATA
            // =====================================================
            $this->emit('refreshTagihanSiswa');

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAksiDelete'
            ]);

            // =====================================================
            // 11. NOTIFIKASI
            // =====================================================
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Tagihan berhasil dihapus.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' =>
                    $e->getMessage()
                    ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.keuangan.tagihan-siswa.delete');
    }
}
