<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditOrangTua extends Component
{
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

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $this->pastikanBolehUbah($pendaftaran);

        $orangTua = PPDBOrangTua::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->firstOrFail();

        $this->isiForm($orangTua);
    }

    /**
     * Ambil pendaftaran milik pengguna yang sedang login.
     */
    protected function ambilPendaftaranMilikPengguna()
    {
        return PPDBPendaftaran::query()
            ->with('ppdb_periode.ms_jenjang')
            ->whereKey($this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->firstOrFail();
    }

    /**
     * Periksa apakah status pendaftaran mengizinkan perubahan data.
     */
    protected function bolehUbahData($status)
    {
        return ! in_array(
            strtolower(trim((string) $status)),
            ['diterima', 'pengecekan'],
            true
        );
    }

    protected function pastikanBolehUbah(PPDBPendaftaran $pendaftaran): void
    {
        if (! $this->bolehUbahData($pendaftaran->status)) {
            abort(
                403,
                'Pendaftaran tidak dapat diubah pada status saat ini.'
            );
        }
    }

    /**
     * Mengisi properti form dari data orang tua yang tersimpan.
     */
    protected function isiForm(PPDBOrangTua $orangTua)
    {
        // Data ayah
        $this->ayah_nama = $orangTua->nama_ayah ?? '';
        $this->ayah_nik = $orangTua->nik_ayah ?? '';
        $this->ayah_tempat_lahir = $orangTua->tempat_lahir_ayah ?? '';
        $this->ayah_tanggal_lahir = $orangTua->tanggal_lahir_ayah
            ? \Illuminate\Support\Carbon::parse(
                $orangTua->tanggal_lahir_ayah
            )->format('Y-m-d')
            : '';

        $this->ayah_berkebutuhan_khusus =
            $orangTua->berkebutuhan_khusus_ayah ?? 'Tidak';

        $this->ayah_pendidikan = $orangTua->pendidikan_ayah ?? '';
        $this->ayah_pekerjaan = $orangTua->pekerjaan_ayah ?? '';
        $this->ayah_penghasilan = $orangTua->penghasilan_ayah ?? '';
        $this->ayah_whatsapp = $orangTua->telepon_ayah ?? '';

        // Data ibu
        $this->ibu_nama = $orangTua->nama_ibu ?? '';
        $this->ibu_nik = $orangTua->nik_ibu ?? '';
        $this->ibu_tempat_lahir = $orangTua->tempat_lahir_ibu ?? '';
        $this->ibu_tanggal_lahir = $orangTua->tanggal_lahir_ibu
            ? \Illuminate\Support\Carbon::parse(
                $orangTua->tanggal_lahir_ibu
            )->format('Y-m-d')
            : '';

        $this->ibu_berkebutuhan_khusus =
            $orangTua->berkebutuhan_khusus_ibu ?? 'Tidak';

        $this->ibu_pendidikan = $orangTua->pendidikan_ibu ?? '';
        $this->ibu_pekerjaan = $orangTua->pekerjaan_ibu ?? '';
        $this->ibu_penghasilan = $orangTua->penghasilan_ibu ?? '';
        $this->ibu_whatsapp = $orangTua->telepon_ibu ?? '';

        // Data wali
        $this->gunakan_wali = filled($orangTua->nama_wali)
            || filled($orangTua->nik_wali)
            || filled($orangTua->hubungan_wali)
            || filled($orangTua->pekerjaan_wali)
            || filled($orangTua->telepon_wali)
            || filled($orangTua->tempat_lahir_wali)
            || filled($orangTua->tanggal_lahir_wali)
            || filled($orangTua->berkebutuhan_khusus_wali);

        $this->wali_hubungan = $orangTua->hubungan_wali ?? '';
        $this->wali_nama = $orangTua->nama_wali ?? '';
        $this->wali_nik = $orangTua->nik_wali ?? '';
        $this->wali_tempat_lahir = $orangTua->tempat_lahir_wali ?? '';
        $this->wali_tanggal_lahir = $orangTua->tanggal_lahir_wali
            ? \Illuminate\Support\Carbon::parse(
                $orangTua->tanggal_lahir_wali
            )->format('Y-m-d')
            : '';

        $this->wali_berkebutuhan_khusus =
            $orangTua->berkebutuhan_khusus_wali ?? 'Tidak';

        $this->wali_pekerjaan = $orangTua->pekerjaan_wali ?? '';
        $this->wali_whatsapp = $orangTua->telepon_wali ?? '';
    }

    protected function rules()
    {
        return [
            // Data ayah
            'ayah_nama' => ['required', 'string', 'max:150'],
            'ayah_nik' => ['nullable', 'digits:16'],
            'ayah_tempat_lahir' => ['nullable', 'string', 'max:100'],
            'ayah_tanggal_lahir' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'ayah_berkebutuhan_khusus' => [
                'required',
                Rule::in(['Ya', 'Tidak']),
            ],
            'ayah_pendidikan' => [
                'nullable',
                Rule::in([
                    'TIDAK_SEKOLAH',
                    'SD',
                    'SMP',
                    'SMA',
                    'D1_D2_D3',
                    'S1',
                    'S2',
                    'S3',
                ]),
            ],
            'ayah_pekerjaan' => ['nullable', 'string', 'max:100'],
            'ayah_penghasilan' => [
                'nullable',
                Rule::in([
                    'TIDAK_ADA',
                    'KURANG_1JT',
                    '1JT_3JT',
                    '3JT_5JT',
                    '5JT_7JT',
                    'LEBIH_7JT',
                ]),
            ],
            'ayah_whatsapp' => ['nullable', 'string', 'max:20'],

            // Data ibu
            'ibu_nama' => ['required', 'string', 'max:150'],
            'ibu_nik' => ['nullable', 'digits:16'],
            'ibu_tempat_lahir' => ['nullable', 'string', 'max:100'],
            'ibu_tanggal_lahir' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'ibu_berkebutuhan_khusus' => [
                'required',
                Rule::in(['Ya', 'Tidak']),
            ],
            'ibu_pendidikan' => [
                'nullable',
                Rule::in([
                    'TIDAK_SEKOLAH',
                    'SD',
                    'SMP',
                    'SMA',
                    'D1_D2_D3',
                    'S1',
                    'S2',
                    'S3',
                ]),
            ],
            'ibu_pekerjaan' => ['nullable', 'string', 'max:100'],
            'ibu_penghasilan' => [
                'nullable',
                Rule::in([
                    'TIDAK_ADA',
                    'KURANG_1JT',
                    '1JT_3JT',
                    '3JT_5JT',
                    '5JT_7JT',
                    'LEBIH_7JT',
                ]),
            ],
            'ibu_whatsapp' => ['nullable', 'string', 'max:20'],

            // Pengaturan wali
            'gunakan_wali' => ['boolean'],

            // Data wali
            'wali_hubungan' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable',
                Rule::in([
                    'KAKEK',
                    'NENEK',
                    'PAMAN',
                    'BIBI',
                    'SAUDARA',
                    'LAINNYA',
                ]),
            ],
            'wali_nama' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable',
                'string',
                'max:150',
            ],
            'wali_nik' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable',
                'digits:16',
            ],
            'wali_tempat_lahir' => [
                'nullable',
                'string',
                'max:100',
            ],
            'wali_tanggal_lahir' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'wali_berkebutuhan_khusus' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable',
                Rule::in(['Ya', 'Tidak']),
            ],
            'wali_pekerjaan' => ['nullable', 'string', 'max:100'],
            'wali_whatsapp' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable',
                'string',
                'max:20',
            ],
        ];
    }

    protected $messages = [
        // Data ayah
        'ayah_nama.required' => 'Nama ayah wajib diisi.',
        'ayah_nama.max' => 'Nama ayah maksimal 150 karakter.',
        'ayah_nik.digits' => 'NIK ayah harus terdiri dari 16 digit.',
        'ayah_tempat_lahir.max' => 'Tempat lahir ayah maksimal 100 karakter.',
        'ayah_tanggal_lahir.date' => 'Tanggal lahir ayah tidak valid.',
        'ayah_tanggal_lahir.before_or_equal' =>
            'Tanggal lahir ayah tidak boleh melebihi hari ini.',
        'ayah_berkebutuhan_khusus.required' =>
            'Pilihan kebutuhan khusus ayah wajib diisi.',
        'ayah_berkebutuhan_khusus.in' =>
            'Pilihan kebutuhan khusus ayah tidak valid.',

        // Data ibu
        'ibu_nama.required' => 'Nama ibu wajib diisi.',
        'ibu_nama.max' => 'Nama ibu maksimal 150 karakter.',
        'ibu_nik.digits' => 'NIK ibu harus terdiri dari 16 digit.',
        'ibu_tempat_lahir.max' => 'Tempat lahir ibu maksimal 100 karakter.',
        'ibu_tanggal_lahir.date' => 'Tanggal lahir ibu tidak valid.',
        'ibu_tanggal_lahir.before_or_equal' =>
            'Tanggal lahir ibu tidak boleh melebihi hari ini.',
        'ibu_berkebutuhan_khusus.required' =>
            'Pilihan kebutuhan khusus ibu wajib diisi.',
        'ibu_berkebutuhan_khusus.in' =>
            'Pilihan kebutuhan khusus ibu tidak valid.',

        // Data wali
        'wali_hubungan.required' => 'Hubungan dengan wali wajib dipilih.',
        'wali_nama.required' => 'Nama wali wajib diisi.',
        'wali_nik.required' => 'NIK wali wajib diisi.',
        'wali_nik.digits' => 'NIK wali harus terdiri dari 16 digit.',
        'wali_tempat_lahir.max' =>
            'Tempat lahir wali maksimal 100 karakter.',
        'wali_tanggal_lahir.date' => 'Tanggal lahir wali tidak valid.',
        'wali_tanggal_lahir.before_or_equal' =>
            'Tanggal lahir wali tidak boleh melebihi hari ini.',
        'wali_berkebutuhan_khusus.required' =>
            'Pilihan kebutuhan khusus wali wajib diisi.',
        'wali_berkebutuhan_khusus.in' =>
            'Pilihan kebutuhan khusus wali tidak valid.',
        'wali_whatsapp.required' => 'Nomor WhatsApp wali wajib diisi.',
        'wali_whatsapp.max' =>
            'Nomor WhatsApp wali maksimal 20 karakter.',
    ];

    /**
     * Bersihkan data wali ketika opsi penggunaan wali dinonaktifkan.
     */
    public function updatedGunakanWali($value)
    {
        if (! $value) {
            $this->reset([
                'wali_hubungan',
                'wali_nama',
                'wali_nik',
                'wali_tempat_lahir',
                'wali_tanggal_lahir',
                'wali_pekerjaan',
                'wali_whatsapp',
            ]);

            $this->wali_berkebutuhan_khusus = 'Tidak';

            $this->resetValidation([
                'wali_hubungan',
                'wali_nama',
                'wali_nik',
                'wali_tempat_lahir',
                'wali_tanggal_lahir',
                'wali_berkebutuhan_khusus',
                'wali_pekerjaan',
                'wali_whatsapp',
            ]);
        }
    }

    public function update()
    {
        // Pastikan pendaftaran masih milik pengguna dan boleh diedit.
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $this->pastikanBolehUbah($pendaftaran);

        $data = $this->validate();

        DB::transaction(function () use ($data) {
            $pendaftaran = PPDBPendaftaran::query()
                ->whereKey($this->pendaftaranId)
                ->where('ms_pengguna_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            // Periksa kembali status di dalam transaksi.
            if (! $this->bolehUbahData($pendaftaran->status)) {
                throw ValidationException::withMessages([
                    'ayah_nama' =>
                        'Status pendaftaran sudah berubah. Data tidak dapat diperbarui.',
                ]);
            }

            $orangTua = PPDBOrangTua::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->lockForUpdate()
                ->firstOrFail();

            // Simpan data ayah
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

            // Simpan data ibu
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

            // Simpan atau kosongkan data wali
            if ($data['gunakan_wali']) {
                $orangTua->nama_wali = $data['wali_nama'];
                $orangTua->nik_wali = $data['wali_nik'] ?: null;
                $orangTua->hubungan_wali = $data['wali_hubungan'];
                $orangTua->tempat_lahir_wali =
                    $data['wali_tempat_lahir'] ?: null;
                $orangTua->tanggal_lahir_wali =
                    $data['wali_tanggal_lahir'] ?: null;
                $orangTua->berkebutuhan_khusus_wali =
                    $data['wali_berkebutuhan_khusus'];
                $orangTua->pekerjaan_wali =
                    $data['wali_pekerjaan'] ?: null;
                $orangTua->telepon_wali =
                    $data['wali_whatsapp'] ?: null;
            } else {
                $orangTua->nama_wali = null;
                $orangTua->nik_wali = null;
                $orangTua->hubungan_wali = null;
                $orangTua->tempat_lahir_wali = null;
                $orangTua->tanggal_lahir_wali = null;
                $orangTua->berkebutuhan_khusus_wali = null;
                $orangTua->pekerjaan_wali = null;
                $orangTua->telepon_wali = null;
            }

            $orangTua->save();
        });

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data orang tua berhasil diperbarui.',
        ]);

        return redirect()->route('ppdb.pendaftaran.orang-tua', [
            'jenjang' => $slugJenjang,
        ]);
    }
    
    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        return view('livewire.p-p-d-b.pendaftaran.edit-orang-tua', [
            'pendaftaran' => $pendaftaran,
        ]);
    }
}
