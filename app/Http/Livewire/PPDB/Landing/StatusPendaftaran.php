<?php

namespace App\Http\Livewire\PPDB\Landing;

use App\Models\Jenjang;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class StatusPendaftaran extends Component
{
    public $jenjangSlug;

    public $nomorPendaftaran = '';

    public $pendaftaran = null;

    public $pesanStatus = null;

    public function mount($jenjangSlug = null)
    {
        $this->jenjangSlug = $jenjangSlug;
    }

    public function cekStatus()
    {
        $this->resetErrorBag();
        $this->pendaftaran = null;
        $this->pesanStatus = null;

        $nomor = trim((string) $this->nomorPendaftaran);

        $validator = Validator::make(
            ['nomorPendaftaran' => $nomor],
            ['nomorPendaftaran' => 'required|string|max:50'],
            [
                'nomorPendaftaran.required' => 'Nomor pendaftaran wajib diisi.',
                'nomorPendaftaran.max' => 'Nomor pendaftaran maksimal 50 karakter.',
            ]
        );

        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            return;
        }

        $pendaftaran = PPDBPendaftaran::with([
            'ppdb_periode.ms_jenjang',
            'ppdb_jalur',
            'ppdb_gelombang',
            'ppdb_calon_siswa'
        ])
            ->where('nomor_pendaftaran', $nomor)
            ->first();

        if (!$pendaftaran) {
            $this->pesanStatus = 'Nomor pendaftaran tidak ditemukan. Periksa kembali nomor yang Anda masukkan.';
            $this->dispatchBrowserEvent('statusPendaftaranGagal');
            return;
        }

        $jenjang = optional($pendaftaran->ppdb_periode)->ms_jenjang;

        $this->pendaftaran = [
            'nomor_pendaftaran' => $pendaftaran->nomor_pendaftaran,
            'nama_jenjang' => optional($jenjang)->nama_jenjang ?? '-',
            'nama_jalur' => optional($pendaftaran->ppdb_jalur)->nama_jalur ?? '-',
            'nama_gelombang' => optional($pendaftaran->ppdb_gelombang)->nama_gelombang ?? '-',
            'status' => $pendaftaran->status,
            'created_at' => $pendaftaran->created_at,
            'catatan_perbaikan' => $pendaftaran->catatan_perbaikan,
            'nama_siswa' => $pendaftaran->ppdb_calon_siswa->nama_siswa
        ];

        $this->dispatchBrowserEvent('show-modal', [
            'modalId' => 'ModalStatusPendaftaran',
        ]);
    }

    public function render()
    {
        return view('livewire.p-p-d-b.landing.status-pendaftaran');
    }
}
