<?php

namespace App\Http\Livewire\Siswa;

use Livewire\Component;
use App\Models\Siswa as SiswaModel;
use App\Models\Kelas as KelasModel;
use App\Models\PenempatanSiswa as PenempatanSiswaModel;

use App\Http\Controllers\HelperController;
use App\Models\AktifitasPengguna;
use App\Models\EduCard;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $nama_siswa, $nisn, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $alamat, $nama_ayah, $nama_ibu, $telepon, $deskripsi, $educard;

    public $ms_siswa_id, $ms_kelas_id, $ms_jenjang_id, $ms_tahun_ajar_id, $ms_pengguna_id;

    public $nama_jenjang;
    public $nama_tahun_ajar;

    protected $listeners = [
        'showCreateSiswa',
    ];

    public function showCreateSiswa($selectedJenjang, $selectedTahunAjar)
    {
        $this->ms_jenjang_id = $selectedJenjang;
        $this->ms_tahun_ajar_id = $selectedTahunAjar;

        $this->nama_jenjang = Jenjang::where('ms_jenjang_id', $selectedJenjang)->value('nama_jenjang');
        $this->nama_tahun_ajar = TahunAjar::where('ms_tahun_ajar_id', $selectedTahunAjar)->value('nama_tahun_ajar');

        // $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_siswa' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
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

        'tanggal_lahir.required' => 'Tanggal lahir tidak boleh kosong',
        'tanggal_lahir.date' => 'Tanggal lahir harus berupa format tanggal yang valid (YYYY-MM-DD)',

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

    public function save()
    {
        // Validasi data input
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Normalisasi nomor telepon
            $normalizedPhone = HelperController::normalizePhoneNumber($this->telepon);

            // Insert data siswa
            $siswa = SiswaModel::create([
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

            // Mendapatkan ID pengguna yang sedang login
            $ms_pengguna_id = Auth::id();

            // Insert data penempatan siswa
            PenempatanSiswaModel::create([
                'ms_siswa_id' => $siswa->ms_siswa_id, // Menggunakan ID siswa yang baru dibuat
                'ms_kelas_id' => $this->ms_kelas_id,
                'ms_tahun_ajar_id' => $this->ms_tahun_ajar_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'ms_pengguna_id' => $ms_pengguna_id, // ID pengguna yang login
            ]);

            // Logika untuk menangani kolom educard
            if (!empty($this->educard)) {
                // Insert data di tabel ms_educard
                EduCard::Create(
                    [
                        'ms_siswa_id' => $siswa->ms_siswa_id, // Kondisi untuk cek apakah data sudah ada
                        'ms_pengguna_id' => Auth::id(),
                        'kode_kartu' => $this->educard, // Input dari form
                        'jenis_pemilik' => 'siswa', // Disesuaikan dengan jenis pemilik
                        'status_kartu' => 'aktif', // Status default
                        'deskripsi' => 'EduCard ' . $this->nama_siswa, // Bisa diubah sesuai kebutuhan
                    ]
                );
            }

            // Commit transaksi
            DB::commit();

            // Notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah siswa!']);

            // Reset form input
            $this->resetInput();

            // Tutup modal dan refresh data siswa
            // $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAddSiswa']);

            $this->emit('refreshSiswas');
            $this->emit('refreshKelass');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal menambah siswa: ' . $e->getMessage()]);
        }
    }

    public function resetInput()
    {
        $this->nama_siswa = '';
        $this->nisn = '';
        $this->tempat_lahir = '';
        // $this->tanggal_lahir = '';
        $this->jenis_kelamin = '';
        $this->alamat = '';
        $this->nama_ayah = '';
        $this->nama_ibu = '';
        $this->telepon = '';
        $this->deskripsi = '';
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

        return view('livewire.siswa.create', [
            'select_kelas' => $select_kelas,
        ]);
    }
}
