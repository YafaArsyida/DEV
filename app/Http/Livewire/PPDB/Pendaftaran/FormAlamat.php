<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBAlamat;
use App\Models\PPDBOrangTua;
use App\Models\PPDBPendaftaran;
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
    public $waktu_tempuh_ke_sekolah = '';
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
    ];

    public function mount($periodeId = null, $pendaftaranId = null)
    {
        $this->periodeId = $periodeId;
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = PPDBPendaftaran::query()
            ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
            ->where('ms_pengguna_id', Auth::id())
            ->where('ppdb_periode_id', $this->periodeId)
            ->first();

        if (! $pendaftaran) {
            return redirect()->route('ppdb.pendaftaran.jalur');
        }

        // Jangan membuat data alamat kedua jika sudah tersedia.
        $alamat = PPDBAlamat::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->first();

        if ($alamat) {
            return redirect()->route('ppdb.pendaftaran.alamat');
        }
    }

    
    public function simpan()
    {
        $this->validate();

        DB::transaction(function () {
            // Pastikan pendaftaran milik pengguna yang login.
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ppdb_pendaftaran_id', $this->pendaftaranId)
                ->where('ms_pengguna_id', Auth::id())
                ->where('ppdb_periode_id', $this->periodeId)
                ->lockForUpdate()
                ->first();

            if (! $pendaftaran) {
                throw ValidationException::withMessages([
                    'alamat_lengkap' => 'Pendaftaran tidak ditemukan. Silakan periksa kembali.',
                ]);
            }

            // Pastikan data orang tua sudah tersedia.
            $orangTua = PPDBOrangtua::query()
                ->where(
                    'ppdb_pendaftaran_id', $pendaftaran->ppdb_pendaftaran_id
                )
                ->exists();

            if (! $orangTua) {
                throw ValidationException::withMessages([
                    'alamat_lengkap' => 'Lengkapi data orang tua terlebih dahulu.',
                ]);
            }

            // Cegah penyimpanan alamat ganda.
            $sudahAda = PPDBAlamat::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->lockForUpdate()
                ->exists();

            if ($sudahAda) {
                throw ValidationException::withMessages([
                    'alamat_lengkap' => 'Alamat sudah tersimpan.',
                ]);
            }

            $alamatLengkap = trim($this->alamat_lengkap);

            // Gabungkan RT/RW karena belum tersedia kolom terpisah.
            $rtRw = [];

            if (trim((string) $this->rt) !== '') {
                $rtRw[] = 'RT ' . str_pad(
                    trim($this->rt),
                    3,
                    '0',
                    STR_PAD_LEFT
                );
            }

            if (trim((string) $this->rw) !== '') {
                $rtRw[] = 'RW ' . str_pad(
                    trim($this->rw),
                    3,
                    '0',
                    STR_PAD_LEFT
                );
            }

            if (! empty($rtRw)) {
                $alamatLengkap .= ', ' . implode('/', $rtRw);
            }

            PPDBAlamat::create([
                'ppdb_pendaftaran_id' => $pendaftaran->ppdb_pendaftaran_id,
                'alamat_lengkap' => $alamatLengkap,
                'provinsi' => trim($this->provinsi),
                'kabupaten' => trim($this->kabupaten),
                'kecamatan' => trim($this->kecamatan),
                'kelurahan_desa' => trim($this->kelurahan_desa),
                'kode_pos' => $this->kode_pos ?: null,
                'jenis_tempat_tinggal' => $this->jenis_tempat_tinggal,

                // Informasi perjalanan ke sekolah
                'jarak_ke_sekolah' => $this->jarak_ke_sekolah,
                'waktu_tempuh_ke_sekolah' => $this->waktu_tempuh_ke_sekolah,
                'moda_transportasi' => $this->moda_transportasi,
            ]);

        });


        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data alamat berhasil disimpan.',
        ]);

        return redirect()->route('ppdb.pendaftaran.pendidikan');
    }

    public function render()
    {
        return view('livewire.p-p-d-b.pendaftaran.form-alamat');
    }
}
