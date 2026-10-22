<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBAlamat;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendidikan;
use App\Models\PPDBPeriode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FormPendidikan extends Component
{
    public $periodeId;
    public $pendaftaranId;

    public $asal_sekolah = '';
    public $alamat_asal_sekolah = '';
    public $npsn_asal_sekolah = '';
    public $jenjang_asal = 'TK';
    public $tahun_lulus = '';
    public $nomor_ijazah = '';

    protected function rules()
    {
        return [
            'asal_sekolah' => 'nullable|string|max:255',
            'alamat_asal_sekolah' => 'nullable|string|max:1000',
            'npsn_asal_sekolah' => [
                'nullable',
                'regex:/^\d{8}$/',
            ],
            'jenjang_asal' => 'required|in:TK,RA,PAUD,TIDAK_ADA',
            'tahun_lulus' => [
                'nullable',
                'integer',
                'min:' . (date('Y') - 10),
                'max:' . date('Y'),
            ],
            'nomor_ijazah' => 'nullable|string|max:100',
        ];
    }

    protected $messages = [
        'asal_sekolah.max' => 'Nama sekolah maksimal 255 karakter.',

        'alamat_asal_sekolah.string' => 'Alamat TK harus berupa teks.',
        'alamat_asal_sekolah.max' => 'Alamat TK maksimal 1000 karakter.',

        'npsn_asal_sekolah.regex' => 'NPSN harus terdiri dari 8 digit angka.',

        'jenjang_asal.required' => 'Jenjang pendidikan asal wajib dipilih.',

        'jenjang_asal.in' => 'Jenjang pendidikan asal tidak valid.',

        'tahun_lulus.integer' => 'Tahun lulus harus berupa angka.',

        'tahun_lulus.min' => 'Tahun lulus terlalu lama. Silakan periksa kembali.',

        'tahun_lulus.max' => 'Tahun lulus tidak boleh melebihi tahun berjalan.',

        'nomor_ijazah.max' => 'Nomor ijazah maksimal 100 karakter.',
    ];

    public function mount($periodeId = null, $pendaftaranId = null)
    {
        $this->periodeId = $periodeId;
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        // Form awal hanya digunakan jika data belum tersedia.
        $pendidikan = PPDBPendidikan::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if ($pendidikan) {
            $slugJenjang = PPDBPeriode::query()
                ->with('ms_jenjang')
                ->whereKey($pendaftaran->ppdb_periode_id)
                ->firstOrFail()
                ->ms_jenjang
                ->slug;

            return redirect()->route('ppdb.pendaftaran.pendidikan',  [
                'jenjang' => $slugJenjang,
            ]);
        }
    }

    protected function ambilPendaftaranMilikPengguna()
    {
        $periode = PPDBPeriode::query()
            ->with('ms_jenjang')
            ->whereKey($this->periodeId)
            ->where('status', 'aktif')
            ->first();

        if (! $periode) {
            abort(403, 'Periode PPDB tidak aktif.');
        }

        $pendaftaran = PPDBPendaftaran::query()
            ->with('ppdb_periode.ms_jenjang')
            ->whereKey($this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->where('ppdb_periode_id', $periode->ppdb_periode_id)
            ->first();

        if (! $pendaftaran) {
            abort(403, 'Pendaftaran tidak ditemukan atau bukan milik Anda.');
        }

        return $pendaftaran;
    }

    public function simpan()
    {
        $this->validate();

        DB::transaction(function () {
            // Pastikan pendaftaran milik pengguna yang login.
            $pendaftaran = PPDBPendaftaran::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $this->pendaftaranId
                )
                ->where('ms_pengguna_id', Auth::id())
                ->where('ppdb_periode_id', $this->periodeId)
                ->lockForUpdate()
                ->first();

            if (! $pendaftaran) {
                throw ValidationException::withMessages([
                    'asal_sekolah' =>
                        'Pendaftaran tidak ditemukan. Silakan periksa kembali.',
                ]);
            }

            // Cegah data pendidikan ganda.
            $sudahAda = PPDBPendidikan::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->lockForUpdate()
                ->exists();

            if ($sudahAda) {
                throw ValidationException::withMessages([
                    'asal_sekolah' => 'Data pendidikan sudah tersimpan.',
                ]);
            }

            $tidakAdaSekolah = $this->jenjang_asal === 'TIDAK_ADA';

            PPDBPendidikan::create([
                'ppdb_pendaftaran_id' =>
                    $pendaftaran->ppdb_pendaftaran_id,

                'asal_sekolah' => $tidakAdaSekolah
                    ? null
                    : (trim($this->asal_sekolah) ?: null),

                'alamat_asal_sekolah' => $tidakAdaSekolah
                    ? null
                    : (trim($this->alamat_asal_sekolah) ?: null),
                    
                'npsn_asal_sekolah' => $tidakAdaSekolah
                    ? null
                    : (trim($this->npsn_asal_sekolah) ?: null),

                'jenjang_asal' => $this->jenjang_asal,

                'tahun_lulus' => $tidakAdaSekolah
                    ? null
                    : ($this->tahun_lulus ?: null),

                'nomor_ijazah' => $tidakAdaSekolah
                    ? null
                    : (trim($this->nomor_ijazah) ?: null),
            ]);
            $pendaftaran->update([
                'current_step' => 5,
            ]);
        });

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data pendidikan berhasil disimpan.',
        ]);

        return redirect()->route('ppdb.pendaftaran.berkas', [
            'jenjang' => $slugJenjang,
        ]);
    }
    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();
        
        return view('livewire.p-p-d-b.pendaftaran.form-pendidikan', [
            'pendaftaran' => $pendaftaran
        ]);
    }
}
