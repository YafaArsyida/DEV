<?php

namespace App\Http\Livewire\Siswa;

use App\Models\AktifitasPengguna;
use App\Models\EduPay;
use App\Models\EduPaySiswa;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\Tabungan;
use App\Models\TabunganSiswa;
use App\Models\Tagihan;
use App\Models\TagihanSiswa;
use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    public $ms_penempatan_id;
    public $siswaSelected = [];

    protected $listeners = [
        'confirmDeleteSiswa' => 'setPenempatanId',
        'confirmBulkDelete' => 'setSiswaSelected',
    ];

    public function setPenempatanId($id)
    {
        $this->ms_penempatan_id = $id;
    }

    public function setSiswaSelected($ids)
    {
        $this->siswaSelected = $ids;
    }

    public function deleteSiswa()
    {
        if (!empty($this->siswaSelected)) {
            DB::beginTransaction();
            try {
                $penempatanIds = $this->siswaSelected;

                // Ambil semua penempatan siswa terkait
                $penempatanSiswa = PenempatanSiswa::whereIn('ms_penempatan_siswa_id', $penempatanIds)->get();

                $gagalDihapus = [];
                foreach ($penempatanSiswa as $penempatan) {
                    // Pengecekan tagihan terkait
                    if (TagihanSiswa::where('ms_penempatan_siswa_id', $penempatan->ms_penempatan_siswa_id)->exists()) {
                        $gagalDihapus[] = $penempatan->ms_penempatan_siswa_id; // Catat ID penempatan yang gagal dihapus
                        continue; // Lewati data ini
                    }

                    // Pengecekan transaksi tabungan dan EduPay
                    $msSiswaId = $penempatan->ms_siswa_id;
                    $adaTransaksiTabungan = TabunganSiswa::where('ms_siswa_id', $msSiswaId)->exists();
                    $adaTransaksiEduPay = EduPaySiswa::where('ms_siswa_id', $msSiswaId)->exists();

                    if ($adaTransaksiTabungan || $adaTransaksiEduPay) {
                        $gagalDihapus[] = $penempatan->ms_penempatan_siswa_id; // Catat ID penempatan yang gagal dihapus
                        continue; // Lewati data ini
                    }

                    // Soft delete penempatan
                    $penempatan->ms_pengguna_id = auth()->id();
                    $penempatan->save();
                    $penempatan->delete();

                    // Cek apakah siswa ini memiliki penempatan lain
                    $penempatanLain = PenempatanSiswa::where('ms_siswa_id', $msSiswaId)->exists();

                    if (!$penempatanLain) {
                        // Pengecekan tagihan terkait penempatan lain
                        if (!TagihanSiswa::whereIn(
                            'ms_penempatan_siswa_id',
                            PenempatanSiswa::where('ms_siswa_id', $msSiswaId)->pluck('ms_penempatan_siswa_id')
                        )->exists()) {
                            // Soft delete siswa jika tidak ada keterkaitan
                            $siswa = Siswa::find($msSiswaId);
                            if ($siswa) {
                                $siswa->delete();
                            }
                        }
                    }
                }

                DB::commit();

                if (!empty($gagalDihapus)) {
                    // Berikan notifikasi data yang gagal dihapus
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => 'Sebagian data tidak dapat dihapus karena memiliki keterkaitan tagihan atau transaksi.',
                        'details' => $gagalDihapus
                    ]);
                } else {
                    $this->dispatchBrowserEvent('alertify-success', ['message' => 'Semua penempatan siswa berhasil dihapus.']);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
            }
        } elseif ($this->ms_penempatan_id) {
            // Delete biasa
            $penempatan = PenempatanSiswa::find($this->ms_penempatan_id);
            if ($penempatan) {
                // Pengecekan di Tagihan, Tabungan, dan EduPay
                $msSiswaId = $penempatan->ms_siswa_id;
                $tagihanTerkait = TagihanSiswa::where('ms_penempatan_siswa_id', $penempatan->ms_penempatan_siswa_id)->exists();
                $adaTransaksiTabungan = TabunganSiswa::where('ms_siswa_id', $msSiswaId)->exists();
                $adaTransaksiEduPay = EduPaySiswa::where('ms_siswa_id', $msSiswaId)->exists();

                if ($tagihanTerkait || $adaTransaksiTabungan || $adaTransaksiEduPay) {
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak dapat menghapus siswa karena memiliki keterkaitan tagihan atau transaksi.']);
                    return;
                }

                $penempatan->ms_pengguna_id = auth()->user()->ms_pengguna_id;
                $penempatan->save();
                $penempatan->delete(); // Soft delete

                // Cek apakah siswa masih memiliki penempatan lain
                $penempatanLain = PenempatanSiswa::where('ms_siswa_id', $msSiswaId)->exists();

                if (!$penempatanLain) {
                    // Jika tidak ada penempatan lain, cek keterkaitan lainnya (tagihan, transaksi, dll.)
                    $tagihanTerkaitPenempatanLain = TagihanSiswa::whereIn(
                        'ms_penempatan_siswa_id',
                        PenempatanSiswa::where('ms_siswa_id', $msSiswaId)->pluck('ms_penempatan_siswa_id')
                    )->exists();
                    if (!$tagihanTerkaitPenempatanLain) {
                        // Hapus siswa jika tidak ada keterkaitan tagihan dengan penempatan lainnya
                        $siswa = Siswa::find($msSiswaId);
                        if ($siswa) {
                            $siswa->delete(); // Soft delete siswa
                        }
                    }
                }
                $this->dispatchBrowserEvent('alertify-success', ['message' => 'Penempatan Siswa berhasil dihapus.']);
            } else {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
            }
        }

        // Reset semua data
        $this->ms_penempatan_id = null;
        $this->siswaSelected = [];
        $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalDeleteSiswa']);
        // Emit event untuk refresh dan kosongkan selected siswa
        $this->emit('refreshSiswas', []);
        $this->emit('refreshKelass');
    }

    public function render()
    {
        return view('livewire.siswa.delete');
    }
}
