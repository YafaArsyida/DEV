<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBCalonSiswa;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
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

        $pendaftaran = PPDBPendaftaran::query()
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->where('ms_penguna_id', Auth::id())
            ->where('ppdb_periode_id', $this->periodeId)
            ->first();

        if (! $pendaftaran) {
            return redirect()->route('ppdb.pendaftaran.jalur');
        }

        // Data siswa wajib tersedia sebelum mengisi data orang tua.
        $adaDataSiswa = DB::table('ppdb_calon_siswa')
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->exists();

        if (! $adaDataSiswa) {
            return redirect()->route('ppdb.pendaftaran.jalur');
        }

        // Hindari form kosong jika data orang tua sudah tersimpan.
        $orangTua = PPDBOrangTua::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if ($orangTua) {
            return redirect()->route('ppdb.pendaftaran.orang-tua');
        }
    }

    protected function ambilPendaftaranMilikPengguna()
    {
        $periode = PPDBPeriode::query()
            ->whereKey($this->periodeId)
            ->where('status', 'aktif')
            ->first();

        if (! $periode) {
            abort(403, 'Periode PPDB tidak aktif.');
        }

        $pendaftaran = PPDBPendaftaran::query()
            ->whereKey($this->pendaftaranId)
            ->where('ms_penguna_id', Auth::id())
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
                ->where('ms_penguna_id', Auth::id())
                ->where('ppdb_periode_id', $this->periodeId)
                ->lockForUpdate()
                ->first();

            if (! $pendaftaran) {
                throw ValidationException::withMessages([
                    'ayah_nama' => 'Pendaftaran tidak ditemukan. Silakan periksa kembali.',
                ]);
            }

            $periodeAktif = PPDBPeriode::query()
                ->whereKey($this->periodeId)
                ->where('status', 'aktif')
                ->exists();

            if (! $periodeAktif) {
                throw ValidationException::withMessages([
                    'ayah_nama' => 'Periode PPDB sudah tidak aktif.',
                ]);
            }

            $adaDataSiswa = PPDBCalonSiswa::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->exists();

            if (! $adaDataSiswa) {
                throw ValidationException::withMessages([
                    'ayah_nama' => 'Lengkapi data calon siswa terlebih dahulu.',
                ]);
            }

            $sudahAda = PPDBOrangtua::query()
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

            $orangTua = new PPDBOrangtua();

            $orangTua->ppdb_pendaftaran_id =
                $pendaftaran->ppdb_pendaftaran_id;

            // Data ayah
            $orangTua->nama_ayah = $data['ayah_nama'];
            $orangTua->nik_ayah = $data['ayah_nik'] ?: null;
            $orangTua->tempat_lahir_ayah =
                $data['ayah_tempat_lahir'] ?: null;
            $orangTua->tanggal_lahir_ayah =
                $data['ayah_tanggal_lahir'] ?: null;
            $orangTua->berkebutuhan_khusus_ayah =
                $data['ayah_berkebutuhan_khusus'];
            $orangTua->pendidikan_ayah =
                $data['ayah_pendidikan'] ?: null;
            $orangTua->pekerjaan_ayah =
                $data['ayah_pekerjaan'] ?: null;
            $orangTua->penghasilan_ayah =
                $data['ayah_penghasilan'] ?: null;
            $orangTua->telepon_ayah =
                $data['ayah_whatsapp'] ?: null;

            // Data ibu
            $orangTua->nama_ibu = $data['ibu_nama'];
            $orangTua->nik_ibu = $data['ibu_nik'] ?: null;
            $orangTua->tempat_lahir_ibu =
                $data['ibu_tempat_lahir'] ?: null;
            $orangTua->tanggal_lahir_ibu =
                $data['ibu_tanggal_lahir'] ?: null;
            $orangTua->berkebutuhan_khusus_ibu =
                $data['ibu_berkebutuhan_khusus'];
            $orangTua->pendidikan_ibu =
                $data['ibu_pendidikan'] ?: null;
            $orangTua->pekerjaan_ibu =
                $data['ibu_pekerjaan'] ?: null;
            $orangTua->penghasilan_ibu =
                $data['ibu_penghasilan'] ?: null;
            $orangTua->telepon_ibu =
                $data['ibu_whatsapp'] ?: null;

            // Data wali hanya disimpan jika diaktifkan.
            if ($this->gunakan_wali) {
                $orangTua->nama_wali = $data['wali_nama'];
                $orangTua->nik_wali = $data['wali_nik'];
                $orangTua->tempat_lahir_wali =
                    $data['wali_tempat_lahir'] ?: null;
                $orangTua->tanggal_lahir_wali =
                    $data['wali_tanggal_lahir'] ?: null;
                $orangTua->berkebutuhan_khusus_wali =
                    $data['wali_berkebutuhan_khusus'];
                $orangTua->hubungan_wali = $data['wali_hubungan'];
                $orangTua->pekerjaan_wali =
                    $data['wali_pekerjaan'] ?: null;
                $orangTua->telepon_wali = $data['wali_whatsapp'];
            } else {
                $orangTua->nama_wali = null;
                $orangTua->nik_wali = null;
                $orangTua->tempat_lahir_wali = null;
                $orangTua->tanggal_lahir_wali = null;
                $orangTua->berkebutuhan_khusus_wali = null;
                $orangTua->hubungan_wali = null;
                $orangTua->pekerjaan_wali = null;
                $orangTua->telepon_wali = null;
            }

            $orangTua->save();

            $pendaftaran->update([
                'current_step' => 4,
            ]);
        });

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data orang tua berhasil disimpan.',
        ]);

        return redirect()->route('ppdb.pendaftaran.alamat');
    }
    
    public function render()
    {
        return view('livewire.p-p-d-b.pendaftaran.form-orang-tua');
    }
}
