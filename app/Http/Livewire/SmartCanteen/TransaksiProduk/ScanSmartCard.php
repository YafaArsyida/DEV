<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\EduCard;
use App\Models\PenempatanSiswa;
use Livewire\Component;

class ScanSmartCard extends Component
{
    public $user_type;
    public $user_id;
    public $ms_penempatan_siswa_id;
    public $nama;
    public $educard;
    public $saldo_edupay;

    public $nama_kelas;
    public $nama_jabatan;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    protected $listeners = [
        'openScanModal',
        'parameterUpdated',
    ];

    public function parameterUpdated($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    // Reset saat modal dibuka
    public function openScanModal()
    {
        $this->reset([
            'user_type',
            'user_id',
            'ms_penempatan_siswa_id',
            'nama',
            'nama_kelas',
            'nama_jabatan',
            'educard',
            'saldo_edupay',
        ]);
    }

    // Jalankan ketika kode kartu diinputkan
    public function updatedEduCard($value)
    {
        $card = EduCard::with(['ms_siswa', 'ms_pegawai'])
            ->where('kode_kartu', $value)
            ->first();

        if (!$card) {
            // $this->emit('scanFailed', 'Kartu tidak dikenali');
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak Terdaftar.']);
            return;
        }

        if ($card->ms_siswa) {
            $penempatan = PenempatanSiswa::where('ms_siswa_id', $card->ms_siswa->ms_siswa_id)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->first();

            if (!$penempatan) {
                // $this->emit('scanFailed', 'Siswa tidak memiliki penempatan');
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Transaksi ditolak. Siswa tidak memiliki penempatan pada Jenjang/Tahun Ajar ini.'
                ]);
                return;
            }

            $this->user_type    = 'siswa';
            $this->user_id      = $card->ms_siswa->ms_siswa_id;
            $this->ms_penempatan_siswa_id = $penempatan->ms_penempatan_siswa_id;
            $this->nama         = $card->ms_siswa->nama_siswa;
            $this->nama_kelas   = $penempatan->ms_kelas->nama_kelas;
            $this->educard      = $card->kode_kartu;
            $this->saldo_edupay = $card->ms_siswa->saldo_edupay_siswa();
        } elseif ($card->ms_pegawai) {
            $this->user_type    = 'pegawai';
            $this->user_id      = $card->ms_pegawai->ms_pegawai_id;
            $this->nama         = $card->ms_pegawai->nama_pegawai;
            $this->nama_jabatan         = $card->ms_pegawai->ms_jabatan->nama_jabatan;
            $this->educard      = $card->kode_kartu;
            $this->saldo_edupay = method_exists($card->ms_pegawai, 'saldo_edupay_pegawai')
                ? $card->ms_pegawai->saldo_edupay_pegawai()
                : 0;
        }

        $this->emit('scanSuccess', [
            'user_type'             => $this->user_type,
            'user_id'               => $this->user_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'nama'                  => $this->nama,
            'nama_kelas'            => $this->nama_kelas ?? null,
            'nama_jabatan'            => $this->nama_jabatan ?? null,
            'educard'               => $this->educard,
            'saldo_edupay'          => $this->saldo_edupay,
            'ms_jenjang_id'         => $this->selectedJenjang,
            'ms_tahun_ajar_id'      => $this->selectedTahunAjar,
        ]);

        $this->dispatchBrowserEvent('hide-delete-modal', ['modalId' => 'ModalScanRFID']);
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kartu valid.']);
    }

    public function render()
    {
        return view('livewire.smart-canteen.transaksi-produk.scan-smart-card');
    }
}
