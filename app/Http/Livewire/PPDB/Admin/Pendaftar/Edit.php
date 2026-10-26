<?php

namespace App\Http\Livewire\PPDB\Admin\Pendaftar;

use App\Models\PPDBJalur;
use App\Models\PPDBPendaftaran;
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

    public $pernyataan = false;

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
        // Pemetaan jawaban dilengkapi setelah relasi/model jawaban
        // pertanyaan tambahan diketahui.

        $this->dispatchBrowserEvent('buka-offcanvas-edit');
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
            'pernyataan',
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
        $this->pernyataan = false;
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
