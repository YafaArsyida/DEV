<?php

namespace App\Http\Livewire\Siswa;

use App\Http\Controllers\HelperController;
use Livewire\Component;
use App\Models\PenempatanSiswa as PenempatanSiswaModel;


class Detail extends Component
{
    public $siswaDetail;
    public $saldo_tabungan;
    public $saldo_edupay;
    public $created_at_formatted;

    protected $listeners = ['showDetailSiswa'];
    public function showDetailSiswa($id)
    {
        $siswa = PenempatanSiswaModel::with([
            'ms_siswa.ms_educard',
            'ms_jenjang',
            'ms_tahun_ajar',
            'ms_kelas',
            'ms_pengguna'
        ])->find($id);

        if (!$siswa) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Data siswa tidak ditemukan'
            ]);
            return;
        }

        $this->saldo_tabungan = $siswa->ms_siswa->saldo_tabungan_siswa() ?? 0;
        $this->saldo_edupay   = $siswa->ms_siswa->saldo_edupay_siswa() ?? 0;

        // 🔥 reset state
        $this->resetErrorBag();
        $this->resetValidation();

        $this->siswaDetail = $siswa;

        // format tambahan (jangan di blade)
        $this->created_at_formatted = HelperController::formatTanggalIndonesia(
            $siswa->ms_siswa->created_at,
            'd F Y H:i'
        );

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Detail siswa dimuat'
        ]);
    }

    public function render()
    {
        return view('livewire.siswa.detail');
    }
}
