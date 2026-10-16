<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBAlamat;
use App\Models\PPDBPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditAlamat extends Component
{
    public $pendaftaranId;

    public $alamat_lengkap = '';
    public $provinsi = '';
    public $kabupaten = '';
    public $kecamatan = '';
    public $kelurahan_desa = '';
    public $kode_pos = '';
    public $jenis_tempat_tinggal = '';


    public $jarak_ke_sekolah = 0;
    public $waktu_tempuh_ke_sekolah = '';
    public $moda_transportasi = '';


    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $pendaftaran = $this->ambilPendaftaran();

        $this->pastikanBolehUbah($pendaftaran);

        $this->isiForm();
    }

    /**
     * Mengambil pendaftaran milik pengguna yang sedang login.
     */
    protected function ambilPendaftaran()
    {
        return PPDBPendaftaran::query()
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
    protected function pastikanBolehUbah($pendaftaran)
    {
        if (! $this->bolehUbahData($pendaftaran->status)) {
            abort(
                403,
                'Data alamat tidak dapat diubah pada tahap pendaftaran ini.'
            );
        }
    }

    /**
     * Mengisi form dari data alamat yang sudah tersimpan.
     */
    protected function isiForm()
    {
        $pendaftaran = $this->ambilPendaftaran();

        $alamat = PPDBAlamat::query()
            ->where(
                'ppdb_pendaftaran_id',
                $pendaftaran->ppdb_pendaftaran_id
            )
            ->firstOrFail();

        $this->alamat_lengkap = $alamat->alamat_lengkap ?? '';
        $this->provinsi = $alamat->provinsi ?? '';
        $this->kabupaten = $alamat->kabupaten ?? '';
        $this->kecamatan = $alamat->kecamatan ?? '';
        $this->kelurahan_desa = $alamat->kelurahan_desa ?? '';
        $this->kode_pos = $alamat->kode_pos ?? '';
        $this->jenis_tempat_tinggal = $alamat->jenis_tempat_tinggal ?? '';

        $this->jarak_ke_sekolah = $alamat->jarak_ke_sekolah ?? 0;
        $this->waktu_tempuh_ke_sekolah = $alamat->waktu_tempuh_ke_sekolah ?? '';
        $this->moda_transportasi = $alamat->moda_transportasi ?? '';
    }

    protected function rules()
    {
        return [
            'alamat_lengkap' => [
                'required',
                'string',
                'max:1000',
            ],
            'provinsi' => [
                'required',
                'string',
                'max:100',
            ],
            'kabupaten' => [
                'required',
                'string',
                'max:100',
            ],
            'kecamatan' => [
                'required',
                'string',
                'max:100',
            ],
            'kelurahan_desa' => [
                'required',
                'string',
                'max:100',
            ],
            'kode_pos' => [
                'nullable',
                'digits:5',
            ],
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
        ];
    }

    protected $messages = [
        'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
        'alamat_lengkap.max' => 'Alamat lengkap maksimal 1000 karakter.',

        'provinsi.required' => 'Provinsi wajib diisi.',
        'kabupaten.required' => 'Kabupaten atau kota wajib diisi.',
        'kecamatan.required' => 'Kecamatan wajib diisi.',
        'kelurahan_desa.required' => 'Kelurahan atau desa wajib diisi.',

        'kode_pos.digits' => 'Kode pos harus terdiri dari 5 digit.',

        'jenis_tempat_tinggal.required' => 'Jenis tempat tinggal wajib dipilih.',
        'jenis_tempat_tinggal.in' => 'Pilihan jenis tempat tinggal tidak valid.',

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

    /**
     * Memperbarui data alamat.
     */
    public function update()
    {
        // Periksa status sebelum validasi dan penyimpanan.
        $pendaftaran = $this->ambilPendaftaran();

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
                    'alamat_lengkap' => 'Pendaftaran tidak ditemukan atau bukan milik Anda.',
                ]);
            }

            // Periksa kembali status di dalam transaksi.
            $this->pastikanBolehUbah($pendaftaran);

            $alamat = PPDBAlamat::query()
                ->where(
                    'ppdb_pendaftaran_id',
                    $pendaftaran->ppdb_pendaftaran_id
                )
                ->lockForUpdate()
                ->first();

            if (! $alamat) {
                throw ValidationException::withMessages([
                    'alamat_lengkap' => 'Data alamat tidak ditemukan. Silakan muat ulang halaman.',
                ]);
            }

            $alamat->update([
                'alamat_lengkap' => trim($data['alamat_lengkap']),
                'provinsi' => trim($data['provinsi']),
                'kabupaten' => trim($data['kabupaten']),
                'kecamatan' => trim($data['kecamatan']),
                'kelurahan_desa' => trim($data['kelurahan_desa']),
                'kode_pos' => filled($data['kode_pos'])
                    ? trim($data['kode_pos'])
                    : null,
                'jenis_tempat_tinggal' => $data['jenis_tempat_tinggal'],

                'jarak_ke_sekolah' => $data['jarak_ke_sekolah'],
                'waktu_tempuh_ke_sekolah' => $data['waktu_tempuh_ke_sekolah'],
                'moda_transportasi' => $data['moda_transportasi'],
            ]);
        });

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data alamat berhasil diperbarui.',
        ]);

        return redirect()->route('ppdb.pendaftaran.alamat');
    }
    public function render()
    {
        return view('livewire.p-p-d-b.pendaftaran.edit-alamat');
    }
}
