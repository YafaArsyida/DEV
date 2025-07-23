<?php

namespace App\Http\Livewire\Siswa;

use Livewire\Component;
use App\Models\Siswa as SiswaModel;
use App\Models\Kelas as KelasModel;
use App\Models\PenempatanSiswa as PenempatanSiswaModel;

use App\Http\Controllers\HelperController;
use App\Models\AktifitasPengguna;
use App\Models\EduCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public $educard, $edupay;

    public $nama_siswa, $nisn, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $alamat, $nama_ayah, $nama_ibu, $telepon, $deskripsi;

    public $nama_petugas, $nama_jenjang, $nama_tahun_ajar, $nama_kelas;

    public $ms_penempatan_siswa_id, $ms_siswa_id, $ms_kelas_id, $ms_jenjang_id, $ms_tahun_ajar_id, $ms_pengguna_id, $created_at;

    protected $listeners = ['loadDataSiswa'];

    public function loadDataSiswa($ms_penempatan_siswa_id)
    {
        $siswa = PenempatanSiswaModel::with('ms_siswa.ms_educard', 'ms_jenjang', 'ms_tahun_ajar', 'ms_kelas', 'ms_pengguna')
            ->findOrFail($ms_penempatan_siswa_id);

        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id; // Update ms_tahun_ajar_id
        $this->ms_jenjang_id = $siswa->ms_jenjang_id; // Update ms_jenjang_id

        $this->nama_petugas = $siswa->ms_pengguna->nama;
        $this->nama_jenjang = $siswa->ms_jenjang->nama_jenjang;
        $this->nama_tahun_ajar = $siswa->ms_tahun_ajar->nama_tahun_ajar;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas;

        $this->ms_penempatan_siswa_id = $siswa->ms_penempatan_siswa_id;
        $this->ms_jenjang_id = $siswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id;
        $this->ms_kelas_id = $siswa->ms_kelas_id;
        $this->ms_siswa_id = $siswa->ms_siswa_id;

        $this->nama_siswa = $siswa->ms_siswa->nama_siswa;
        $this->nisn = $siswa->ms_siswa->nisn;
        $this->tempat_lahir = $siswa->ms_siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->ms_siswa->tanggal_lahir;
        $this->jenis_kelamin = $siswa->ms_siswa->jenis_kelamin;
        $this->alamat = $siswa->ms_siswa->alamat;
        $this->nama_ayah = $siswa->ms_siswa->nama_ayah;
        $this->nama_ibu = $siswa->ms_siswa->nama_ibu;
        $this->telepon = $siswa->ms_siswa->telepon;
        $this->deskripsi = $siswa->ms_siswa->deskripsi;
        $this->created_at = HelperController::formatTanggalIndonesia($siswa->ms_siswa->created_at, 'd F Y H:i');

        $this->educard = $siswa->ms_siswa->ms_educard ? $siswa->ms_siswa->ms_educard->kode_kartu : null;
        $this->edupay = $siswa->ms_siswa->saldo_edupay_siswa();
    }

    public function rules()
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'ms_kelas_id' => 'required|exists:ms_kelas,ms_kelas_id',
            'ms_jenjang_id' => 'required|exists:ms_jenjang,ms_jenjang_id',
            'ms_tahun_ajar_id' => 'required|exists:ms_tahun_ajar,ms_tahun_ajar_id',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_siswa.required' => 'Nama siswa tidak boleh kosong',
        'nama_siswa.string' => 'Nama siswa harus berupa teks',
        'nama_siswa.max' => 'Nama siswa maksimal 255 karakter',

        'telepon.required' => 'Telepon tidak boleh kosong',
        'telepon.string' => 'Telepon harus berupa teks',
        'telepon.max' => 'Telepon maksimal 20 karakter',

        'ms_kelas_id.required' => 'Kelas tidak boleh kosong',
        'ms_kelas_id.exists' => 'Kelas tidak valid',

        'ms_jenjang_id.required' => 'Jenjang tidak boleh kosong',
        'ms_jenjang_id.exists' => 'Jenjang tidak valid',

        'ms_tahun_ajar_id.required' => 'Tahun ajar tidak boleh kosong',
        'ms_tahun_ajar_id.exists' => 'Tahun ajar tidak valid',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateSiswa()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Normalisasi nomor telepon
            $normalizedPhone = HelperController::normalizePhoneNumber($this->telepon);

            $ms_pengguna_id = Auth::id();

            // Update data siswa di tabel ms_siswa
            $siswa = SiswaModel::findOrFail($this->ms_siswa_id);
            $siswa->update([
                'nama_siswa' => $this->nama_siswa,
                'nisn' => $this->nisn ?: null,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'alamat' => $this->alamat,
                'nama_ayah' => $this->nama_ayah,
                'nama_ibu' => $this->nama_ibu,
                'telepon' => $normalizedPhone,
                'deskripsi' => $this->deskripsi,
            ]);

            // Update data penempatan siswa di tabel ms_penempatan_siswa
            $penempatan = PenempatanSiswaModel::findOrFail($this->ms_penempatan_siswa_id);
            $penempatan->update([
                'ms_kelas_id' => $this->ms_kelas_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'ms_tahun_ajar_id' => $this->ms_tahun_ajar_id,
                'ms_pengguna_id' => $ms_pengguna_id,
            ]);

            // Logika untuk menangani kolom educard
            if (!empty($this->educard)) {
                // Insert or Update data di tabel ms_educard
                EduCard::updateOrCreate(
                    ['ms_siswa_id' => $this->ms_siswa_id], // Kondisi untuk cek apakah data sudah ada
                    [
                        'ms_pengguna_id' => $ms_pengguna_id,
                        'kode_kartu' => $this->educard, // Input dari form
                        'jenis_pemilik' => 'siswa', // Disesuaikan dengan jenis pemilik
                        'status_kartu' => 'aktif', // Status default
                        'deskripsi' => 'EduCard ' . $this->nama_siswa, // Bisa diubah sesuai kebutuhan
                    ]
                );
            } else {
                // Hapus data di tabel ms_educard jika kolom educard dikosongkan
                EduCard::where('ms_siswa_id', $this->ms_siswa_id)->delete();
            }

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah siswa!']);
            $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditSiswa']);
            $this->emit('refreshSiswas');
        } catch (\Exception $e) {
            DB::rollBack();

            // Tampilkan notifikasi error jika terjadi kesalahan
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan saat memperbarui data siswa: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kelas = KelasModel::where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }

        return view('livewire.siswa.edit', [
            'select_kelas' => $select_kelas,
        ]);
    }
}
