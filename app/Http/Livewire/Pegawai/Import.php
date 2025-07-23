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
        // Validasi data input
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Loop melalui daftar pegawai baru
            foreach ($this->newPegawaiList as $pegawaiData) {
                // Pastikan semua data pegawai lengkap sebelum insert
                if (!isset($pegawaiData['nama_pegawai']) || !isset($pegawaiData['telepon']) || !isset($pegawaiData['nip']) || !isset($pegawaiData['deskripsi'])) {
                    continue;
                }

                // Insert data pegawai
                Pegawai::create([
                    'nama_pegawai' => $pegawaiData['nama_pegawai'],
                    'nip' => $pegawaiData['nip'],
                    'ms_pengguna_id'   => Auth::id(),
                    'ms_jabatan_id' => $this->ms_jabatan_id,
                    'ms_jenjang_id'    => $this->selectedJenjang,
                    'telepon' => $pegawaiData['telepon'],
                    'deskripsi' => $pegawaiData['deskripsi'],
                ]);
            }

            // Commit transaksi jika semua proses berhasil
            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data pegawai berhasil diimport.']);
            $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalImportPegawai']);

            // Reset data setelah sukses
            $this->newPegawaiList = null;
            $this->file_import = null;

            $this->emit('refreshPegawais');
            $this->emit('refreshJabatans');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Informasikan error kepada pengguna
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
