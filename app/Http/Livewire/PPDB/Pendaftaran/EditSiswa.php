<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBCalonSiswa;
use App\Models\PPDBPendaftaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditSiswa extends Component
{
    public $pendaftaranId;

    // Identitas siswa
    public $nama_siswa = '';
    public $nama_panggilan = '';
    public $nik = '';
    public $nisn = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $jenis_kelamin = '';
    public $agama = '';
    public $kewarganegaraan = 'Indonesia';
    public $anak_ke = 1;

    // Data keluarga
    public $jumlah_saudara_kandung = 0;
    public $jumlah_saudara_tiri = 0;
    public $status_yatim_piatu = 'Tidak';

    // Data pribadi dan kesehatan
    public $cita_cita = '';
    public $hobi = '';
    public $berkebutuhan_khusus = 'Tidak';
    public $penyakit = '';
    public $alergi = '';

    // Kondisi fisik
    public $tinggi_badan = '';
    public $berat_badan = '';

    // Bantuan sosial
    public $penerima_bantuan_sosial = [];
    public $nomor_bantuan_sosial = '';

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $this->pastikanBolehUbah($pendaftaran);

        $siswa = PPDBCalonSiswa::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->firstOrFail();

        $this->isiForm($siswa);
    }

    /**
     * Mengambil pendaftaran milik pengguna yang sedang login.
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
     * Mengisi form dari data yang sudah tersimpan.
     */
    protected function isiForm(PPDBCalonSiswa $siswa)
    {
        // Identitas siswa
        $this->nama_siswa = $siswa->nama_siswa ?? '';
        $this->nama_panggilan = $siswa->nama_panggilan ?? '';
        $this->nik = $siswa->nik ?? '';
        $this->nisn = $siswa->nisn ?? '';
        $this->tempat_lahir = $siswa->tempat_lahir ?? '';

        $this->tanggal_lahir = $siswa->tanggal_lahir
            ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d')
            : '';

        $this->jenis_kelamin = $siswa->jenis_kelamin ?? '';
        $this->agama = $siswa->agama ?? '';
        $this->kewarganegaraan = $siswa->kewarganegaraan ?? 'Indonesia';
        $this->anak_ke = $siswa->anak_ke ?? 1;

        // Data keluarga
        $this->jumlah_saudara_kandung =  $siswa->jumlah_saudara_kandung ?? 0;

        $this->jumlah_saudara_tiri = $siswa->jumlah_saudara_tiri ?? 0;

        $this->status_yatim_piatu = $siswa->status_yatim_piatu ?? 'Tidak';

        // Data pribadi dan kesehatan
        $this->cita_cita = $siswa->cita_cita ?? '';
        $this->hobi = $siswa->hobi ?? '';
        $this->berkebutuhan_khusus = $siswa->berkebutuhan_khusus ?? 'Tidak';

        $this->penyakit = $siswa->penyakit ?? '';
        $this->alergi = $siswa->alergi ?? '';

        // Kondisi fisik
        $this->tinggi_badan = $siswa->tinggi_badan ?? '';
        $this->berat_badan = $siswa->berat_badan ?? '';

        // Bantuan sosial: teks menjadi array untuk checkbox
        $this->penerima_bantuan_sosial = array_values(
            array_filter(
                array_map(
                    'trim',
                    explode(',', $siswa->penerima_bantuan_sosial ?? '')
                ),
                fn ($kode) => in_array(
                    $kode,
                    ['KPS', 'KKS', 'PKH', 'KIP'],
                    true
                )
            )
        );

        $this->nomor_bantuan_sosial = $siswa->nomor_bantuan_sosial ?? '';
    }

    protected function rules()
    {
        return [
            // Identitas siswa
            'nama_siswa' => [
                'required',
                'string',
                'max:150',
            ],
            'nama_panggilan' => [
                'nullable',
                'string',
                'max:50',
            ],
            'nik' => [
                'required',
                'digits:16',
            ],
            'nisn' => [
                'nullable',
                'digits:10',
            ],
            'tempat_lahir' => [
                'required',
                'string',
                'max:100',
            ],
            'tanggal_lahir' => [
                'required',
                'date',
                'before_or_equal:today',
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
            'kewarganegaraan' => [
                'required',
                'string',
                'max:50',
            ],
            'anak_ke' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            // Data keluarga
            'jumlah_saudara_kandung' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'jumlah_saudara_tiri' => [
                'required',
                'integer',
                'min:0',
                'max:100',
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

            // Data pribadi dan kesehatan
            'cita_cita' => [
                'nullable',
                'string',
                'max:100',
            ],
            'hobi' => [
                'nullable',
                'string',
                'max:255',
            ],
            'berkebutuhan_khusus' => [
                'required',
                Rule::in(['Ya', 'Tidak']),
            ],
            'penyakit' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'alergi' => [
                'nullable',
                'string',
                'max:5000',
            ],

            // Kondisi fisik
            'tinggi_badan' => [
                'nullable',
                'numeric',
                'gt:0',
                'max:300',
            ],
            'berat_badan' => [
                'nullable',
                'numeric',
                'gt:0',
                'max:300',
            ],

            // Bantuan sosial
            'penerima_bantuan_sosial' => [
                'nullable',
                'array',
            ],
            'penerima_bantuan_sosial.*' => [
                'required',
                Rule::in(['KPS', 'KKS', 'PKH', 'KIP']),
            ],
            'nomor_bantuan_sosial' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }

    protected $messages = [
        'nama_siswa.required' => 'Nama lengkap wajib diisi.',
        'nama_siswa.max' => 'Nama lengkap maksimal 150 karakter.',

        'nama_panggilan.max' => 'Nama panggilan maksimal 50 karakter.',

        'nik.required' => 'NIK wajib diisi.',
        'nik.digits' => 'NIK harus terdiri dari 16 digit.',

        'nisn.digits' => 'NISN harus terdiri dari 10 digit.',

        'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
        'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',

        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
        'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',

        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',

        'agama.required' => 'Agama wajib dipilih.',
        'agama.in' => 'Pilihan agama tidak valid.',

        'kewarganegaraan.required' => 'Kewarganegaraan wajib diisi.',
        'kewarganegaraan.max' => 'Kewarganegaraan maksimal 50 karakter.',

        'anak_ke.required' => 'Urutan anak wajib diisi.',
        'anak_ke.integer' => 'Urutan anak harus berupa angka.',
        'anak_ke.min' => 'Urutan anak minimal 1.',
        'anak_ke.max' => 'Urutan anak maksimal 100.',

        'jumlah_saudara_kandung.required' =>
            'Jumlah saudara kandung wajib diisi.',
        'jumlah_saudara_kandung.integer' =>
            'Jumlah saudara kandung harus berupa angka.',
        'jumlah_saudara_kandung.min' =>
            'Jumlah saudara kandung tidak boleh kurang dari 0.',
        'jumlah_saudara_kandung.max' =>
            'Jumlah saudara kandung maksimal 100.',

        'jumlah_saudara_tiri.required' =>
            'Jumlah saudara tiri wajib diisi.',
        'jumlah_saudara_tiri.integer' =>
            'Jumlah saudara tiri harus berupa angka.',
        'jumlah_saudara_tiri.min' =>
            'Jumlah saudara tiri tidak boleh kurang dari 0.',
        'jumlah_saudara_tiri.max' =>
            'Jumlah saudara tiri maksimal 100.',

        'status_yatim_piatu.required' =>
            'Status yatim/piatu wajib dipilih.',
        'status_yatim_piatu.in' =>
            'Pilihan status yatim/piatu tidak valid.',

        'cita_cita.max' => 'Cita-cita maksimal 100 karakter.',
        'hobi.max' => 'Hobi maksimal 255 karakter.',

        'berkebutuhan_khusus.required' =>
            'Status berkebutuhan khusus wajib dipilih.',
        'berkebutuhan_khusus.in' =>
            'Pilihan status berkebutuhan khusus tidak valid.',

        'penyakit.max' => 'Informasi penyakit terlalu panjang.',
        'alergi.max' => 'Informasi alergi terlalu panjang.',

        'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
        'tinggi_badan.gt' => 'Tinggi badan harus lebih dari 0.',
        'tinggi_badan.max' => 'Tinggi badan maksimal 300 cm.',

        'berat_badan.numeric' => 'Berat badan harus berupa angka.',
        'berat_badan.gt' => 'Berat badan harus lebih dari 0.',
        'berat_badan.max' => 'Berat badan maksimal 300 kg.',

        'penerima_bantuan_sosial.array' =>
            'Format pilihan bantuan sosial tidak valid.',
        'penerima_bantuan_sosial.*.in' =>
            'Pilihan bantuan sosial tidak valid.',

        'nomor_bantuan_sosial.max' =>
            'Nomor bantuan sosial maksimal 100 karakter.',
    ];

    /**
     * Memperbarui data calon siswa.
     */
    public function update()
    {
        // Periksa status sebelum validasi dan penyimpanan.
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

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
                    'nama_siswa' =>
                        'Pendaftaran tidak ditemukan atau bukan milik Anda.',
                ]);
            }

            $siswa = PPDBCalonSiswa::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->lockForUpdate()
                ->firstOrFail();

            $bantuanSosial = $data['penerima_bantuan_sosial'] ?? [];

            $siswa->update([
                // Identitas siswa
                'nama_siswa' => $data['nama_siswa'],
                'nama_panggilan' => $data['nama_panggilan'] ?: null,
                'nik' => $data['nik'],
                'nisn' => $data['nisn'] ?: null,
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'agama' => $data['agama'],
                'kewarganegaraan' => $data['kewarganegaraan'],
                'anak_ke' => $data['anak_ke'],

                // Data keluarga
                'jumlah_saudara_kandung' =>
                    $data['jumlah_saudara_kandung'],
                'jumlah_saudara_tiri' =>
                    $data['jumlah_saudara_tiri'],
                'status_yatim_piatu' =>
                    $data['status_yatim_piatu'],

                // Data pribadi dan kesehatan
                'cita_cita' => $data['cita_cita'] ?: null,
                'hobi' => $data['hobi'] ?: null,
                'berkebutuhan_khusus' =>
                    $data['berkebutuhan_khusus'],
                'penyakit' => $data['penyakit'] ?: null,
                'alergi' => $data['alergi'] ?: null,

                // Kondisi fisik
                'tinggi_badan' => $data['tinggi_badan'] ?: null,
                'berat_badan' => $data['berat_badan'] ?: null,

                // Bantuan sosial
                'penerima_bantuan_sosial' => ! empty($bantuanSosial)
                    ? implode(', ', $bantuanSosial)
                    : null,

                'nomor_bantuan_sosial' =>
                    $data['nomor_bantuan_sosial'] ?: null,
            ]);
        });

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data calon siswa berhasil diperbarui.',
        ]);

        return redirect()->route('ppdb.pendaftaran.data-siswa', [
            'jenjang' => $slugJenjang,
        ]);
    }

    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        return view('livewire.p-p-d-b.pendaftaran.edit-siswa',[
            'pendaftaran' => $pendaftaran,
        ]);
    }
}
