<?php

namespace App\Http\Livewire\Pegawai;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\ImportPegawai;

use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HelperController;
use App\Models\User;

class Import extends Component
{
    use WithFileUploads;

    public $selectedJenjang = null;
    public $ms_jabatan_id = null;
    public $file_import = null;

    public $newPegawaiList = []; // Menyimpan pegawai baru di-upload

    protected $listeners = [
        'showImportPegawai'
    ];

    public function showImportPegawai($selectedJenjang)
    {
        $this->newPegawaiList = [];
        $this->selectedJenjang = $selectedJenjang;
    }

    public function updatedFileImport()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Inisialisasi Jabatan import
            $import = new ImportPegawai();

            // Proses file Excel
            Excel::import($import, $this->file_import);

            // Simpan data dari file ke properti $newPegawaiList
            $this->newPegawaiList = $import->getCollection()->toArray();

            // Informasikan pengguna bahwa file berhasil dibaca
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'File berhasil dibaca!']);
        } catch (\Exception $e) {
            // Tampilkan pesan error jika terjadi masalah
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    protected function rules()
    {
        return [
            'file_import' => 'required|mimes:xlsx,xls,csv',
            'ms_jabatan_id' => 'required|exists:ms_jabatan,ms_jabatan_id',
        ];
    }

    protected $messages = [
        'file_import.required' => 'File Excel wajib diunggah untuk melanjutkan.',
        'file_import.mimes' => 'File harus berupa format: xlsx, xls, atau csv.',

        'ms_jabatan_id.required' => 'Harap pilih jabatan sebelum melanjutkan.',
        'ms_jabatan_id.exists' => 'Jabatan yang dipilih tidak valid atau tidak ditemukan.',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function createPegawai()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            foreach ($this->newPegawaiList as $pegawaiData) {

                // Validasi minimal per baris
                if (empty($pegawaiData['nama_pegawai']) || empty($pegawaiData['nip'])) {
                    continue;
                }

                // 1️⃣ Generate email (jika kosong)
                $email = !empty($pegawaiData['email'])
                    ? $pegawaiData['email']
                    : HelperController::generateEmailFromNama($pegawaiData['nama_pegawai']);

                $email = HelperController::makeUniqueEmail($email);

                // 2️⃣ Create / Ambil user (hindari duplikat)
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'nama'     => $pegawaiData['nama_pegawai'],
                        'telepon'  => $pegawaiData['telepon'] ?? null,
                        'password' => Hash::make('123456'),
                        'peran'    => HelperController::mapRoleDariJabatan($this->ms_jabatan_id),
                        'current_session' => null,
                    ]
                );

                // 3️⃣ Insert pegawai + simpan user_id
                Pegawai::create([
                    'nama_pegawai'  => $pegawaiData['nama_pegawai'],
                    'nip'           => $pegawaiData['nip'],
                    'user_id'       => $user->ms_pengguna_id,   // FK user
                    'ms_pengguna_id' => auth()->id(),             // petugas import
                    'ms_jabatan_id' => $this->ms_jabatan_id,
                    'ms_jenjang_id' => $this->selectedJenjang,
                    'telepon'       => $pegawaiData['telepon'] ?? null,
                    'email'         => $email,
                    'deskripsi'     => $pegawaiData['deskripsi'] ?? null,
                ]);
            }

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Import pegawai + pembuatan akun login berhasil.'
            ]);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalImportPegawai']);

            $this->newPegawaiList = null;
            $this->file_import = null;

            $this->emit('PegawaiIndex');
            $this->emit('JabatanIndex');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $select_jabatan = Jabatan::get();

        return view('livewire.pegawai.import', [
            'select_jabatan' => $select_jabatan,

        ]);
    }
}
