<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use App\Models\PPDBGelombang;
use App\Models\PPDBPeriode;
use App\Models\PPDBJalur;
use App\Models\PPDBJawaban;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPertanyaan;
use App\Services\PPDB\PPDBPendaftaranService;
use App\Services\PPDB\PPDBDataSiswaService;
use App\Services\PPDB\PPDBOrangTuaService;
use App\Services\PPDB\PPDBAlamatService;
use App\Services\PPDB\PPDBPendidikanService;
use App\Services\PPDB\PPDBBerkasService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;

use Livewire\Component;

class Create extends Component
{
    // Di dalam class Create:
    use WithFileUploads;

    public $tabAktif = 'jalur';

    public $periodeId = null;

    public $jalurTerpilih = null;

    // =========================================================
    // DATA CALON SISWA
    // =========================================================
    public $nama_siswa = '';
    public $nama_panggilan = '';
    public $nik = '';
    public $nisn = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $jenis_kelamin = 'Laki-Laki';
    public $agama = 'Islam';
    public $kewarganegaraan = 'Indonesia';
    public $cita_cita = '';
    public $hobi = '';

    public $anak_ke = 1;
    public $jumlah_saudara_kandung = 0;
    public $jumlah_saudara_tiri = 0;
    public $status_yatim_piatu = 'Tidak';

    public $berkebutuhan_khusus = 'Tidak';
    public $penyakit = '';
    public $alergi = '';

    public $tinggi_badan = '';
    public $berat_badan = '';
    public $ukuran_baju = '';

    public $penerima_bantuan_sosial = [];
    public $nomor_bantuan_sosial = '';

    // =========================================================
    // DATA AYAH
    // =========================================================

    public $ayah_nama = '';
    public $ayah_nik = '';
    public $ayah_tempat_lahir = '';
    public $ayah_tanggal_lahir = '';
    public $ayah_berkebutuhan_khusus = 'Tidak';
    public $ayah_pendidikan = '';
    public $ayah_pekerjaan = '';
    public $ayah_penghasilan = '';
    public $ayah_whatsapp = '';

    // =========================================================
    // DATA IBU
    // =========================================================

    public $ibu_nama = '';
    public $ibu_nik = '';
    public $ibu_tempat_lahir = '';
    public $ibu_tanggal_lahir = '';
    public $ibu_berkebutuhan_khusus = 'Tidak';
    public $ibu_pendidikan = '';
    public $ibu_pekerjaan = '';
    public $ibu_penghasilan = '';
    public $ibu_whatsapp = '';

    // =========================================================
    // DATA WALI
    // =========================================================
    public $gunakan_wali = false;
    public $wali_hubungan = '';
    public $wali_nama = '';
    public $wali_nik = '';
    public $wali_tempat_lahir = '';
    public $wali_tanggal_lahir = '';
    public $wali_berkebutuhan_khusus = 'Tidak';
    public $wali_pekerjaan = '';
    public $wali_whatsapp = '';
    

    // ALAMAT
    public $alamat_lengkap = '';
    public $provinsi = '';
    public $kabupaten = '';
    public $kecamatan = '';
    public $kelurahan_desa = '';
    public $rt = '';
    public $rw = '';
    public $kode_pos = '';
    public $jenis_tempat_tinggal = '';

    /**
     * Data perjalanan calon siswa ke sekolah.
     */
    public $jarak_ke_sekolah = 0;
    public $waktu_tempuh_ke_sekolah = 5;
    public $moda_transportasi = '';

    // PENDIDIKAN
    public $jenjang_asal = 'TK';
    public $asal_sekolah = '';
    public $npsn_asal_sekolah = '';
    public $alamat_asal_sekolah = '';
    public $tahun_lulus = '';
    public $nomor_ijazah = '';


    // BERKAS
    public $fileKartuKeluarga = null;
    public $fileAktaKelahiran = null;
    public $filePasFoto = null;
    public $fileDokumenPendukung = null;

    // PERTANYAAN TAMBAHAN
    public $pertanyaan = [];
    public $jawaban = [];

    public $pernyataan = false;

    public function pindahTab($tab)
    {
        $tabValid = [
            'jalur',
            'siswa',
            'orang-tua',
            'alamat',
            'pendidikan',
            'berkas',
            'pertanyaan'
        ];

        if (in_array($tab, $tabValid, true)) {
            $this->tabAktif = $tab;
        }
    }

    protected $listeners = [
        'bukaBantuPendaftaran' => 'bukaForm',
    ];

