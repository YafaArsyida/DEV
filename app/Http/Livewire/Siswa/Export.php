<?php

namespace App\Http\Livewire\Siswa;

use App\Exports\ExportSiswa;
use App\Models\AktifitasPengguna;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    public $siswasOnPage = [];
    public $ms_tahun_ajar_id, $ms_jenjang_id;

    protected $listeners = ['prepareExport'];

    public function prepareExport($jenjang, $tahunAjar, $siswas)
    {
        if (!isset($siswas)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data laporan tidak valid.']);
            return;
        }
        $this->siswasOnPage = $siswas;
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Dokumen siap diexport...']);
    }

    public function exportSiswa()
    {
        if (empty($this->siswasOnPage)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
            return;
        }

        try {
            // Log aktivitas pengguna
            AktifitasPengguna::create([
                'ms_pengguna_id'       => Auth::id(),
                'ms_jenjang_id'        => $this->ms_jenjang_id ?? null,  // Jika ada jenjang yang relevan
                'ms_tahun_ajar_id'     => $this->ms_tahun_ajar_id ?? null, // Jika ada tahun ajar yang relevan
                'tipe_aksi'            => 'export',
                'tipe_tabel'           => 'tabel siswa',
                'id_tabel'             => null, // Tidak ada ID spesifik karena ini menyangkut banyak data
                'ip_pengguna'          => request()->ip(),
                'perangkat_pengguna'   => request()->header('User-Agent'),
                'deskripsi'            => 'Melakukan ekspor data siswa.',
            ]);

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
            return Excel::download(new ExportSiswa($this->siswasOnPage), 'siswa-export.xlsx');
        } catch (\Exception $e) {
            // Informasikan error kepada pengguna
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.siswa.export');
    }
}
