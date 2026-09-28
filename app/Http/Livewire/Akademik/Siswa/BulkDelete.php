<?php

namespace App\Http\Livewire\Akademik\Siswa;

use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use App\Models\TransaksiEduPay;
use App\Models\TransaksiTabungan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BulkDelete extends Component
{
    protected $listeners = [
        'confirmBulkDelete' => 'setBulkIds',
    ];

    public $bulkIds = [];

    public function setBulkIds($ids)
    {
        $this->bulkIds = $ids;
    }

    protected function cleanupSiswa($msSiswaId)
    {
        $masihAda = PenempatanSiswa::where('ms_siswa_id', $msSiswaId)->exists();

        if ($masihAda) return;

        $punyaTagihan = TagihanSiswa::whereIn(
            'ms_penempatan_siswa_id',
            PenempatanSiswa::where('ms_siswa_id', $msSiswaId)->pluck('ms_penempatan_siswa_id')
        )->exists();

        if ($punyaTagihan) return;

        Siswa::where('ms_siswa_id', $msSiswaId)->delete();
    }

    protected function afterBulkDelete($deletedCount, $allIds, $allowedIds)
    {
        $failedCount = count($allIds) - $deletedCount;

        if ($failedCount > 0) {
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "$deletedCount berhasil dihapus, $failedCount gagal (punya transaksi)"
            ]);
        } else {
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "$deletedCount data berhasil dihapus"
            ]);
        }

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalBulkDeleteSiswa'
        ]);

        // 🔥 reset state
        $this->bulkIds = [];

        $this->emit('refreshSiswas', []);
        $this->emit('refreshKelass');
    }

    public function bulkDeleteSiswa()
    {
        DB::beginTransaction();

        try {
            if (empty($this->bulkIds)) {
                throw new \Exception('Tidak ada data yang dipilih');
            }

            // 🔥 Ambil semua penempatan (1x query)
            $penempatans = PenempatanSiswa::whereIn('ms_penempatan_siswa_id', $this->bulkIds)->get();

            if ($penempatans->isEmpty()) {
                throw new \Exception('Data tidak ditemukan');
            }

            $penempatanIds = $penempatans->pluck('ms_penempatan_siswa_id');
            $siswaIds      = $penempatans->pluck('ms_siswa_id');

            // 🔥 VALIDASI MASSAL (NO LOOP)
            $blockedByTagihan = TagihanSiswa::whereIn('ms_penempatan_siswa_id', $penempatanIds)
                ->pluck('ms_penempatan_siswa_id');

            $blockedByTabungan = TransaksiTabungan::whereIn('user_id', $siswaIds)
                ->pluck('user_id');

            $blockedByEdupay = TransaksiEduPay::whereIn('user_id', $siswaIds)
                ->pluck('user_id');

            // 🔥 Filter yang boleh dihapus
            $allowed = $penempatans->filter(function ($item) use ($blockedByTagihan, $blockedByTabungan, $blockedByEdupay) {
                return !(
                    $blockedByTagihan->contains($item->ms_penempatan_siswa_id) ||
                    $blockedByTabungan->contains($item->ms_siswa_id) ||
                    $blockedByEdupay->contains($item->ms_siswa_id)
                );
            });

            $allowedIds = $allowed->pluck('ms_penempatan_siswa_id');
            $affectedSiswaIds = $allowed->pluck('ms_siswa_id');

            // ❗ kalau tidak ada yg bisa dihapus
            if ($allowedIds->isEmpty()) {
                throw new \Exception('Tidak ada data yang bisa dihapus');
            }

            // 🔥 DELETE MASSAL (SUPER CEPAT)
            PenempatanSiswa::whereIn('ms_penempatan_siswa_id', $allowedIds)
                ->update(['ms_pengguna_id' => auth()->id()]);

            PenempatanSiswa::whereIn('ms_penempatan_siswa_id', $allowedIds)
                ->delete();

            // 🔥 CLEANUP SISWA (LOOP KECIL, AMAN)
            foreach ($affectedSiswaIds->unique() as $siswaId) {
                $this->cleanupSiswa($siswaId);
            }

            DB::commit();

            $this->afterBulkDelete($allowedIds->count(), $this->bulkIds, $allowedIds);
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.akademik.siswa.bulk-delete');
    }
}
