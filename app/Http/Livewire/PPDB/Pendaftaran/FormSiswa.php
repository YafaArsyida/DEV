<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBCalonSiswa;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
use App\Services\PPDB\PPDBDataSiswaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FormSiswa extends Component
{
    public $periodeId;
    public $pendaftaranId;

    public $nama_siswa = '';
    public $nama_panggilan = '';
    public $nik = '';
    public $nisn = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $jenis_kelamin = 'Laki-Laki';
    public $agama = 'Islam';
    public $anak_ke = 1;

    // Identitas tambahan
    public $cita_cita = '';
    public $hobi = '';
    public $kewarganegaraan = 'Indonesia';

    // Data keluarga
    public $jumlah_saudara_kandung = 0;
    public $jumlah_saudara_tiri = 0;
    public $status_yatim_piatu = 'Tidak';

    // Kondisi khusus dan kesehatan
    public $berkebutuhan_khusus = 'Tidak';
    public $penyakit = '';
    public $alergi = '';

    // Kondisi fisik
    public $tinggi_badan = '';
    public $berat_badan = '';
    public $ukuran_baju = '';

    // Checkbox bantuan sosial
    public $penerima_bantuan_sosial = [];
    public $nomor_bantuan_sosial = '';

    protected function rules()
    {
        return [
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
                'required', Rule::in(['Laki-Laki', 'Perempuan']),
            ],
            'agama' => [
                'required',
                Rule::in([
                    'Islam', 'Kristen', 'Katolik',
                    'Hindu', 'Budha', 'Konghucu',
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
                Rule::in(['Tidak', 'Yatim', 'Piatu', 'Yatim Piatu']),
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

            'ukuran_baju' => 'in:XS,S,M,L,XL,XXL',
            // 'ukuran_baju' => 'required|in:XS,S,M,L,XL,XXL',

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
        ];
    }

    protected $messages = [
        'nama_siswa.required' => 'Nama lengkap wajib diisi.',
        'nama_siswa.max' => 'Nama lengkap maksimal 150 karakter.',

        'nik.required' => 'NIK wajib diisi.',
        'nik.digits' => 'NIK harus terdiri dari 16 digit.',

        'nisn.digits' => 'NISN harus terdiri dari 10 digit.',

        'tempat_lahir.required' => 'Tempat lahir wajib diisi.',

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

        'nama_panggilan.max' => 'Nama panggilan maksimal 50 karakter.',
        'cita_cita.max' => 'Cita-cita maksimal 100 karakter.',
        'hobi.max' => 'Hobi maksimal 255 karakter.',
        'kewarganegaraan.required' => 'Kewarganegaraan wajib diisi.',
        'kewarganegaraan.max' => 'Kewarganegaraan maksimal 50 karakter.',

        'jumlah_saudara_kandung.required' => 'Jumlah saudara kandung wajib diisi.',
        'jumlah_saudara_kandung.integer' => 'Jumlah saudara kandung harus berupa angka.',
        'jumlah_saudara_kandung.min' => 'Jumlah saudara kandung tidak boleh negatif.',

        'jumlah_saudara_tiri.required' => 'Jumlah saudara tiri wajib diisi.',
        'jumlah_saudara_tiri.integer' => 'Jumlah saudara tiri harus berupa angka.',
        'jumlah_saudara_tiri.min' => 'Jumlah saudara tiri tidak boleh negatif.',

        'status_yatim_piatu.required' => 'Status yatim/piatu wajib dipilih.',
        'status_yatim_piatu.in' => 'Status yatim/piatu tidak valid.',

        'berkebutuhan_khusus.required' =>
            'Status kebutuhan khusus wajib dipilih.',

        'berkebutuhan_khusus.in' =>
            'Pilihan status kebutuhan khusus tidak valid.',

        'penyakit.max' => 'Keterangan penyakit terlalu panjang.',
        'alergi.max' => 'Keterangan alergi terlalu panjang.',

        'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
        'tinggi_badan.gt' => 'Tinggi badan harus lebih dari 0.',
        'tinggi_badan.max' => 'Tinggi badan maksimal 300 cm.',

        'berat_badan.numeric' => 'Berat badan harus berupa angka.',
        'berat_badan.gt' => 'Berat badan harus lebih dari 0.',
        'berat_badan.max' => 'Berat badan maksimal 300 kg.',

        // 'ukuran_baju.required' => 'Ukuran baju siswa wajib dipilih.',
        'ukuran_baju.in' => 'Ukuran baju yang dipilih tidak valid.',

        'penerima_bantuan_sosial.array' => 'Pilihan bantuan sosial tidak valid.',
        'penerima_bantuan_sosial.*.in' => 'Jenis bantuan sosial tidak valid.',
        'nomor_bantuan_sosial.max' => 'Nomor bantuan sosial maksimal 100 karakter.',
    ];

    public function mount($periodeId, $pendaftaranId)
    {
        $this->periodeId = $periodeId;
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        // Jika data sudah ada, jangan tampilkan form kosong.
        $sudahAda = PPDBCalonSiswa::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->exists();

        if ($sudahAda) {
            $slugJenjang = PPDBPeriode::query()
                ->with('ms_jenjang')
                ->whereKey($pendaftaran->ppdb_periode_id)
                ->firstOrFail()
                ->ms_jenjang
                ->slug;

            return redirect()->route('ppdb.pendaftaran.data-siswa', [
                'jenjang' => $slugJenjang,
            ]);
        }
    }

    /**
     * Mengambil pendaftaran yang benar-benar dimiliki pengguna login.
     */
    protected function ambilPendaftaranMilikPengguna()
    {
        $periode = PPDBPeriode::query()
            ->with('ms_jenjang')
            ->whereKey($this->periodeId)
            ->where('status', 'aktif')
            ->first();

        if (!$periode) {
            abort(403, 'Periode PPDB tidak aktif.');
        }

        $pendaftaran = PPDBPendaftaran::query()
            ->with('ppdb_periode.ms_jenjang')
            ->whereKey($this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->where('ppdb_periode_id', $periode->ppdb_periode_id)
            ->first();

        if (!$pendaftaran) {
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
                    'nama_siswa' =>
                        'Pendaftaran tidak ditemukan. Silakan periksa kembali.',
                ]);
            }

            // Hindari membuat data calon siswa ganda.
            $sudahAda = PPDBCalonSiswa::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->exists();

            if ($sudahAda) {
                throw ValidationException::withMessages([
                    'nama_siswa' => 'Data calon siswa sudah tersimpan.',
                ]);
            }

            // Simpan data calon siswa melalui service.
            PPDBDataSiswaService::create(array_merge($data, [
                'ppdb_pendaftaran_id' => $pendaftaran->ppdb_pendaftaran_id,
            ]));

            // Tandai bahwa tahap data siswa sudah selesai.
            $pendaftaran->update([
                'current_step' => 2,
            ]);
        });

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' =>
                'Data calon siswa berhasil disimpan. Silakan lanjutkan data orang tua.',
        ]);

        return redirect()->route('ppdb.pendaftaran.orang-tua', [
            'jenjang' => $slugJenjang,
        ]);
    }

    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();
        return view('livewire.p-p-d-b.pendaftaran.form-siswa',[
            'pendaftaran' => $pendaftaran,
        ]);
    }
}
