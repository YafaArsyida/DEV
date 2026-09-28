<?php

namespace App\Http\Livewire\Akademik\Siswa;

use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use App\Models\TransaksiEduPay;
use App\Models\TransaksiTabungan;
use Livewire\Component;

use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    public $ms_penempatan_id;
    public $siswaSelected = [];

    protected $listeners = [
        'confirmDeleteSiswa' => 'setPenempatanId',
    ];

    public function setPenempatanId($id)
    {
        $this->ms_penempatan_id = $id;
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

    protected function afterDeleteSuccess()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data berhasil dihapus'
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalDeleteSiswa'
        ]);

        $this->ms_penempatan_id = null;

        $this->emit('refreshSiswas', []);
        $this->emit('refreshKelass');
    }

    public function deleteSiswa()
    {
        DB::beginTransaction();

        try {
            if (!$this->ms_penempatan_id) {
                throw new \Exception('Data tidak ditemukan');
            }

            $penempatan = PenempatanSiswa::with('ms_siswa')
                ->find($this->ms_penempatan_id);

            if (!$penempatan) {
                throw new \Exception('Data tidak ditemukan');
            }

            // 🔥 VALIDASI RELASI (langsung, no loop)
            $hasTagihan = TagihanSiswa::where('ms_penempatan_siswa_id', $penempatan->ms_penempatan_siswa_id)->exists();
            $hasTabungan = TransaksiTabungan::where('user_id', $penempatan->ms_siswa_id)->exists();
            $hasEdupay = TransaksiEduPay::where('user_id', $penempatan->ms_siswa_id)->exists();

            if ($hasTagihan || $hasTabungan || $hasEdupay) {
                throw new \Exception('Tidak bisa dihapus karena masih memiliki transaksi');
            }

            // ✅ delete penempatan
            $penempatan->update([
                'ms_pengguna_id' => auth()->id()
            ]);
            $penempatan->delete();

            // ✅ cleanup siswa
            $this->cleanupSiswa($penempatan->ms_siswa_id);

            DB::commit();

            $this->afterDeleteSuccess();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.akademik.siswa.delete');
    }
}
