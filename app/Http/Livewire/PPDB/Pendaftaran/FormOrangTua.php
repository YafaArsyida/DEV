<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBCalonSiswa;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
use App\Services\PPDB\PPDBOrangTuaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FormOrangTua extends Component
{
    public $periodeId;
    public $pendaftaranId;

    // Data ayah
    public $ayah_nama = '';
    public $ayah_nik = '';
    public $ayah_tempat_lahir = '';
    public $ayah_tanggal_lahir = '';
    public $ayah_berkebutuhan_khusus = 'Tidak';
    public $ayah_pendidikan = '';
    public $ayah_pekerjaan = '';
    public $ayah_penghasilan = '';
    public $ayah_whatsapp = '';

    // Data ibu
    public $ibu_nama = '';
    public $ibu_nik = '';
    public $ibu_tempat_lahir = '';
    public $ibu_tanggal_lahir = '';
    public $ibu_berkebutuhan_khusus = 'Tidak';
    public $ibu_pendidikan = '';
    public $ibu_pekerjaan = '';
    public $ibu_penghasilan = '';
    public $ibu_whatsapp = '';

    // Data wali
    public $gunakan_wali = false;
    public $wali_hubungan = '';
    public $wali_nama = '';
    public $wali_nik = '';
    public $wali_tempat_lahir = '';
    public $wali_tanggal_lahir = '';
    public $wali_berkebutuhan_khusus = 'Tidak';
    public $wali_pekerjaan = '';
    public $wali_whatsapp = '';

    protected function rules()
    {
        return [
            // Data ayah
            'ayah_nama' => ['required', 'string', 'max:150'],
            'ayah_nik' => ['nullable', 'digits:16'],
            'ayah_tempat_lahir' => ['nullable', 'string', 'max:100'],
            'ayah_tanggal_lahir' => [
                'nullable', 'date', 'before_or_equal:today',
            ],
            'ayah_berkebutuhan_khusus' => [
                'required', Rule::in(['Ya', 'Tidak']),
            ],
            'ayah_pendidikan' => [
                'nullable',
                Rule::in([
                    'TIDAK_SEKOLAH', 'SD', 'SMP', 'SMA',
                    'D1_D2_D3', 'S1', 'S2', 'S3',
                ]),
            ],
            'ayah_pekerjaan' => ['nullable', 'string', 'max:100'],
            'ayah_penghasilan' => [
                'nullable',
                Rule::in([
                    'TIDAK_ADA', 'KURANG_1JT', '1JT_3JT',
                    '3JT_5JT', '5JT_7JT', 'LEBIH_7JT',
                ]),
            ],
            'ayah_whatsapp' => ['nullable', 'string', 'max:20'],

            // Data ibu
            'ibu_nama' => ['required', 'string', 'max:150'],
            'ibu_nik' => ['nullable', 'digits:16'],
            'ibu_tempat_lahir' => ['nullable', 'string', 'max:100'],
            'ibu_tanggal_lahir' => [
                'nullable', 'date', 'before_or_equal:today',
            ],
            'ibu_berkebutuhan_khusus' => [
                'required', Rule::in(['Ya', 'Tidak']),
            ],
            'ibu_pendidikan' => [
                'nullable',
                Rule::in([
                    'TIDAK_SEKOLAH', 'SD', 'SMP', 'SMA',
                    'D1_D2_D3', 'S1', 'S2', 'S3',
                ]),
            ],
            'ibu_pekerjaan' => ['nullable', 'string', 'max:100'],
            'ibu_penghasilan' => [
                'nullable',
                Rule::in([
                    'TIDAK_ADA', 'KURANG_1JT', '1JT_3JT',
                    '3JT_5JT', '5JT_7JT', 'LEBIH_7JT',
                ]),
            ],
            'ibu_whatsapp' => ['nullable', 'string', 'max:20'],

            // Pengaturan wali
            'gunakan_wali' => ['boolean'],

            'wali_hubungan' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable',
                Rule::in([
                    'KAKEK', 'NENEK', 'PAMAN',
                    'BIBI', 'SAUDARA', 'LAINNYA',
                ]),
            ],
            'wali_nama' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable', 'string', 'max:150',
            ],
            'wali_nik' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable', 'digits:16',
            ],
            'wali_tempat_lahir' => [
                'nullable', 'string', 'max:100',
            ],
            'wali_tanggal_lahir' => [
                'nullable', 'date', 'before_or_equal:today',
            ],
            'wali_berkebutuhan_khusus' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable',
                Rule::in(['Ya', 'Tidak']),
            ],
            'wali_pekerjaan' => ['nullable', 'string', 'max:100'],
            'wali_whatsapp' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable', 'string', 'max:20',
            ],
        ];
    }

    protected $messages = [
        'ayah_nama.required' => 'Nama ayah wajib diisi.',
        'ayah_nama.max' => 'Nama ayah maksimal 150 karakter.',
        'ayah_nik.digits' => 'NIK ayah harus terdiri dari 16 digit.',
        'ayah_tempat_lahir.max' => 'Tempat lahir ayah maksimal 100 karakter.',
        'ayah_tanggal_lahir.date' => 'Tanggal lahir ayah tidak valid.',
        'ayah_tanggal_lahir.before_or_equal' => 'Tanggal lahir ayah tidak boleh melebihi hari ini.',
        'ayah_berkebutuhan_khusus.required' => 'Pilih status kebutuhan khusus ayah.',
        'ayah_berkebutuhan_khusus.in' => 'Status kebutuhan khusus ayah tidak valid.',

        'ibu_nama.required' => 'Nama ibu wajib diisi.',
        'ibu_nama.max' => 'Nama ibu maksimal 150 karakter.',
        'ibu_nik.digits' => 'NIK ibu harus terdiri dari 16 digit.',
        'ibu_tempat_lahir.max' => 'Tempat lahir ibu maksimal 100 karakter.',
        'ibu_tanggal_lahir.date' => 'Tanggal lahir ibu tidak valid.',
        'ibu_tanggal_lahir.before_or_equal' => 'Tanggal lahir ibu tidak boleh melebihi hari ini.',
        'ibu_berkebutuhan_khusus.required' => 'Pilih status kebutuhan khusus ibu.',
        'ibu_berkebutuhan_khusus.in' => 'Status kebutuhan khusus ibu tidak valid.',

        'wali_hubungan.required' => 'Hubungan dengan wali wajib dipilih.',
        'wali_nama.required' => 'Nama wali wajib diisi.',
        'wali_nik.required' => 'NIK wali wajib diisi.',
        'wali_nik.digits' => 'NIK wali harus terdiri dari 16 digit.',
        'wali_tempat_lahir.max' => 'Tempat lahir wali maksimal 100 karakter.',
        'wali_tanggal_lahir.date' => 'Tanggal lahir wali tidak valid.',
        'wali_tanggal_lahir.before_or_equal' => 'Tanggal lahir wali tidak boleh melebihi hari ini.',
        'wali_berkebutuhan_khusus.required' => 'Pilih status kebutuhan khusus wali.',
        'wali_berkebutuhan_khusus.in' => 'Status kebutuhan khusus wali tidak valid.',
        'wali_whatsapp.required' => 'Nomor WhatsApp wali wajib diisi.',
    ];

    public function mount($periodeId, $pendaftaranId)
    {
        $this->periodeId = $periodeId;
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        // Hindari form kosong jika data orang tua sudah tersimpan.
        $orangTua = PPDBOrangTua::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if ($orangTua) {
            $slugJenjang = PPDBPeriode::query()
                ->with('ms_jenjang')
                ->whereKey($pendaftaran->ppdb_periode_id)
                ->firstOrFail()
                ->ms_jenjang
                ->slug;

            return redirect()->route('ppdb.pendaftaran.orang-tua', [
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
        $data = $this->validate();

        DB::transaction(function () use ($data) {
            $pendaftaran = PPDBPendaftaran::query()
                ->whereKey($this->pendaftaranId)
                ->where('ms_pengguna_id', Auth::id())
                ->where('ppdb_periode_id', $this->periodeId)
                ->lockForUpdate()
                ->first();

            if (!$pendaftaran) {
                throw ValidationException::withMessages([
                    'ayah_nama' =>
                        'Pendaftaran tidak ditemukan. Silakan periksa kembali.',
                ]);
            }

            $sudahAda = PPDBOrangTua::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->exists();

            if ($sudahAda) {
                throw ValidationException::withMessages([
                    'ayah_nama' => 'Data orang tua sudah tersimpan.',
                ]);
            }

            PPDBOrangTuaService::create(
                $pendaftaran->ppdb_pendaftaran_id,
                $data
            );

            $pendaftaran->update([
                'current_step' => 3,
            ]);
        });

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data orang tua berhasil disimpan.',
        ]);

        return redirect()->route('ppdb.pendaftaran.alamat', [
            'jenjang' => $slugJenjang,
        ]);
    }

    
    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();
        return view('livewire.p-p-d-b.pendaftaran.form-orang-tua',[
            'pendaftaran' => $pendaftaran
        ]);
    }
}
