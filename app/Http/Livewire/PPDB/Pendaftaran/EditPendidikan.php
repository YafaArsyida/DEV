<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBPendaftaran;
use App\Models\PpdbPendidikan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditPendidikan extends Component
{
    public $pendaftaranId;

    public $asal_sekolah = '';
    public $alamat_asal_sekolah = '';
    public $npsn_asal_sekolah = '';
    public $jenjang_asal = 'TK';
    public $tahun_lulus = '';
    public $nomor_ijazah = '';

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaran();

        $this->pastikanBolehUbah($pendaftaran);

        $this->isiForm();
    }

    /**
     * Mengambil pendaftaran milik pengguna yang sedang login.
     */
    protected function ambilPendaftaran()
    {
        return PPDBPendaftaran::query()
            ->whereKey($this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->firstOrFail();
    }

    /**
     * Menentukan apakah data masih boleh diubah.
     */
    protected function bolehUbahData($status)
    {
        return ! in_array(
            strtolower(trim((string) $status)),
            ['diterima', 'pengecekan'],
            true
        );
    }

    /**
     * Memastikan status pendaftaran mengizinkan pengeditan.
     */
    protected function pastikanBolehUbah($pendaftaran)
    {
        if (! $this->bolehUbahData($pendaftaran->status)) {
            abort(
                403,
                'Data pendidikan tidak dapat diubah pada tahap pendaftaran ini.'
            );
        }
    }

    /**
     * Mengisi form dari data pendidikan yang tersimpan.
     */
    protected function isiForm()
    {
        $pendaftaran = $this->ambilPendaftaran();

        $pendidikan = PpdbPendidikan::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->firstOrFail();

        $this->asal_sekolah = $pendidikan->asal_sekolah ?? '';
        $this->alamat_asal_sekolah = $pendidikan->alamat_asal_sekolah ?? '';
        $this->npsn_asal_sekolah =
            $pendidikan->npsn_asal_sekolah ?? '';
        $this->jenjang_asal =
            $pendidikan->jenjang_asal ?? 'TK';
        $this->tahun_lulus = $pendidikan->tahun_lulus ?? '';
        $this->nomor_ijazah = $pendidikan->nomor_ijazah ?? '';
    }

    protected function rules()
    {
        return [
            'asal_sekolah' => [
                'nullable',
                'string',
                'max:255',
            ],
            'alamat_asal_sekolah' => 'nullable|string|max:1000',
            'npsn_asal_sekolah' => [
                'nullable',
                'regex:/^\d{8}$/',
            ],
            'jenjang_asal' => [
                'required',
                'in:TK,RA,PAUD,TIDAK_ADA',
            ],
            'tahun_lulus' => [
                'nullable',
                'integer',
                'min:' . (date('Y') - 10),
                'max:' . date('Y'),
            ],
            'nomor_ijazah' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }

    protected $messages = [
        'asal_sekolah.max' =>
            'Nama sekolah maksimal 255 karakter.',

        'npsn_asal_sekolah.regex' =>
            'NPSN harus terdiri dari 8 digit angka.',

        'alamat_asal_sekolah.string' => 'Alamat TK harus berupa teks.',
        'alamat_asal_sekolah.max' => 'Alamat TK maksimal 1000 karakter.',

        'jenjang_asal.required' =>
            'Jenjang pendidikan asal wajib dipilih.',

        'jenjang_asal.in' =>
            'Pilihan jenjang pendidikan tidak valid.',

        'tahun_lulus.integer' =>
            'Tahun lulus harus berupa angka.',

        'tahun_lulus.min' =>
            'Tahun lulus terlalu lama. Silakan periksa kembali.',

        'tahun_lulus.max' =>
            'Tahun lulus tidak boleh melebihi tahun berjalan.',

        'nomor_ijazah.max' =>
            'Nomor peserta atau bukti kelulusan maksimal 100 karakter.',
    ];

    /**
     * Memperbarui data pendidikan.
     */
    public function update()
    {
        // Periksa status sebelum validasi dan penyimpanan.
        $pendaftaran = $this->ambilPendaftaran();

        $this->pastikanBolehUbah($pendaftaran);

        $data = $this->validate();

        DB::transaction(function () use ($data) {
            $pendaftaran = PPDBPendaftaran::query()
                ->whereKey($this->pendaftaranId)
                ->where('ms_pengguna_id', Auth::id())
                ->lockForUpdate()
                ->first();

            if (! $pendaftaran) {
                throw ValidationException::withMessages([
                    'asal_sekolah' => 'Pendaftaran tidak ditemukan atau bukan milik Anda.',
                ]);
            }

            // Periksa kembali status di dalam transaksi.
            $this->pastikanBolehUbah($pendaftaran);

            $pendidikan = PpdbPendidikan::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->lockForUpdate()
                ->first();

            if (! $pendidikan) {
                throw ValidationException::withMessages([
                    'asal_sekolah' => 'Data pendidikan tidak ditemukan. Silakan muat ulang halaman.',
                ]);
            }

            $tidakAdaSekolah =
                $data['jenjang_asal'] === 'TIDAK_ADA';

            $pendidikan->update([
                'jenjang_asal' => $data['jenjang_asal'],

                'asal_sekolah' => $tidakAdaSekolah
                    ? null
                    : (filled($data['asal_sekolah'])
                        ? trim($data['asal_sekolah'])
                        : null),
                
                'alamat_asal_sekolah' => $tidakAdaSekolah
                    ? null
                    : (trim($this->alamat_asal_sekolah) ?: null),

                'npsn_asal_sekolah' => $tidakAdaSekolah
                    ? null
                    : (filled($data['npsn_asal_sekolah'])
                        ? trim($data['npsn_asal_sekolah'])
                        : null),

                'tahun_lulus' => $tidakAdaSekolah
                    ? null
                    : ($data['tahun_lulus'] ?: null),

                'nomor_ijazah' => $tidakAdaSekolah
                    ? null
                    : (filled($data['nomor_ijazah'])
                        ? trim($data['nomor_ijazah'])
                        : null),
            ]);
        });

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data pendidikan berhasil diperbarui.',
        ]);

        return redirect()->route(
            'ppdb.pendaftaran.pendidikan'
        );
    }
    
    public function render()
    {
        return view('livewire.p-p-d-b.pendaftaran.edit-pendidikan');
    }
}