    public function bukaForm($periodeId)
    {
        // Bersihkan validasi dan file dari form sebelumnya.
        $this->resetValidation();
        $this->resetErrorBag();

        $this->reset([
            'jalurTerpilih',

            'nama_siswa',
            'nama_panggilan',
            'nik',
            'nisn',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'kewarganegaraan',
            'cita_cita',
            'hobi',

            'anak_ke',
            'jumlah_saudara_kandung',
            'jumlah_saudara_tiri',
            'status_yatim_piatu',
            'berkebutuhan_khusus',
            'penyakit',
            'alergi',
            'tinggi_badan',
            'berat_badan',
            'ukuran_baju',
            'penerima_bantuan_sosial',
            'nomor_bantuan_sosial',

            'ayah_nama',
            'ayah_nik',
            'ayah_tempat_lahir',
            'ayah_tanggal_lahir',
            'ayah_berkebutuhan_khusus',
            'ayah_pendidikan',
            'ayah_pekerjaan',
            'ayah_penghasilan',
            'ayah_whatsapp',

            'ibu_nama',
            'ibu_nik',
            'ibu_tempat_lahir',
            'ibu_tanggal_lahir',
            'ibu_berkebutuhan_khusus',
            'ibu_pendidikan',
            'ibu_pekerjaan',
            'ibu_penghasilan',
            'ibu_whatsapp',

            'gunakan_wali',
            'wali_hubungan',
            'wali_nama',
            'wali_nik',
            'wali_tempat_lahir',
            'wali_tanggal_lahir',
            'wali_berkebutuhan_khusus',
            'wali_pekerjaan',
            'wali_whatsapp',

            'alamat_lengkap',
            'provinsi',
            'kabupaten',
            'kecamatan',
            'kelurahan_desa',
            'rt',
            'rw',
            'kode_pos',
            'jenis_tempat_tinggal',

            'jarak_ke_sekolah',
            'waktu_tempuh_ke_sekolah',
            'moda_transportasi',

            'jenjang_asal',
            'asal_sekolah',
            'npsn_asal_sekolah',
            'alamat_asal_sekolah',
            'tahun_lulus',
            'nomor_ijazah',

            'fileKartuKeluarga',
            'fileAktaKelahiran',
            'filePasFoto',
            'fileDokumenPendukung',
        ]);

        $periode = PPDBPeriode::query()
            ->whereKey($periodeId)
            ->where('status', 'aktif')
            ->first();

        if (! $periode) {
            $this->periodeId = null;

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Periode PPDB tidak tersedia atau sudah tidak aktif.',
            ]);

