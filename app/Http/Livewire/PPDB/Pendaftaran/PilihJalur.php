<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBGelombang;
use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
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

        $nomorPendaftaran = DB::transaction(function () {
            // Kunci periode untuk menyinkronkan proses pendaftaran
            // yang menggunakan periode yang sama.
            $periode = PPDBPeriode::query()
                ->whereKey($this->periodeId)
                ->where('status', 'aktif')
                ->lockForUpdate()
                ->first();

            if (!$periode) {
                throw ValidationException::withMessages([
                    'jalurTerpilih' =>
                        'Periode PPDB sudah tidak aktif.',
                ]);
            }

            // Pastikan belum ada pendaftaran untuk pengguna ini.
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

            // Pastikan gelombang dan jalur masih tersedia.
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

            // Hitung pendaftar yang masih memakai kuota jalur ini.
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
                    'jalurTerpilih' =>
                        'Kuota jalur sudah terpenuhi. Silakan pilih jalur lain.',
                ]);
            }

            $nomor = $this->buatNomorPendaftaran();

            PPDBPendaftaran::create([
                'ms_pengguna_id' => Auth::id(),
                'ppdb_periode_id' => $periode->ppdb_periode_id,
                'ppdb_gelombang_id' => $gelombang->ppdb_gelombang_id,
                'ppdb_jalur_id' => $jalur->ppdb_jalur_id,
                'nomor_pendaftaran' => $nomor,
                'current_step' => 2,
                'status' => 'draft',
                'submitted_at' => null,
            ]);

            return $nomor;
        });

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Jalur berhasil dipilih. Silakan lanjutkan pengisian data calon siswa.',
        ]);

        return redirect()->route('ppdb.pendaftaran.data-siswa');
    }

    /**
     * Membuat nomor pendaftaran unik.
     */
    protected function buatNomorPendaftaran()
    {
        do {
            $nomor = 'PPDB-' . now()->format('Y') . '-'
                . Str::upper(Str::random(6));

            $sudahAda = PPDBPendaftaran::query()
                ->where('nomor_pendaftaran', $nomor)
                ->exists();
        } while ($sudahAda);

        return $nomor;
    }

    public function render()
    {
        $jalurTersedia = collect();

        if ($this->periodeId) {
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
        }
        return view('livewire.p-p-d-b.pendaftaran.pilih-jalur',[
            'jalurTersedia' => $jalurTersedia
        ]);
    }
}
