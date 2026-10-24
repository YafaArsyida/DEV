<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBAlamat;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPeriode;
use App\Services\PPDB\PPDBAlamatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FormAlamat extends Component
{
    public $periodeId;
    public $pendaftaranId;

    public $alamat_lengkap = '';
    public $provinsi = '';
    public $kabupaten = '';
    public $kecamatan = '';
    public $kelurahan_desa = '';
    public $rt = '';
    public $rw = '';
    public $kode_pos = '';
    public $jenis_tempat_tinggal = '';


    public $jarak_ke_sekolah = 0;
    public $waktu_tempuh_ke_sekolah = 5;
    public $moda_transportasi = '';


    protected function rules()
    {
        return [
            'alamat_lengkap' => 'required|string|max:1000',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan_desa' => 'required|string|max:100',
            'rt' => 'nullable|digits_between:1,3',
            'rw' => 'nullable|digits_between:1,3',
            'kode_pos' => 'nullable|digits:5',
            'jenis_tempat_tinggal' => 'required|in:rumah_orang_tua,rumah_saudara,kos_kontrak,lainnya',

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
        ];
    }

    protected $messages = [
        'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
        'provinsi.required' => 'Provinsi wajib diisi.',
        'kabupaten.required' => 'Kabupaten/kota wajib diisi.',
        'kecamatan.required' => 'Kecamatan wajib diisi.',
        'kelurahan_desa.required' => 'Desa/kelurahan wajib diisi.',
        'rt.digits_between' => 'RT maksimal terdiri dari 3 digit.',
        'rw.digits_between' => 'RW maksimal terdiri dari 3 digit.',
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
    ];

    public function mount($periodeId = null, $pendaftaranId = null)
    {
        $this->periodeId = $periodeId;
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        // Jangan membuat data alamat kedua jika sudah tersedia.
        $alamat = PPDBAlamat::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if ($alamat) {
            $slugJenjang = PPDBPeriode::query()
                ->with('ms_jenjang')
                ->whereKey($pendaftaran->ppdb_periode_id)
                ->firstOrFail()
                ->ms_jenjang
                ->slug;

            return redirect()->route('ppdb.pendaftaran.alamat', [
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
            // Pastikan pendaftaran milik pengguna yang login.
            $pendaftaran = PPDBPendaftaran::query()
                ->whereKey($this->pendaftaranId)
                ->where('ms_pengguna_id', Auth::id())
                ->where('ppdb_periode_id', $this->periodeId)
                ->lockForUpdate()
                ->first();

            if (!$pendaftaran) {
                throw ValidationException::withMessages([
                    'alamat_lengkap' =>
                        'Pendaftaran tidak ditemukan. Silakan periksa kembali.',
                ]);
            }

            // Cegah penyimpanan alamat ganda.
            $sudahAda = PPDBAlamat::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->exists();

            if ($sudahAda) {
                throw ValidationException::withMessages([
                    'alamat_lengkap' => 'Alamat sudah tersimpan.',
                ]);
            }

            PPDBAlamatService::create(
                $pendaftaran->ppdb_pendaftaran_id,
                $data
            );

            $pendaftaran->update([
                'current_step' => 4,
            ]);
        });

        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        $slugJenjang = $pendaftaran->ppdb_periode->ms_jenjang->slug;

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data alamat berhasil disimpan.',
        ]);

        return redirect()->route('ppdb.pendaftaran.pendidikan', [
            'jenjang' => $slugJenjang,
        ]);
    }


    public function render()
    {
        $pendaftaran = $this->ambilPendaftaranMilikPengguna();

        return view('livewire.p-p-d-b.pendaftaran.form-alamat',[
            'pendaftaran' => $pendaftaran
        ]);
    }
}
