<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DetailJalur extends Component
{
    public $pendaftaranId;

    public $modeEdit = false;

    public $jalurTerpilih;

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaran();

        $this->jalurTerpilih = $pendaftaran->ppdb_jalur_id;
        $this->modeEdit = false;
    }

    protected function ambilPendaftaran()
    {
        return PPDBPendaftaran::query()
            ->with([
                'ppdb_jalur',
                'ppdb_gelombang',
                'ppdb_periode',
                'ppdb_periode.ms_jenjang',
            ])
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->firstOrFail();
    }

    protected function bolehUbahData($status)
    {
        return ! in_array(
            strtolower(trim((string) $status)),
            ['diterima', 'pengecekan'],
            true
        );
    }

    public function edit()
    {
        $pendaftaran = $this->ambilPendaftaran();

        if (! $this->bolehUbahData($pendaftaran->status)) {
            $this->modeEdit = false;
            $this->jalurTerpilih = $pendaftaran->ppdb_jalur_id;

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jalur tidak dapat diubah pada tahap pendaftaran ini.',
            ]);

            return;
        }

        // Selalu mulai dari data yang tersimpan.
        $this->jalurTerpilih = $pendaftaran->ppdb_jalur_id;

        $this->resetValidation();
        $this->modeEdit = true;
    }

    public function batalEdit()
    {
        // Ambil ulang data agar perubahan yang belum disimpan dibuang.
        $pendaftaran = $this->ambilPendaftaran();

        $this->jalurTerpilih = $pendaftaran->ppdb_jalur_id;

        $this->resetValidation();

        // Tutup mode edit secara eksplisit.
        $this->modeEdit = false;
    }


    /**
     * Memperbarui jalur pada pendaftaran yang sudah ada.
     */
    public function perbaruiJalur()
    {
        $this->validate([
            'jalurTerpilih' => ['required', 'integer'],
        ], [
            'jalurTerpilih.required' => 'Silakan pilih jalur pendaftaran.',
            'jalurTerpilih.integer' => 'Pilihan jalur tidak valid.',
        ]);

        DB::transaction(function () {
            $pendaftaran = PPDBPendaftaran::query()
                ->where(
                    'ppdb_pendaftaran_id', $this->pendaftaranId
                )
                ->where('ms_pengguna_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            if (!$pendaftaran) {
                throw ValidationException::withMessages([
                    'nama_siswa' => 'Pendaftaran tidak ditemukan atau bukan milik Anda.',
                ]);
            }

            $jalur = PPDBJalur::query()
                ->where(
                    'ppdb_jalur_id',
                    $this->jalurTerpilih
                )
                ->where(
                    'ppdb_periode_id',
                    $pendaftaran->ppdb_periode_id
                )
                ->where('status', 'Aktif')
                ->lockForUpdate()
                ->first();

            if (! $jalur) {
                throw ValidationException::withMessages([
                    'jalurTerpilih' =>
                        'Jalur tidak tersedia pada periode pendaftaran ini.',
                ]);
            }

            $jumlahPendaftar = PPDBPendaftaran::query()
                ->where(
                    'ppdb_jalur_id', $jalur->ppdb_jalur_id
                )
                ->where(
                    'ppdb_periode_id', $pendaftaran->ppdb_periode_id
                )
                ->where(
                    'ppdb_pendaftaran_id',
                    '!=',
                    $pendaftaran->ppdb_pendaftaran_id
                )
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
                    'jalurTerpilih' => 'Kuota jalur tersebut sudah terpenuhi. Silakan pilih jalur lain.',
                ]);
            }

            // UPDATE record lama, tidak membuat pendaftaran baru.
            $pendaftaran->update([
                'ppdb_jalur_id' => $jalur->ppdb_jalur_id,
            ]);
        });

        // Ambil kembali data pendaftaran beserta jenjangnya.
        $pendaftaran = $this->ambilPendaftaran();

        $this->jalurTerpilih = $pendaftaran->ppdb_jalur_id;

        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Jalur pendaftaran berhasil diperbarui.',
        ]);

        return redirect()->route('ppdb.pendaftaran.data-siswa', [
            'jenjang' => $slugJenjang,
        ]);
    }

    public function render()
    {
        $pendaftaran = $this->ambilPendaftaran();

        $bolehUbahData = $this->bolehUbahData(
            $pendaftaran->status
        );

        $jalurTersedia = PPDBJalur::query()
            ->where(
                'ppdb_periode_id',
                $pendaftaran->ppdb_periode_id
            )
            ->where('status', 'Aktif')
            ->withCount([
                'ppdb_pendaftaran as jumlah_pendaftar' =>
                    function ($query) {
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

        return view('livewire.p-p-d-b.pendaftaran.detail-jalur',
            compact(
                'pendaftaran',
                'jalurTersedia',
                'bolehUbahData'
            )
        );
    }
}
