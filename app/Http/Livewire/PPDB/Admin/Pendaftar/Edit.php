<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use App\Models\PPDBAlamat;
use App\Models\PPDBBerkas;
use App\Models\PPDBCalonSiswa;
use App\Models\PPDBJalur;
use App\Models\PPDBJawaban;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPendidikan;
use App\Models\PPDBPertanyaan;
use App\Services\PPDB\PPDBAlamatService;
use App\Services\PPDB\PPDBBerkasService;
use App\Services\PPDB\PPDBDataSiswaService;
use App\Services\PPDB\PPDBOrangTuaService;
use App\Services\PPDB\PPDBPendidikanService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    // =========================================================
    // IDENTITAS PENDAFTARAN
    // =========================================================

    public $pendaftaranId = null;

    public $periodeId = null;

    public $jalurTerpilih = null;

    public $tabAktif = 'jalur';

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

    // =========================================================
    // ALAMAT
    // =========================================================

    public $alamat_lengkap = '';
    public $provinsi = '';
    public $kabupaten = '';
    public $kecamatan = '';
    public $kelurahan_desa = '';
    public $rt = '';
    public $rw = '';
    public $kode_pos = '';
    public $jenis_tempat_tinggal = '';

    // =========================================================
    // PERJALANAN KE SEKOLAH
    // =========================================================

    public $jarak_ke_sekolah = 0;
    public $waktu_tempuh_ke_sekolah = 5;
    public $moda_transportasi = '';

    // =========================================================
    // PENDIDIKAN
    // =========================================================

    public $jenjang_asal = 'TK';
    public $asal_sekolah = '';
    public $npsn_asal_sekolah = '';
    public $alamat_asal_sekolah = '';
    public $tahun_lulus = '';
    public $nomor_ijazah = '';

    // =========================================================
    // BERKAS BARU — UPLOAD PENGGANTI
    // =========================================================

    public $fileKartuKeluarga = null;
    public $fileAktaKelahiran = null;
    public $filePasFoto = null;
    public $fileDokumenPendukung = null;

    // =========================================================
    // BERKAS LAMA — UNTUK PREVIEW
    // =========================================================

    public $berkasKartuKeluarga = null;
    public $berkasAktaKelahiran = null;
    public $berkasPasFoto = null;
    public $berkasDokumenPendukung = null;
   
    // =========================================================
    // PERTANYAAN TAMBAHAN
    // =========================================================
    public $pertanyaan = [];
    public $jawaban = [];

    // =========================================================
    // LISTENER
    // =========================================================

    protected $listeners = [
        'bukaEditPendaftaran' => 'bukaEditPendaftaran',
    ];

    // =========================================================
    // PINDAH TAB
    // =========================================================

    public function pindahTab($tab)
    {
        $tabValid = [
            'jalur',
            'siswa',
            'orang-tua',
            'alamat',
            'pendidikan',
            'berkas',
            'pertanyaan',
        ];

        if (in_array($tab, $tabValid, true)) {
            $this->tabAktif = $tab;
        }
    }

    // =========================================================
    // BUKA FORM EDIT
    // =========================================================

    public function bukaEditPendaftaran($pendaftaranId)
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->resetFormEdit();

        $pendaftaran = PPDBPendaftaran::with([
            'ppdb_periode',
            'ppdb_gelombang',
            'ppdb_jalur',
            'ms_siswa',
            'ppdb_calon_siswa',
            'ppdb_orangtua',
            'ppdb_alamat',
            'ppdb_pendidikan',
            'ppdb_berkas',
        ])->find($pendaftaranId);

        if (!$pendaftaran) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Data pendaftaran tidak ditemukan.',
            ]);

            return;
        }

        // =========================================================
        // DATA UTAMA PENDAFTARAN
        // =========================================================

        $this->pendaftaranId = $pendaftaran->ppdb_pendaftaran_id;
        $this->periodeId = $pendaftaran->ppdb_periode_id;
        $this->jalurTerpilih = $pendaftaran->ppdb_jalur_id;

        // =========================================================
        // DATA CALON SISWA
        // =========================================================

        $calon = $pendaftaran->ppdb_calon_siswa;

        $this->isiPropertiDariModel($calon, [
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

        $this->tanggal_lahir = $calon?->tanggal_lahir
            ? \Carbon\Carbon::parse($calon->tanggal_lahir)->format('Y-m-d')
            : null;
            
        // Jika penerima bantuan sosial disimpan sebagai JSON/string,
        // ubah menjadi array untuk input checkbox Livewire.
        $bantuan = $this->penerima_bantuan_sosial;

        if (is_array($bantuan)) {
            $this->penerima_bantuan_sosial = $bantuan;
        } elseif (is_string($bantuan) && trim($bantuan) !== '') {
            $decoded = json_decode($bantuan, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Jika formatnya JSON array.
                $this->penerima_bantuan_sosial = $decoded;
            } else {
                // Jika formatnya CSV: KPS, PKH, KKS, KIP.
                $this->penerima_bantuan_sosial = array_values(
                    array_filter(
                        array_map('trim', explode(',', $bantuan)),
                        fn ($item) => $item !== ''
                    )
                );
            }
        } else {
            $this->penerima_bantuan_sosial = [];
        }

        $this->isiFormOrangTua($pendaftaran);

        // =========================================================
        // DATA ALAMAT
        // =========================================================

        $alamat = $pendaftaran->ppdb_alamat;

        $this->isiPropertiDariModel($alamat, [
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
        ]);

        // =========================================================
        // DATA PENDIDIKAN
        // =========================================================

        $pendidikan = $pendaftaran->ppdb_pendidikan;

        $this->isiPropertiDariModel($pendidikan, [
            'jenjang_asal',
            'asal_sekolah',
            'npsn_asal_sekolah',
            'alamat_asal_sekolah',
            'tahun_lulus',
            'nomor_ijazah',
        ]);

        // =========================================================
        // MUAT BERKAS LAMA
        // =========================================================

        $berkas = $pendaftaran->ppdb_berkas;

        $this->berkasKartuKeluarga = $berkas
            ->firstWhere('jenis_berkas', 'KARTU_KELUARGA');

        $this->berkasAktaKelahiran = $berkas
            ->firstWhere('jenis_berkas', 'AKTA_KELAHIRAN');

        $this->berkasPasFoto = $berkas
            ->firstWhere('jenis_berkas', 'PAS_FOTO');

        $this->berkasDokumenPendukung = $berkas
            ->firstWhere('jenis_berkas', 'DOKUMEN_PENDUKUNG');

        // =========================================================
        // PERTANYAAN TAMBAHAN
        // =========================================================
        $this->muatPertanyaanTambahan($pendaftaran);

        $this->dispatchBrowserEvent('buka-offcanvas-edit');
    }

    private function muatUlangBerkas(): void
    {
        if (! $this->pendaftaranId) {
            return;
        }

        $berkas = PPDBBerkas::query()
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->get();

        $this->berkasKartuKeluarga = $berkas
            ->firstWhere('jenis_berkas', 'KARTU_KELUARGA');

        $this->berkasAktaKelahiran = $berkas
            ->firstWhere('jenis_berkas', 'AKTA_KELAHIRAN');

        $this->berkasPasFoto = $berkas
            ->firstWhere('jenis_berkas', 'PAS_FOTO');

        $this->berkasDokumenPendukung = $berkas
            ->firstWhere('jenis_berkas', 'DOKUMEN_PENDUKUNG');
    }

    protected function isiFormOrangTua($pendaftaran)
    {
        $orangtua = $pendaftaran->ppdb_orangtua;

        // Belum ada data orang tua yang tersimpan.
        if (!$orangtua) {
            return;
        }

        // =========================================================
        // DATA AYAH
        // =========================================================

        $this->ayah_nama = $orangtua->nama_ayah ?? '';
        $this->ayah_nik = $orangtua->nik_ayah ?? '';
        $this->ayah_tempat_lahir = $orangtua->tempat_lahir_ayah ?? '';
        $this->ayah_tanggal_lahir = $orangtua->tanggal_lahir_ayah
            ? \Carbon\Carbon::parse($orangtua->tanggal_lahir_ayah)->format('Y-m-d')
            : '';

        $this->ayah_berkebutuhan_khusus =
            $orangtua->berkebutuhan_khusus_ayah ?? 'Tidak';

        $this->ayah_pendidikan = $orangtua->pendidikan_ayah ?? '';
        $this->ayah_pekerjaan = $orangtua->pekerjaan_ayah ?? '';
        $this->ayah_penghasilan = $orangtua->penghasilan_ayah ?? '';
        $this->ayah_whatsapp = $orangtua->telepon_ayah ?? '';

        // =========================================================
        // DATA IBU
        // =========================================================

        $this->ibu_nama = $orangtua->nama_ibu ?? '';
        $this->ibu_nik = $orangtua->nik_ibu ?? '';
        $this->ibu_tempat_lahir = $orangtua->tempat_lahir_ibu ?? '';
        $this->ibu_tanggal_lahir = $orangtua->tanggal_lahir_ibu
            ? \Carbon\Carbon::parse($orangtua->tanggal_lahir_ibu)->format('Y-m-d')
            : '';

        $this->ibu_berkebutuhan_khusus =
            $orangtua->berkebutuhan_khusus_ibu ?? 'Tidak';

        $this->ibu_pendidikan = $orangtua->pendidikan_ibu ?? '';
        $this->ibu_pekerjaan = $orangtua->pekerjaan_ibu ?? '';
        $this->ibu_penghasilan = $orangtua->penghasilan_ibu ?? '';
        $this->ibu_whatsapp = $orangtua->telepon_ibu ?? '';

        // =========================================================
        // DATA WALI
        // =========================================================

        $this->wali_nama = $orangtua->nama_wali ?? '';
        $this->wali_nik = $orangtua->nik_wali ?? '';
        $this->wali_tempat_lahir = $orangtua->tempat_lahir_wali ?? '';
        $this->wali_tanggal_lahir = $orangtua->tanggal_lahir_wali
            ? \Carbon\Carbon::parse($orangtua->tanggal_lahir_wali)->format('Y-m-d')
            : '';

        $this->wali_berkebutuhan_khusus =
            $orangtua->berkebutuhan_khusus_wali ?? 'Tidak';

        $this->wali_hubungan = $orangtua->hubungan_wali ?? '';
        $this->wali_pekerjaan = $orangtua->pekerjaan_wali ?? '';
        $this->wali_whatsapp = $orangtua->telepon_wali ?? '';

        // Aktifkan bagian wali jika sebelumnya sudah diisi.
        $this->gunakan_wali = !empty($orangtua->nama_wali);
    }

    /**
     * Memuat pertanyaan tambahan dan jawaban pendaftar.
     */
    private function muatPertanyaanTambahan(PPDBPendaftaran $pendaftaran): void
    {
        // Ambil semua pertanyaan aktif untuk periode pendaftaran ini.
        $pertanyaan = PPDBPertanyaan::query()
            ->where('ppdb_periode_id', $pendaftaran->ppdb_periode_id)
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get();

        $this->pertanyaan = $pertanyaan;

        // Siapkan jawaban kosong untuk seluruh pertanyaan aktif.
        $this->jawaban = $pertanyaan
            ->mapWithKeys(function ($item) {
                return [
                    $item->ppdb_pertanyaan_id => '',
                ];
            })
            ->all();

        // Muat jawaban yang sudah tersimpan.
        $jawabanTersimpan = PPDBJawaban::query()
            ->with('ppdb_pertanyaan')
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->where(
                'ppdb_periode_id',
                $pendaftaran->ppdb_periode_id
            )
            ->whereHas('ppdb_pertanyaan', function ($query) {
                $query->where('status', 'aktif');
            })
            ->get();

        // Masukkan jawaban lama ke properti Livewire berdasarkan ID pertanyaan.
        foreach ($jawabanTersimpan as $item) {
            if ($item->ppdb_pertanyaan) {
                $this->jawaban[$item->ppdb_pertanyaan->ppdb_pertanyaan_id]
                    = $item->jawaban;
            }
        }
    }

    protected function isiPropertiDariModel($model, array $properti)
    {
        if (!$model) {
            return;
        }

        $atribut = $model->getAttributes();

        foreach ($properti as $nama) {
            if (array_key_exists($nama, $atribut)) {
                $this->{$nama} = $model->{$nama};
            }
        }
    }

    // =========================================================
    // RESET FORM
    // =========================================================

    protected function resetFormEdit()
    {
        $this->reset([
            'pendaftaranId',
            'periodeId',
            'jalurTerpilih',
            'tabAktif',

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

            'pertanyaan',
            'jawaban',
        ]);

        // Pulihkan default yang berbeda dari nilai kosong.
        $this->tabAktif = 'jalur';
        $this->jenis_kelamin = 'Laki-Laki';
        $this->agama = 'Islam';
        $this->kewarganegaraan = 'Indonesia';

        $this->anak_ke = 1;
        $this->jumlah_saudara_kandung = 0;
        $this->jumlah_saudara_tiri = 0;

        $this->status_yatim_piatu = 'Tidak';
        $this->berkebutuhan_khusus = 'Tidak';

        $this->ayah_berkebutuhan_khusus = 'Tidak';
        $this->ibu_berkebutuhan_khusus = 'Tidak';
        $this->wali_berkebutuhan_khusus = 'Tidak';

        $this->gunakan_wali = false;

        $this->jarak_ke_sekolah = 0;
        $this->waktu_tempuh_ke_sekolah = 5;
        $this->jenjang_asal = 'TK';

        $this->pertanyaan = [];
        $this->jawaban = [];
    }


    protected function rulesEdit()
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
            // 'ukuran_baju' => [
            //     'required',
            //     Rule::in(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            // ],
            'ukuran_baju' => [
                // 'required',
                Rule::in(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            ],
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
            'ayah_nama' => [
                'required', 'string', 'max:150',
            ],
            'ayah_nik' => [
                'required', 'digits:16',
            ],
            'ayah_tempat_lahir' => [
                'nullable', 'string', 'max:100',
            ],
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
            'ayah_pekerjaan' => [
                'nullable', 'string', 'max:100',
            ],
            'ayah_penghasilan' => [
                'nullable',
                Rule::in([
                    'TIDAK_ADA', 'KURANG_1JT', '1JT_3JT',
                    '3JT_5JT', '5JT_7JT', 'LEBIH_7JT',
                ]),
            ],
            'ayah_whatsapp' => [
                'required', 'string', 'max:20',
            ],

            // =========================================================
            // DATA IBU
            // =========================================================
            'ibu_nama' => [
                'required', 'string', 'max:150',
            ],
            'ibu_nik' => [
                'required', 'digits:16',
            ],
            'ibu_tempat_lahir' => [
                'nullable', 'string', 'max:100',
            ],
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
            'ibu_pekerjaan' => [
                'nullable', 'string', 'max:100',
            ],
            'ibu_penghasilan' => [
                'nullable',
                Rule::in([
                    'TIDAK_ADA', 'KURANG_1JT', '1JT_3JT',
                    '3JT_5JT', '5JT_7JT', 'LEBIH_7JT',
                ]),
            ],
            'ibu_whatsapp' => [
                'required', 'string', 'max:20',
            ],

            // =========================================================
            // PENGATURAN WALI
            // =========================================================
            'gunakan_wali' => [
                'boolean',
            ],
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
            'wali_pekerjaan' => [
                'nullable', 'string', 'max:100',
            ],
            'wali_whatsapp' => [
                Rule::requiredIf($this->gunakan_wali),
                'nullable', 'string', 'max:20',
            ],

            // =========================================================
            // ALAMAT
            // =========================================================
            'alamat_lengkap' => [
                'required', 'string', 'max:1000',
            ],
            'provinsi' => [
                'required', 'string', 'max:100',
            ],
            'kabupaten' => [
                'required', 'string', 'max:100',
            ],
            'kecamatan' => [
                'required', 'string', 'max:100',
            ],
            'kelurahan_desa' => [
                'required', 'string', 'max:100',
            ],
            'rt' => [
                'nullable', 'digits_between:1,3',
            ],
            'rw' => [
                'nullable', 'digits_between:1,3',
            ],
            'kode_pos' => [
                'nullable', 'digits:5',
            ],
            'jenis_tempat_tinggal' => [
                'required',
                Rule::in([
                    'rumah_orang_tua',
                    'rumah_saudara',
                    'kos_kontrak',
                    'lainnya',
                ]),
            ],

            // =========================================================
            // INFORMASI PERJALANAN KE SEKOLAH
            // =========================================================
            'jarak_ke_sekolah' => [
                'required', 'numeric', 'min:0', 'max:999999.99',
            ],
            'waktu_tempuh_ke_sekolah' => [
                'required', 'integer', 'min:1', 'max:65535',
            ],
            'moda_transportasi' => [
                'required',
                Rule::in([
                    'jalan_kaki',
                    'sepeda',
                    'sepeda_motor',
                    'mobil',
                    'angkutan_umum',
                    'antar_jemput',
                    'lainnya',
                ]),
            ],

            // =========================================================
            // PENDIDIKAN SEBELUMNYA
            // =========================================================
            'asal_sekolah' => [
                'nullable', 'string', 'max:255',
            ],
            'alamat_asal_sekolah' => [
                'nullable', 'string', 'max:1000',
            ],
            'npsn_asal_sekolah' => [
                'nullable', 'regex:/^\d{8}$/',
            ],
            'jenjang_asal' => [
                'required',
                Rule::in(['TK', 'RA', 'PAUD', 'TIDAK_ADA']),
            ],
            'tahun_lulus' => [
                'nullable',
                'integer',
                'min:' . (date('Y') - 10),
                'max:' . date('Y'),
            ],
            'nomor_ijazah' => [
                'nullable', 'string', 'max:100',
            ],

            // =========================================================
            // BERKAS BARU (BERKAS LAMA TIDAK WAJIB DIUNGGAH ULANG)
            // =========================================================
            ...$this->rulesBerkas(),

            // =========================================================
            // JAWABAN PERTANYAAN TAMBAHAN
            // =========================================================
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
                    $rules[$field][] = Rule::in(['ya', 'tidak']);
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

        // 'ukuran_baju.required' => 'Ukuran baju siswa wajib dipilih.',
        'ukuran_baju.in' => 'Ukuran baju yang dipilih tidak valid.',

        'penerima_bantuan_sosial.array' => 'Pilihan bantuan sosial tidak valid.',
        'penerima_bantuan_sosial.*.required' => 'Pilihan bantuan sosial tidak boleh kosong.',
        'penerima_bantuan_sosial.*.in' => 'Jenis bantuan sosial tidak valid.',
        'nomor_bantuan_sosial.max' => 'Nomor bantuan sosial maksimal 100 karakter.',

        // =========================================================
        // DATA AYAH
        // =========================================================
        'ayah_nama.required' => 'Nama ayah wajib diisi.',
        'ayah_nama.string' => 'Nama ayah harus berupa teks.',
        'ayah_nama.max' => 'Nama ayah maksimal 150 karakter.',
        'ayah_nik.required' => 'NIK ayah wajib diisi.',
        'ayah_nik.digits' => 'NIK ayah harus terdiri dari 16 digit.',
        'ayah_whatsapp.required' => 'Nomor WhatsApp ayah wajib diisi.',
        'ayah_whatsapp.max' => 'Nomor WhatsApp ayah maksimal 20 karakter.',
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
        'fileKartuKeluarga.file' => 'Kartu Keluarga harus berupa file yang valid.',
        'fileKartuKeluarga.mimes' => 'Kartu Keluarga harus berupa JPG, JPEG, PNG, WebP, atau PDF.',
        'fileKartuKeluarga.max' => 'Ukuran Kartu Keluarga maksimal 2 MB.',

        'fileAktaKelahiran.file' => 'Akta Kelahiran harus berupa file yang valid.',
        'fileAktaKelahiran.mimes' => 'Akta Kelahiran harus berupa JPG, JPEG, PNG, WebP, atau PDF.',
        'fileAktaKelahiran.max' => 'Ukuran Akta Kelahiran maksimal 2 MB.',

        'filePasFoto.image' => 'Pas foto harus berupa gambar.',
        'filePasFoto.mimes' => 'Pas foto harus berupa JPG, JPEG, PNG, atau WebP.',
        'filePasFoto.max' => 'Ukuran pas foto maksimal 2 MB.',

        'fileDokumenPendukung.file' => 'Dokumen pendukung harus berupa file yang valid.',
        'fileDokumenPendukung.mimes' => 'Dokumen pendukung harus berupa JPG, JPEG, PNG, WebP, atau PDF.',
        'fileDokumenPendukung.max' => 'Ukuran dokumen pendukung maksimal 2 MB.',

        // =========================================================
        // PERTANYAAN TAMBAHAN
        // =========================================================
        'jawaban.*.required' => 'Pertanyaan ini wajib dijawab.',
        'jawaban.*.string' => 'Jawaban harus berupa teks.',
        'jawaban.*.numeric' => 'Jawaban harus berupa angka.',
        'jawaban.*.max' => 'Jawaban melebihi batas karakter yang diizinkan.',
        'jawaban.*.in' => 'Pilihan jawaban tidak valid.',

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
            if (str_starts_with($field, 'jawaban.')) {
                return true;
            }
        }

        return false;
    }

    // protected function adaIsian(array $data, array $fields): bool
    // {
    //     foreach ($fields as $field) {
    //         $value = $data[$field] ?? null;

    //         if (is_array($value)) {
    //             if (count(array_filter($value, fn ($item) => filled($item))) > 0) {
    //                 return true;
    //             }

    //             continue;
    //         }

    //         if (filled($value)) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

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

    public function simpanPerubahan()
    {
        // =========================================================
        // 1. VALIDASI INPUT
        // =========================================================

        $data = $this->validate($this->rulesEdit());

        $pendaftaranId = (int) $this->pendaftaranId;

        // =========================================================
        // 2. DAFTAR FILE DAN JENIS BERKAS
        // =========================================================

        $fileFields = [
            'fileKartuKeluarga' => 'KARTU_KELUARGA',
            'fileAktaKelahiran' => 'AKTA_KELAHIRAN',
            'filePasFoto' => 'PAS_FOTO',
            'fileDokumenPendukung' => 'DOKUMEN_PENDUKUNG',
        ];

        $fileBaru = [];
        $pathLama = [];

        try {
            // =====================================================
            // 3. PERIKSA PENDAFTARAN DAN BERKAS SAAT INI
            // =====================================================

            $pendaftaran = PPDBPendaftaran::query()
                ->where('ppdb_pendaftaran_id', $pendaftaranId)
                ->firstOrFail();

            $berkasTersimpan = PPDBBerkas::query()
                ->where('ppdb_pendaftaran_id', $pendaftaranId)
                ->get()
                ->keyBy('jenis_berkas');

            // Berkas wajib harus tersedia dari file lama atau baru.
            foreach ([
                'KARTU_KELUARGA',
                'AKTA_KELAHIRAN',
                'PAS_FOTO',
            ] as $jenis) {
                $property = array_search($jenis, $fileFields, true);

                if (
                    ! $this->{$property}
                    && ! $berkasTersimpan->has($jenis)
                ) {
                    throw ValidationException::withMessages([
                        $property => 'Berkas ini wajib tersedia.',
                    ]);
                }
            }

            // =====================================================
            // 4. SIMPAN FILE PENGGANTI TERLEBIH DAHULU
            // =====================================================

            foreach ($fileFields as $property => $jenis) {
                $file = $data[$property] ?? null;

                if (! $file) {
                    continue;
                }

                $fileBaru[$jenis] = PPDBBerkasService::simpanFile(
                    $file,
                    $pendaftaranId,
                    $jenis,
                    $property
                );
            }

            // =====================================================
            // 5. TRANSAKSI DATABASE
            // =====================================================

            DB::transaction(function () use (
                $pendaftaranId,
                $data,
                $fileBaru,
                $fileFields,
                &$pathLama
            ) {
                // Kunci pendaftaran selama proses pembaruan.
                $pendaftaran = PPDBPendaftaran::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaranId)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Jangan mengubah periode, gelombang, jalur, atau status.
                $periodeId = $pendaftaran->ppdb_periode_id;

                // Jalur boleh diubah oleh admin.
                $pendaftaran->ppdb_jalur_id = $this->jalurTerpilih;
                $pendaftaran->save();

                // -------------------------------------------------
                // 5A. PERBARUI DATA CALON SISWA
                // -------------------------------------------------

                $calonSiswa = PPDBCalonSiswa::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaranId)
                    ->lockForUpdate()
                    ->first();

                if ($calonSiswa) {
                    PPDBDataSiswaService::update(
                        $calonSiswa,
                        $data
                    );
                } else {
                    PPDBDataSiswaService::create(array_merge(
                        $data,
                        [
                            'ppdb_pendaftaran_id' => $pendaftaranId,
                        ]
                    ));
                }

                // -------------------------------------------------
                // 5B. PERBARUI DATA ORANG TUA / WALI
                // -------------------------------------------------

                $orangTua = PPDBOrangTua::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaranId)
                    ->lockForUpdate()
                    ->first();

                if ($orangTua) {
                    PPDBOrangTuaService::update(
                        $orangTua,
                        $data
                    );
                } else {
                    PPDBOrangTuaService::create(
                        $pendaftaranId,
                        $data
                    );
                }

                // -------------------------------------------------
                // 5C. PERBARUI ALAMAT
                // -------------------------------------------------

                $alamat = PPDBAlamat::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaranId)
                    ->lockForUpdate()
                    ->first();

                if ($alamat) {
                    PPDBAlamatService::update(
                        $alamat,
                        $data
                    );
                } else {
                    PPDBAlamatService::create(
                        $pendaftaranId,
                        $data
                    );
                }

                // -------------------------------------------------
                // 5D. PERBARUI PENDIDIKAN
                // -------------------------------------------------

                $pendidikan = PPDBPendidikan::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaranId)
                    ->lockForUpdate()
                    ->first();

                if ($pendidikan) {
                    PPDBPendidikanService::update(
                        $pendidikan,
                        $data
                    );
                } else {
                    PPDBPendidikanService::create(
                        $pendaftaranId,
                        $data
                    );
                }

                // -------------------------------------------------
                // 5E. PERBARUI BERKAS
                // -------------------------------------------------

                $berkasTerkini = PPDBBerkas::query()
                    ->where('ppdb_pendaftaran_id', $pendaftaranId)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('jenis_berkas');

                // Periksa ulang berkas wajib di dalam transaksi.
                foreach ([
                    'KARTU_KELUARGA',
                    'AKTA_KELAHIRAN',
                    'PAS_FOTO',
                ] as $jenis) {
                    $property = array_search($jenis, $fileFields, true);

                    if (
                        ! isset($fileBaru[$jenis])
                        && ! $berkasTerkini->has($jenis)
                    ) {
                        throw ValidationException::withMessages([
                            $property => 'Berkas ini wajib tersedia.',
                        ]);
                    }
                }

                foreach ($fileBaru as $jenis => $metadata) {
                    $berkas = PPDBBerkas::query()
                        ->where('ppdb_pendaftaran_id', $pendaftaranId)
                        ->where('jenis_berkas', $jenis)
                        ->lockForUpdate()
                        ->first();

                    if ($berkas && $berkas->path_file) {
                        $pathLama[] = $berkas->path_file;
                    }

                    logger()->info('PPDB update berkas', [
                        'jenis_berkas' => $jenis,
                        'path_file_baru' => $metadata['path_file'] ?? null,
                        'nama_file_baru' => $metadata['nama_file'] ?? null,
                        'path_file_lama' => $berkas?->path_file,
                    ]);

                    if ($berkas) {
                        PPDBBerkasService::update(
                            $berkas,
                            $metadata
                        );
                    } else {
                        PPDBBerkasService::create(
                            $pendaftaranId,
                            $jenis,
                            $metadata
                        );
                    }
                }

                // -------------------------------------------------
                // 5F. PERBARUI JAWABAN PERTANYAAN TAMBAHAN
                // -------------------------------------------------

                // Ambil pertanyaan aktif terbaru untuk periode ini.
                $pertanyaanAktif = PPDBPertanyaan::query()
                    ->where('ppdb_periode_id', $periodeId)
                    ->where('status', 'aktif')
                    ->orderBy('urutan')
                    ->get()
                    ->map(function ($item) {
                        $item->opsi_jawaban = is_array(
                            $item->opsi_jawaban
                        )
                            ? $item->opsi_jawaban
                            : (
                                json_decode(
                                    $item->opsi_jawaban ?? '[]',
                                    true
                                ) ?: []
                            );

                        return $item;
                    });

                $this->pertanyaan = $pertanyaanAktif;

                // Validasi kembali jawaban terhadap pertanyaan aktif terbaru.
                $this->validate($this->aturanJawaban());

                foreach ($pertanyaanAktif as $item) {
                    $pertanyaanId = $item->ppdb_pertanyaan_id;

                    $nilai = $this->jawaban[$pertanyaanId] ?? null;

                    if (is_string($nilai)) {
                        $nilai = trim($nilai);
                    }

                    if ($nilai === '') {
                        $nilai = null;
                    }

                    $kondisi = [
                        'ppdb_periode_id' => $periodeId,
                        'ppdb_pendaftaran_id' => $pendaftaranId,
                        'ppdb_pertanyaan_id' => $pertanyaanId,
                    ];

                    // Jawaban opsional yang dikosongkan dihapus.
                    if ($nilai === null && ! $item->wajib_diisi) {
                        PPDBJawaban::query()
                            ->where($kondisi)
                            ->delete();

                        continue;
                    }

                    PPDBJawaban::updateOrCreate(
                        $kondisi,
                        ['jawaban' => $nilai]
                    );
                }
            }, 3);

            // =====================================================
            // 6. TRANSAKSI BERHASIL: HAPUS FILE LAMA
            // =====================================================

        } catch (ValidationException $e) {
            // File baru dibersihkan jika validasi/transaksi gagal.
            foreach ($fileBaru as $metadata) {
                PPDBBerkasService::hapusFile(
                    $metadata['path_file'] ?? null
                );
            }

            throw $e;
        } catch (\Throwable $e) {
            foreach ($fileBaru as $metadata) {
                PPDBBerkasService::hapusFile(
                    $metadata['path_file'] ?? null
                );
            }

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Perubahan belum berhasil disimpan. Silakan coba kembali.',
            ]);

            return;
        }

        // Penghapusan file lama dilakukan setelah transaksi selesai.
        try {
            PPDBBerkasService::hapusFileLama($pathLama);
        } catch (\Throwable $e) {
            // Jangan menyatakan pembaruan database gagal jika hanya
            // penghapusan file lama yang mengalami kendala.
            report($e);
        }

        // =====================================================
        // 7. RESET INPUT FILE DAN NOTIFIKASI
        // =====================================================

        $this->muatUlangBerkas();
        
        foreach ($fileBaru as $metadata) {
            if (! empty($metadata['property'])) {
                $this->{$metadata['property']} = null;
            }
        }

        $this->resetValidation();

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Perubahan data pendaftar berhasil disimpan.',
        ]);

        $this->emit('pendaftaranTersimpan');
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
        return view('livewire.p-p-d-b.admin.pendaftar.edit',[
            'jalurTersedia' => $jalurTersedia,
        ]);
    }
}