            return;
        }

        $this->periodeId = $periode->ppdb_periode_id;
        $this->muatPertanyaanTambahan($this->periodeId);
    }
    
    /**
     * Memuat pertanyaan tambahan berdasarkan periode PPDB.
     */
    public function muatPertanyaanTambahan($periodeId = null)
    {
        $periodeId = $periodeId ?? $this->periodeId;

        // Reset jika periode belum dipilih.
        if (empty($periodeId)) {
            $this->pertanyaan = [];
            $this->jawaban = [];

            return;
        }

        $this->pertanyaan = PPDBPertanyaan::query()
            ->where('ppdb_periode_id', $periodeId)
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get()
            ->map(function ($item) {
                $item->opsi_jawaban = is_array($item->opsi_jawaban)
                    ? $item->opsi_jawaban
                    : (json_decode($item->opsi_jawaban ?? '[]', true) ?: []);

                return $item;
            });

        // Siapkan jawaban tanpa menghapus jawaban yang sudah diisi.
        foreach ($this->pertanyaan as $item) {
            $id = $item->ppdb_pertanyaan_id;

            if (!array_key_exists($id, $this->jawaban)) {
                $this->jawaban[$id] = '';
            }
        }

        // Hapus jawaban dari pertanyaan yang sudah tidak aktif
        // atau bukan milik periode terpilih.
        $idAktif = collect($this->pertanyaan)
            ->pluck('ppdb_pertanyaan_id')
            ->map(fn ($id) => (string) $id)
            ->all();

        foreach (array_keys($this->jawaban) as $id) {
            if (!in_array((string) $id, $idAktif, true)) {
                unset($this->jawaban[$id]);
            }
        }
    }

    protected function rulesDraft()
    {
        return [
            // Parameter pendaftaran
            'periodeId' => [
                'required',
                'integer',
            ],
            'jalurTerpilih' => [
                'required',
                'integer',
            ],

            // =========================================================
            // DATA CALON SISWA
            // =========================================================
            'nama_siswa' => [
                'required', 'string', 'max:150',
            ],
            'nama_panggilan' => [
                'nullable', 'string', 'max:50',
            ],
            'cita_cita' => [
                'nullable', 'string', 'max:100',
            ],
            'hobi' => [
                'nullable', 'string', 'max:255',
            ],
            'kewarganegaraan' => [
                'required', 'string', 'max:50',
            ],

            'nik' => [
                'required', 'digits:16',
            ],
            'nisn' => [
                'nullable', 'digits:10',
            ],
            'tempat_lahir' => [
                'required', 'string', 'max:100',
            ],
            'tanggal_lahir' => [
                'required', 'date', 'before_or_equal:today',
            ],
            'jenis_kelamin' => [
                'required',
                Rule::in(['Laki-Laki', 'Perempuan']),
            ],
            'agama' => [
                'required',
                Rule::in([
                    'Islam',
                    'Kristen',
                    'Katolik',
                    'Hindu',
                    'Budha',
                    'Konghucu',
                ]),
            ],
            'anak_ke' => [
                'required', 'integer', 'min:1', 'max:100',
            ],

            'jumlah_saudara_kandung' => [
                'required', 'integer', 'min:0', 'max:100',
            ],
            'jumlah_saudara_tiri' => [
                'required', 'integer', 'min:0', 'max:100',
            ],
            'status_yatim_piatu' => [
                'required',
                Rule::in([
                    'Tidak',
                    'Yatim',
                    'Piatu',
                    'Yatim Piatu',
                ]),
            ],
            'berkebutuhan_khusus' => [
                'required',
                Rule::in(['Ya', 'Tidak']),
            ],

            'penyakit' => [
                'nullable', 'string', 'max:5000',
            ],
            'alergi' => [
                'nullable', 'string', 'max:5000',
            ],

            'tinggi_badan' => [
                'nullable', 'numeric', 'gt:0', 'max:300',
            ],
            'berat_badan' => [
                'nullable', 'numeric', 'gt:0', 'max:300',
            ],

            'ukuran_baju' => 'required|in:XS,S,M,L,XL,XXL',

            'penerima_bantuan_sosial' => [
                'nullable', 'array',
            ],
            'penerima_bantuan_sosial.*' => [
                'required',
                Rule::in(['KPS', 'KKS', 'PKH', 'KIP']),
            ],
            'nomor_bantuan_sosial' => [
                'nullable', 'string', 'max:100',
            ],

            // =========================================================
            // DATA AYAH
            // =========================================================
            'ayah_nama' => ['required', 'string', 'max:150'],
            'ayah_nik' => ['required', 'digits:16'],
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
            'ayah_whatsapp' => ['required', 'string', 'max:20'],

            // =========================================================
            // DATA IBU
            // =========================================================
            'ibu_nama' => ['required', 'string', 'max:150'],
            'ibu_nik' => ['required', 'digits:16'],
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
            'ibu_whatsapp' => ['required', 'string', 'max:20'],

            // =========================================================
            // PENGATURAN WALI
            // =========================================================
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

            // Alamat
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'provinsi' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kelurahan_desa' => ['required', 'string', 'max:100'],

            'rt' => ['nullable', 'digits_between:1,3'],
            'rw' => ['nullable', 'digits_between:1,3'],
            'kode_pos' => ['nullable', 'digits:5'],

            'jenis_tempat_tinggal' => [
                'required',
                'in:rumah_orang_tua,rumah_saudara,kos_kontrak,lainnya',
            ],

            // Informasi perjalanan ke sekolah
            'jarak_ke_sekolah' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],

            'waktu_tempuh_ke_sekolah' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],

            'moda_transportasi' => [
                'required',
                'in:jalan_kaki,sepeda,sepeda_motor,mobil,angkutan_umum,antar_jemput,lainnya',
            ],

            // Pendidikan sebelumnya
            'asal_sekolah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alamat_asal_sekolah' => [
                'nullable',
                'string',
                'max:1000',
            ],

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

            // Berkas
            ...$this->rulesBerkas(),

            // pertanyaan
            ...$this->aturanJawaban(),

        ];
    }

    protected function rulesBerkas()
    {
        return [
            'fileKartuKeluarga' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:2048',
            ],
            'fileAktaKelahiran' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:2048',
            ],
            'filePasFoto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'fileDokumenPendukung' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:2048',
            ],
        ];
    }

    /**
     * Aturan validasi jawaban pertanyaan tambahan.
     */
    protected function aturanJawaban()
    {
        $rules = [];

        foreach ($this->pertanyaan as $item) {
            $id = $item->ppdb_pertanyaan_id;
            $field = 'jawaban.' . $id;

            $rules[$field] = [
                $item->wajib_diisi ? 'required' : 'nullable',
            ];

            switch ($item->jenis) {
                case 'teks':
                    $rules[$field][] = 'string';
                    $rules[$field][] = 'max:255';
                    break;

                case 'textarea':
                    $rules[$field][] = 'string';
                    $rules[$field][] = 'max:5000';
                    break;

                case 'angka':
                    $rules[$field][] = 'numeric';
                    break;

                case 'pilihan':
                    $rules[$field][] = 'string';
                    $rules[$field][] = Rule::in(
                        $item->opsi_jawaban ?? []
                    );
                    break;

                case 'ya_tidak':
                    $rules[$field][] = Rule::in([
                        'ya',
                        'tidak',
                    ]);
                    break;
            }
        }

        return $rules;
    }

    protected $messages = [
        // =========================================================
        // PARAMETER PENDAFTARAN
        // =========================================================
        'periodeId.required' => 'Periode PPDB wajib dipilih.',
        'periodeId.integer' => 'Periode PPDB tidak valid.',
        'jalurTerpilih.required' => 'Jalur pendaftaran wajib dipilih.',
        'jalurTerpilih.integer' => 'Jalur pendaftaran tidak valid.',

        // =========================================================
        // DATA CALON SISWA
        // =========================================================
        'nama_siswa.required' => 'Nama lengkap calon siswa wajib diisi.',
        'nama_siswa.string' => 'Nama lengkap calon siswa harus berupa teks.',
        'nama_siswa.max' => 'Nama lengkap calon siswa maksimal 150 karakter.',

        'nama_panggilan.max' => 'Nama panggilan maksimal 50 karakter.',
        'cita_cita.max' => 'Cita-cita maksimal 100 karakter.',
        'hobi.max' => 'Hobi maksimal 255 karakter.',

        'kewarganegaraan.required' => 'Kewarganegaraan wajib diisi.',
        'kewarganegaraan.string' => 'Kewarganegaraan harus berupa teks.',
        'kewarganegaraan.max' => 'Kewarganegaraan maksimal 50 karakter.',

        'nik.required' => 'NIK calon siswa wajib diisi.',
        'nik.digits' => 'NIK calon siswa harus terdiri dari 16 digit.',

        'nisn.digits' => 'NISN harus terdiri dari 10 digit.',

        'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
        'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
        'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',

        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
        'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',

        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',

        'agama.required' => 'Agama wajib dipilih.',
        'agama.in' => 'Pilihan agama tidak valid.',

        'anak_ke.required' => 'Urutan anak wajib diisi.',
        'anak_ke.integer' => 'Urutan anak harus berupa angka.',
        'anak_ke.min' => 'Urutan anak minimal 1.',
        'anak_ke.max' => 'Urutan anak maksimal 100.',

        'jumlah_saudara_kandung.required' => 'Jumlah saudara kandung wajib diisi.',
        'jumlah_saudara_kandung.integer' => 'Jumlah saudara kandung harus berupa angka.',
        'jumlah_saudara_kandung.min' => 'Jumlah saudara kandung tidak boleh negatif.',
        'jumlah_saudara_kandung.max' => 'Jumlah saudara kandung maksimal 100.',

        'jumlah_saudara_tiri.required' => 'Jumlah saudara tiri wajib diisi.',
        'jumlah_saudara_tiri.integer' => 'Jumlah saudara tiri harus berupa angka.',
        'jumlah_saudara_tiri.min' => 'Jumlah saudara tiri tidak boleh negatif.',
        'jumlah_saudara_tiri.max' => 'Jumlah saudara tiri maksimal 100.',

        'status_yatim_piatu.required' => 'Status yatim/piatu wajib dipilih.',
        'status_yatim_piatu.in' => 'Pilihan status yatim/piatu tidak valid.',

        'berkebutuhan_khusus.required' => 'Status kebutuhan khusus wajib dipilih.',
        'berkebutuhan_khusus.in' => 'Pilihan status kebutuhan khusus tidak valid.',

        'penyakit.max' => 'Keterangan penyakit maksimal 5000 karakter.',
        'alergi.max' => 'Keterangan alergi maksimal 5000 karakter.',

        'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
        'tinggi_badan.gt' => 'Tinggi badan harus lebih dari 0.',
        'tinggi_badan.max' => 'Tinggi badan maksimal 300 cm.',

        'berat_badan.numeric' => 'Berat badan harus berupa angka.',
        'berat_badan.gt' => 'Berat badan harus lebih dari 0.',
        'berat_badan.max' => 'Berat badan maksimal 300 kg.',

        'ukuran_baju.required' => 'Ukuran baju siswa wajib dipilih.',
        'ukuran_baju.in' => 'Ukuran baju yang dipilih tidak valid.',

        'penerima_bantuan_sosial.array' => 'Pilihan bantuan sosial tidak valid.',
        'penerima_bantuan_sosial.*.required' => 'Pilihan bantuan sosial tidak boleh kosong.',
        'penerima_bantuan_sosial.*.in' => 'Jenis bantuan sosial tidak valid.',

        'nomor_bantuan_sosial.max' => 'Nomor bantuan sosial maksimal 100 karakter.',

        // =========================================================
        // DATA AYAH
        // =========================================================
        'ayah_nik.required' => 'NIK ayah wajib diisi.',
        'ayah_nik.digits' => 'NIK ayah harus terdiri dari 16 digit.',
        'ayah_whatsapp.required' => 'Nomor WhatsApp ayah wajib diisi.',
        'ayah_whatsapp.max' => 'Nomor WhatsApp ayah maksimal 20 karakter.',
        'ayah_nama.required' => 'Nama ayah wajib diisi.',
        'ayah_nama.string' => 'Nama ayah harus berupa teks.',
        'ayah_nama.max' => 'Nama ayah maksimal 150 karakter.',
        'ayah_tempat_lahir.max' => 'Tempat lahir ayah maksimal 100 karakter.',
        'ayah_tanggal_lahir.date' => 'Tanggal lahir ayah tidak valid.',
        'ayah_tanggal_lahir.before_or_equal' => 'Tanggal lahir ayah tidak boleh melebihi hari ini.',
        'ayah_berkebutuhan_khusus.required' => 'Pilih status kebutuhan khusus ayah.',
        'ayah_berkebutuhan_khusus.in' => 'Status kebutuhan khusus ayah tidak valid.',
        'ayah_pendidikan.in' => 'Pilihan pendidikan ayah tidak valid.',
        'ayah_pekerjaan.max' => 'Pekerjaan ayah maksimal 100 karakter.',
        'ayah_penghasilan.in' => 'Pilihan penghasilan ayah tidak valid.',

        // =========================================================
        // DATA IBU
        // =========================================================
        'ibu_nama.required' => 'Nama ibu wajib diisi.',
        'ibu_nama.string' => 'Nama ibu harus berupa teks.',
        'ibu_nama.max' => 'Nama ibu maksimal 150 karakter.',
        'ibu_nik.required' => 'NIK ibu wajib diisi.',
        'ibu_nik.digits' => 'NIK ibu harus terdiri dari 16 digit.',
        'ibu_whatsapp.required' => 'Nomor WhatsApp ibu wajib diisi.',
        'ibu_whatsapp.max' => 'Nomor WhatsApp ibu maksimal 20 karakter.',
        'ibu_tempat_lahir.max' => 'Tempat lahir ibu maksimal 100 karakter.',
        'ibu_tanggal_lahir.date' => 'Tanggal lahir ibu tidak valid.',
        'ibu_tanggal_lahir.before_or_equal' => 'Tanggal lahir ibu tidak boleh melebihi hari ini.',
        'ibu_berkebutuhan_khusus.required' => 'Pilih status kebutuhan khusus ibu.',
        'ibu_berkebutuhan_khusus.in' => 'Status kebutuhan khusus ibu tidak valid.',
        'ibu_pendidikan.in' => 'Pilihan pendidikan ibu tidak valid.',
        'ibu_pekerjaan.max' => 'Pekerjaan ibu maksimal 100 karakter.',
        'ibu_penghasilan.in' => 'Pilihan penghasilan ibu tidak valid.',

        // =========================================================
        // PENGATURAN WALI
        // =========================================================
        'gunakan_wali.boolean' => 'Pilihan penggunaan wali tidak valid.',

        'wali_hubungan.required' => 'Hubungan dengan wali wajib dipilih.',
        'wali_hubungan.in' => 'Pilihan hubungan dengan wali tidak valid.',

        'wali_nama.required' => 'Nama wali wajib diisi.',
        'wali_nama.string' => 'Nama wali harus berupa teks.',
        'wali_nama.max' => 'Nama wali maksimal 150 karakter.',

        'wali_nik.required' => 'NIK wali wajib diisi.',
        'wali_nik.digits' => 'NIK wali harus terdiri dari 16 digit.',

        'wali_tempat_lahir.max' => 'Tempat lahir wali maksimal 100 karakter.',
        'wali_tanggal_lahir.date' => 'Tanggal lahir wali tidak valid.',
        'wali_tanggal_lahir.before_or_equal' => 'Tanggal lahir wali tidak boleh melebihi hari ini.',

        'wali_berkebutuhan_khusus.required' => 'Pilih status kebutuhan khusus wali.',
        'wali_berkebutuhan_khusus.in' => 'Status kebutuhan khusus wali tidak valid.',

        'wali_pekerjaan.max' => 'Pekerjaan wali maksimal 100 karakter.',

        'wali_whatsapp.required' => 'Nomor WhatsApp wali wajib diisi.',
        'wali_whatsapp.string' => 'Nomor WhatsApp wali harus berupa teks.',
        'wali_whatsapp.max' => 'Nomor WhatsApp wali maksimal 20 karakter.',


        // =========================================================
        // ALAMAT DAN PERJALANAN
        // =========================================================
        'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
        'alamat_lengkap.string' => 'Alamat lengkap harus berupa teks.',
        'alamat_lengkap.max' => 'Alamat lengkap maksimal 1000 karakter.',

        'provinsi.required' => 'Provinsi wajib diisi.',
        'provinsi.string' => 'Provinsi harus berupa teks.',
        'provinsi.max' => 'Nama provinsi maksimal 100 karakter.',

        'kabupaten.required' => 'Kabupaten/kota wajib diisi.',
        'kabupaten.string' => 'Kabupaten/kota harus berupa teks.',
        'kabupaten.max' => 'Nama kabupaten/kota maksimal 100 karakter.',

        'kecamatan.required' => 'Kecamatan wajib diisi.',
        'kecamatan.string' => 'Kecamatan harus berupa teks.',
        'kecamatan.max' => 'Nama kecamatan maksimal 100 karakter.',

        'kelurahan_desa.required' => 'Desa/kelurahan wajib diisi.',
        'kelurahan_desa.string' => 'Desa/kelurahan harus berupa teks.',
        'kelurahan_desa.max' => 'Nama desa/kelurahan maksimal 100 karakter.',

        'rt.digits_between' => 'RT harus terdiri dari 1 sampai 3 digit.',
        'rw.digits_between' => 'RW harus terdiri dari 1 sampai 3 digit.',
        'kode_pos.digits' => 'Kode pos harus terdiri dari 5 digit.',

        'jenis_tempat_tinggal.required' => 'Jenis tempat tinggal wajib dipilih.',
        'jenis_tempat_tinggal.in' => 'Jenis tempat tinggal tidak valid.',

        'jarak_ke_sekolah.required' => 'Jarak ke sekolah wajib diisi.',
        'jarak_ke_sekolah.numeric' => 'Jarak ke sekolah harus berupa angka.',
        'jarak_ke_sekolah.min' => 'Jarak ke sekolah tidak boleh negatif.',
        'jarak_ke_sekolah.max' => 'Jarak ke sekolah terlalu besar.',

        'waktu_tempuh_ke_sekolah.required' => 'Waktu tempuh ke sekolah wajib diisi.',
        'waktu_tempuh_ke_sekolah.integer' => 'Waktu tempuh harus berupa bilangan bulat dalam menit.',
        'waktu_tempuh_ke_sekolah.min' => 'Waktu tempuh minimal 1 menit.',
        'waktu_tempuh_ke_sekolah.max' => 'Waktu tempuh terlalu besar.',

        'moda_transportasi.required' => 'Moda transportasi wajib dipilih.',
        'moda_transportasi.in' => 'Pilihan moda transportasi tidak valid.',

        // =========================================================
        // DATA PENDIDIKAN
        // =========================================================
        'asal_sekolah.string' => 'Nama sekolah harus berupa teks.',
        'asal_sekolah.max' => 'Nama sekolah maksimal 255 karakter.',

        'alamat_asal_sekolah.string' => 'Alamat sekolah asal harus berupa teks.',
        'alamat_asal_sekolah.max' => 'Alamat sekolah asal maksimal 1000 karakter.',

        'npsn_asal_sekolah.regex' => 'NPSN harus terdiri dari 8 digit angka.',

        'jenjang_asal.required' => 'Jenjang pendidikan asal wajib dipilih.',
        'jenjang_asal.in' => 'Jenjang pendidikan asal tidak valid.',

        'tahun_lulus.integer' => 'Tahun lulus harus berupa angka.',
        'tahun_lulus.min' => 'Tahun lulus terlalu lama. Silakan periksa kembali.',
        'tahun_lulus.max' => 'Tahun lulus tidak boleh melebihi tahun berjalan.',

        'nomor_ijazah.string' => 'Nomor ijazah harus berupa teks.',
        'nomor_ijazah.max' => 'Nomor ijazah maksimal 100 karakter.',

        // =========================================================
        // BERKAS
        // =========================================================
        'fileKartuKeluarga.file' =>
            'Kartu Keluarga harus berupa file yang valid.',
        'fileKartuKeluarga.mimes' =>
            'Kartu Keluarga harus berupa JPG, JPEG, PNG, WebP, atau PDF.',
        'fileKartuKeluarga.max' =>
            'Ukuran Kartu Keluarga maksimal 2 MB.',

        'fileAktaKelahiran.file' =>
            'Akta Kelahiran harus berupa file yang valid.',
        'fileAktaKelahiran.mimes' =>
            'Akta Kelahiran harus berupa JPG, JPEG, PNG, WebP, atau PDF.',
        'fileAktaKelahiran.max' =>
            'Ukuran Akta Kelahiran maksimal 2 MB.',

        'filePasFoto.image' =>
            'Pas foto harus berupa gambar.',
        'filePasFoto.mimes' =>
            'Pas foto harus berupa JPG, JPEG, PNG, atau WebP.',
        'filePasFoto.max' =>
            'Ukuran pas foto maksimal 2 MB.',

        'fileDokumenPendukung.file' =>
            'Dokumen pendukung harus berupa file yang valid.',
        'fileDokumenPendukung.mimes' =>
            'Dokumen pendukung harus berupa JPG, JPEG, PNG, WebP, atau PDF.',
        'fileDokumenPendukung.max' =>
            'Ukuran dokumen pendukung maksimal 2 MB.',

        // PERTANYAAN TAMBAHAN 
        'jawaban.*.required' =>
            'Pertanyaan ini wajib dijawab.',

        'jawaban.*.string' =>
            'Jawaban harus berupa teks.',

        'jawaban.*.numeric' =>
            'Jawaban harus berupa angka.',

        'jawaban.*.max' =>
            'Jawaban melebihi batas karakter yang diizinkan.',

        'jawaban.*.in' =>
            'Pilihan jawaban tidak valid.',

        'pernyataan.accepted' =>
            'Anda harus menyetujui pernyataan kebenaran data.',
            
        // =========================================================
        // PESAN UMUM
        // =========================================================
        '*.string' => 'Isian harus berupa teks.',
        '*.integer' => 'Isian harus berupa angka bulat.',
        '*.numeric' => 'Isian harus berupa angka.',
        '*.date' => 'Format tanggal tidak valid.',
        '*.max' => 'Isian melebihi batas maksimal yang diperbolehkan.',
    ];

    /**
     * Memeriksa apakah terdapat error validasi
     * pada properti tertentu.
     */
    protected function tabMemilikiError(array $properti): bool
    {
        foreach ($properti as $field) {
            if ($this->getErrorBag()->has($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * TAB JALUR
     */
    public function errorTabJalur(): bool
    {
        return $this->tabMemilikiError([
            'jalurTerpilih',
        ]);
    }

    /**
     * TAB SISWA
     */
    public function errorTabSiswa(): bool
    {
        return $this->tabMemilikiError([
            'nama_siswa',
            'nama_panggilan',
            'nik',
            'nisn',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'kewarganegaraan',
            'cita_cita',
            'hobi',

            'anak_ke',
            'jumlah_saudara_kandung',
            'jumlah_saudara_tiri',
            'status_yatim_piatu',

            'berkebutuhan_khusus',
            'penyakit',
            'alergi',

            'tinggi_badan',
            'berat_badan',
            'ukuran_baju',

            'penerima_bantuan_sosial',
            'nomor_bantuan_sosial',
        ]);
    }

    /**
     * TAB ORANG TUA / WALI
     */
    public function errorTabOrangTua(): bool
    {
        return $this->tabMemilikiError([
            // Data ayah
            'ayah_nama',
            'ayah_nik',
            'ayah_tempat_lahir',
            'ayah_tanggal_lahir',
            'ayah_berkebutuhan_khusus',
            'ayah_pendidikan',
            'ayah_pekerjaan',
            'ayah_penghasilan',
            'ayah_whatsapp',

            // Data ibu
            'ibu_nama',
            'ibu_nik',
            'ibu_tempat_lahir',
            'ibu_tanggal_lahir',
            'ibu_berkebutuhan_khusus',
            'ibu_pendidikan',
            'ibu_pekerjaan',
            'ibu_penghasilan',
            'ibu_whatsapp',

            // Data wali
            'gunakan_wali',
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

    /**
     * TAB ALAMAT
     */
    public function errorTabAlamat(): bool
    {
        return $this->tabMemilikiError([
            'alamat_lengkap',
            'provinsi',
            'kabupaten',
            'kecamatan',
            'kelurahan_desa',
            'rt',
            'rw',
            'kode_pos',
            'jenis_tempat_tinggal',

            // Perjalanan calon siswa ke sekolah
            'jarak_ke_sekolah',
            'waktu_tempuh_ke_sekolah',
            'moda_transportasi',
        ]);
    }

    /**
     * TAB PENDIDIKAN
     */
    public function errorTabPendidikan(): bool
    {
        return $this->tabMemilikiError([
            'jenjang_asal',
            'asal_sekolah',
            'npsn_asal_sekolah',
            'alamat_asal_sekolah',
            'tahun_lulus',
            'nomor_ijazah',
        ]);
    }

    /**
     * TAB BERKAS
     */
    public function errorTabBerkas(): bool
    {
        return $this->tabMemilikiError([
            'fileKartuKeluarga',
            'fileAktaKelahiran',
            'filePasFoto',
            'fileDokumenPendukung',
        ]);
    }

    public function errorTabPertanyaan()
    {
        foreach (array_keys($this->getErrorBag()->toArray()) as $field) {
            if (
                $field === 'pernyataan' ||
                str_starts_with($field, 'jawaban.')
            ) {
                return true;
            }
        }

        return false;
    }


    protected function adaIsian(array $data, array $fields): bool
    {
        foreach ($fields as $field) {
            $value = $data[$field] ?? null;

            if (is_array($value)) {
                if (count(array_filter($value, fn ($item) => filled($item))) > 0) {
                    return true;
                }

                continue;
            }

            if (filled($value)) {
                return true;
            }
        }

        return false;
    }

    public function updatedFileKartuKeluarga()
    {
        $this->validateOnly(
            'fileKartuKeluarga',
            $this->rulesBerkas()
        );
    }

    public function updatedFileAktaKelahiran()
    {
        $this->validateOnly(
            'fileAktaKelahiran',
            $this->rulesBerkas()
        );
    }

    public function updatedFilePasFoto()
    {
        $this->validateOnly(
            'filePasFoto',
            $this->rulesBerkas()
        );
    }

    public function updatedFileDokumenPendukung()
    {
        $this->validateOnly(
            'fileDokumenPendukung',
            $this->rulesBerkas()
        );
    }

    public function simpanDraft()
    {
        // =========================================================
        // 1. VALIDASI INPUT DRAFT
        // =========================================================

        $data = $this->validate($this->rulesDraft());

        // =========================================================
        // 2. KELOMPOK FIELD UNTUK MENENTUKAN TAB YANG DIISI
        // =========================================================

        $fieldOrangTua = [
            'ayah_nama',
            'ayah_nik',
            'ayah_tempat_lahir',
            'ayah_tanggal_lahir',
            'ayah_pendidikan',
            'ayah_pekerjaan',
            'ayah_penghasilan',
            'ayah_whatsapp',

            'ibu_nama',
            'ibu_nik',
            'ibu_tempat_lahir',
            'ibu_tanggal_lahir',
            'ibu_pendidikan',
            'ibu_pekerjaan',
            'ibu_penghasilan',
            'ibu_whatsapp',
        ];

        $fieldAlamat = [
            'alamat_lengkap',
            'provinsi',
            'kabupaten',
            'kecamatan',
            'kelurahan_desa',
            'rt',
            'rw',
            'kode_pos',
            'jenis_tempat_tinggal',
            'jarak_ke_sekolah',
            'waktu_tempuh_ke_sekolah',
            'moda_transportasi',
        ];

        $fieldPendidikan = [
            'asal_sekolah',
            'npsn_asal_sekolah',
            'alamat_asal_sekolah',
            'tahun_lulus',
            'nomor_ijazah',
        ];

        $fileFields = [
            'fileKartuKeluarga' => 'KARTU_KELUARGA',
            'fileAktaKelahiran' => 'AKTA_KELAHIRAN',
            'filePasFoto' => 'PAS_FOTO',
            'fileDokumenPendukung' => 'DOKUMEN_PENDUKUNG',
        ];

        $uploadedPaths = [];
        $pendaftaran = null;

        try {
            // =====================================================
            // 3. TRANSAKSI DATABASE
            // =====================================================

            DB::transaction(function () use (
                &$uploadedPaths,
                &$pendaftaran,
                $data,
                $fieldOrangTua,
                $fieldAlamat,
                $fieldPendidikan,
                $fileFields
            ) {
                // -------------------------------------------------
                // 3A. VALIDASI ULANG PERIODE
                // -------------------------------------------------

                $periode = PPDBPeriode::query()
                    ->whereKey($data['periodeId'])
                    ->where('status', 'aktif')
                    ->lockForUpdate()
                    ->first();

                if (! $periode) {
                    throw ValidationException::withMessages([
                        'periodeId' => 'Periode PPDB tidak tersedia atau sudah tidak aktif.',
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


                // -------------------------------------------------
                // 3B. VALIDASI JALUR DAN KUOTA
                // -------------------------------------------------

                $jalur = PPDBJalur::query()
                    ->whereKey($data['jalurTerpilih'])
                    ->where('ppdb_periode_id', $periode->ppdb_periode_id)
                    ->where('status', 'Aktif')
                    ->lockForUpdate()
                    ->first();

                if (! $jalur) {
                    throw ValidationException::withMessages([
                        'jalurTerpilih' => 'Jalur pendaftaran tidak tersedia untuk periode ini.',
                    ]);
                }

                /*
                * Asumsi: kuota 0 atau null berarti tidak dibatasi.
                * Jika sistem Anda menganggap kuota 0 sebagai penuh,
                * ubah kondisi pengecekan ini.
                */
                if ((int) ($jalur->kuota ?? 0) > 0) {
                    $jumlahPendaftar = PPDBPendaftaran::query()
                        ->where('ppdb_jalur_id', $jalur->ppdb_jalur_id)
                        ->whereNotIn('status', [
                            'rejected',
                            'ditolak',
                            'cancelled',
                            'dibatalkan',
                        ])
                        ->count();

                    if ($jumlahPendaftar >= (int) $jalur->kuota) {
                        throw ValidationException::withMessages([
                            'jalurTerpilih' => 'Kuota jalur pendaftaran sudah penuh.',
                        ]);
                    }
                }

                // -------------------------------------------------
                // 3C. BUAT PENDAFTARAN BARU
                // -------------------------------------------------

                $pendaftaran = PPDBPendaftaranService::create([
                    'ms_pengguna_id' => null,
                    'ppdb_periode_id' => $periode->ppdb_periode_id,
                    'ppdb_jalur_id' => $jalur->ppdb_jalur_id,
                    'ppdb_gelombang_id' => $gelombang->ppdb_gelombang_id,                
                    'ms_siswa_id' => null,
                    'status' => 'pengecekan',
                    'current_step' => 1,
                    'created_by' => Auth::id(),
                ]);

                $pendaftaranId = $pendaftaran->ppdb_pendaftaran_id;

                // -------------------------------------------------
                // 3D. BUAT DATA CALON SISWA
                // -------------------------------------------------

                PPDBDataSiswaService::create(array_merge(
                    $data,
                    [
                        'ppdb_pendaftaran_id' => $pendaftaranId,
                    ]
                ));

                // -------------------------------------------------
                // 3E. BUAT DATA ORANG TUA / WALI JIKA ADA ISIAN
                // -------------------------------------------------

                $adaDataOrangTua = $this->adaIsian(
                    $data,
                    $fieldOrangTua
                );

                $adaDataWali = ! empty($data['gunakan_wali'])
                    && $this->adaIsian($data, [
                        'wali_hubungan',
                        'wali_nama',
                        'wali_nik',
                        'wali_tempat_lahir',
                        'wali_tanggal_lahir',
                        'wali_pekerjaan',
                        'wali_whatsapp',
                    ]);

                if ($adaDataOrangTua || $adaDataWali) {
                    PPDBOrangTuaService::create(
                        $pendaftaranId,
                        $data
                    );
                }

                // -------------------------------------------------
                // 3F. BUAT ALAMAT JIKA ADA ISIAN
                // -------------------------------------------------

                if ($this->adaIsian($data, $fieldAlamat)) {
                    PPDBAlamatService::create(
                        $pendaftaranId,
                        $data
                    );
                }

                // -------------------------------------------------
                // 3G. BUAT PENDIDIKAN JIKA ADA ISIAN
                // -------------------------------------------------

                if ($this->adaIsian($data, $fieldPendidikan)) {
                    PPDBPendidikanService::create(
                        $pendaftaranId,
                        $data
                    );
                }

                // -------------------------------------------------
                // 3H. SIMPAN BERKAS YANG DIUNGGAH
                // -------------------------------------------------

                foreach ($fileFields as $property => $jenisBerkas) {
                    $file = $data[$property] ?? null;

                    if (! $file) {
                        continue;
                    }

                    $metadata = PPDBBerkasService::simpanFile(
                        $file,
                        $pendaftaranId,
                        $jenisBerkas,
                        $property
                    );

                    /*
                    * Catat path segera setelah file berhasil dibuat,
                    * agar dapat dibersihkan jika transaksi gagal.
                    */
                    $uploadedPaths[] = $metadata['path_file'];

                    PPDBBerkasService::create(
                        $pendaftaranId,
                        $jenisBerkas,
                        $metadata
                    );
                }
                // -------------------------------------------------
                // 3I. SIMPAN JAWABAN PERTANYAAN TAMBAHAN
                // -------------------------------------------------

                // Ambil ulang pertanyaan aktif berdasarkan periode pendaftaran.
                $pertanyaanAktif = PPDBPertanyaan::query()
                    ->where(
                        'ppdb_periode_id',
                        $pendaftaran->ppdb_periode_id
                    )
                    ->where('status', 'aktif')
                    ->orderBy('urutan')
                    ->get()
                    ->map(function ($item) {
                        $item->opsi_jawaban = is_array($item->opsi_jawaban)
                            ? $item->opsi_jawaban
                            : (
                                json_decode($item->opsi_jawaban ?? '[]', true) ?: []
                            );

                        return $item;
                    })
                    ->all();

                // Gunakan pertanyaan terbaru untuk validasi.
                $this->pertanyaan = $pertanyaanAktif;

                // Validasi jawaban.
                // Simpan draft tidak mewajibkan persetujuan final.
                $this->validate($this->aturanJawaban());

                foreach ($pertanyaanAktif as $item) {
                    $id = $item->ppdb_pertanyaan_id;
                    $nilai = $this->jawaban[$id] ?? null;

                    if (is_string($nilai)) {
                        $nilai = trim($nilai);
                    }

                    if ($nilai === '') {
                        $nilai = null;
                    }

                    $kondisi = [
                        'ppdb_periode_id' => $pendaftaran->ppdb_periode_id,
                        'ppdb_pendaftaran_id' => $pendaftaran->ppdb_pendaftaran_id,
                        'ppdb_pertanyaan_id' => $id,
                    ];

                    // Jawaban opsional kosong tidak perlu disimpan.
                    if ($nilai === null && ! $item->wajib_diisi) {
                        PPDBJawaban::query()
                            ->where($kondisi)
                            ->delete();

                        continue;
                    }

                    PPDBJawaban::updateOrCreate(
                        $kondisi, ['jawaban' => $nilai]
                    );
                }
            }, 3);

            // =====================================================
            // 4. TRANSAKSI BERHASIL
            // =====================================================

            // Reset form untuk pendaftaran berikutnya.
            $this->resetForm();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Draft pendaftaran berhasil disimpan.',
            ]);

            $this->emit('pendaftaranTersimpan');

            /*
            * Di sini pendaftaran sudah tersimpan.
            * Jangan menghapus uploadedPaths setelah transaksi berhasil.
            *
            * Form ini dapat tetap terbuka jika petugas ingin segera
            * membantu pendaftaran lain; gunakan resetForm() jika
            * ingin mengosongkan seluruh isian setelah penyimpanan.
            */
        } catch (ValidationException $e) {
            // Bersihkan file baru jika validasi dalam transaksi gagal.
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        } catch (\Throwable $e) {
            // =====================================================
            // 5. TRANSAKSI GAGAL: BERSIHKAN FILE BARU
            // =====================================================

            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Draft belum berhasil disimpan. Silakan coba kembali.',
            ]);
        }
    }

    public function resetForm()
    {
        $this->resetExcept('periodeId');

        $this->tabAktif = 'jalur';
        $this->jalurTerpilih = null;

        // Muat kembali pertanyaan untuk periode aktif
        $this->muatPertanyaanTambahan($this->periodeId);
    }

    public function render()
    {
        $periode = null;
        $jalurTersedia = collect();
        $kuota = 0;

        if ($this->periodeId) {
            $periode = PPDBPeriode::query()
                ->with([
                    'ms_jenjang',
                    'ms_tahun_ajar',
                ])
                ->whereKey($this->periodeId)
                ->where('status', 'aktif')
                ->first();

            if ($periode) {
                $jalurTersedia = PPDBJalur::query()
                    ->where(
                        'ppdb_periode_id',
                        $periode->ppdb_periode_id
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

                $kuota = $jalurTersedia->sum(function ($jalur) {
                    return $jalur->kuota ?? 0;
                });
            } else {
                // Periode mungkin dinonaktifkan setelah form dibuka.
                $this->periodeId = null;
                $this->jalurTerpilih = null;
            }
        }

        return view('livewire.p-p-d-b.admin.pendaftar.create',
            compact('periode', 'jalurTersedia', 'kuota')
        );
    }
}
