<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBGelombang;
use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
use App\Services\PPDB\PPDBPendaftaranService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class PilihJalur extends Component
{
    
    public $periodeId;
    public $jalurTerpilih;

    public function mount($periodeId)
    {
        $this->periodeId = $periodeId;
    }

    protected function ambilPeriode()
    {
        return PPDBPeriode::query()
            ->with('ms_jenjang')
            ->whereKey($this->periodeId)
            ->firstOrFail();
    }

    /**
     * Menyimpan pilihan jalur dan membuat pendaftaran baru.
     */
    public function pilihJalur()
    {
        $this->validate([
            'jalurTerpilih' => ['required', 'integer'],
        ], [
            'jalurTerpilih.required' =>
                'Silakan pilih salah satu jalur pendaftaran.',
            'jalurTerpilih.integer' =>
                'Pilihan jalur tidak valid.',
        ]);

        DB::transaction(function () {
            // Kunci periode untuk menyinkronkan pendaftaran
            // yang menggunakan periode yang sama.
            $periode = PPDBPeriode::query()
                ->whereKey($this->periodeId)
                ->where('status', 'aktif')
                ->lockForUpdate()
                ->first();

            if (!$periode) {
                throw ValidationException::withMessages([
                    'jalurTerpilih' => 'Periode PPDB sudah tidak aktif.',
                ]);
            }

            // Pastikan pengguna belum memiliki pendaftaran
            // pada periode ini.
            $sudahMendaftar = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->exists();

            if ($sudahMendaftar) {
                throw ValidationException::withMessages([
                    'jalurTerpilih' =>
                        'Anda sudah memiliki pendaftaran pada periode ini.',
                ]);
            }

            // Pastikan ada gelombang aktif.
            $gelombang = PPDBGelombang::query()
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->where('status', 'Aktif')
                ->first();

            if (!$gelombang) {
                throw ValidationException::withMessages([
                    'jalurTerpilih' =>
                        'Belum ada gelombang pendaftaran yang aktif.',
                ]);
            }

            // Pastikan jalur masih aktif dan termasuk periode ini.
            $jalur = PPDBJalur::query()
                ->where('ppdb_jalur_id', $this->jalurTerpilih)
                ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                ->where('status', 'Aktif')
                ->lockForUpdate()
                ->first();

            if (!$jalur) {
                throw ValidationException::withMessages([
                    'jalurTerpilih' =>
                        'Jalur tidak tersedia. Silakan pilih jalur lain.',
                ]);
            }

            // Hitung pendaftar yang masih memakai kuota jalur.
            $jumlahPendaftar = PPDBPendaftaran::query()
                ->where('ppdb_jalur_id', $jalur->ppdb_jalur_id)
                ->whereNotIn('status', [
                    'rejected',
                    'ditolak',
                    'cancelled',
                    'dibatalkan',
                ])
                ->count();

            if (
                $jalur->kuota !== null &&
                $jumlahPendaftar >= $jalur->kuota
            ) {
                throw ValidationException::withMessages([
                    'jalurTerpilih' => 'Kuota jalur sudah terpenuhi. Silakan pilih jalur lain.',
                ]);
            }

            // Simpan pendaftaran melalui service.
            PPDBPendaftaranService::create([
                'ms_pengguna_id' => Auth::id(),
                'ppdb_periode_id' => $periode->ppdb_periode_id,
                'ppdb_gelombang_id' => $gelombang->ppdb_gelombang_id,
                'ppdb_jalur_id' => $jalur->ppdb_jalur_id,
                'status' => 'draft'
            ]);
        });

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Jalur berhasil dipilih. Silakan lanjutkan pengisian data calon siswa.',
        ]);

        $periode = $this->ambilPeriode();

        return redirect()->route('ppdb.pendaftaran.data-siswa', [
            'jenjang' => $periode->ms_jenjang->slug,
        ]);
    }

    public function render()
    {
        $periode = $this->ambilPeriode();

        $jalurTersedia = PPDBJalur::query()
            ->where('ppdb_periode_id', $this->periodeId)
            ->where('status', 'Aktif')
            ->withCount([
                'ppdb_pendaftaran as jumlah_pendaftar' => function ($query) {
                    $query->whereNotIn('status', [
                        'rejected',
                        'ditolak',
                        'cancelled',
                        'dibatalkan',
                    ]);
                },
            ])
            ->orderBy('nama_jalur')
            ->get();

        return view('livewire.p-p-d-b.pendaftaran.pilih-jalur', [
            'periode' => $periode,
            'jalurTersedia' => $jalurTersedia,
        ]);
    }

}
