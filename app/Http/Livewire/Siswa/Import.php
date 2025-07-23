<?php

namespace App\Http\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Kelas as KelasModel;

use App\Imports\ImportSiswa;
use App\Models\AktifitasPengguna;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Import extends Component
{
    use WithFileUploads;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $ms_kelas_id = null;
    public $file_import = null;

    public $newSiswaList = []; // Menyimpan siswa baru di-upload

    protected $listeners = [
        'showImportSiswa' => 'updateParameters'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->newSiswaList = [];
    }

    public function updatedFileImport()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Inisialisasi kelas import
            $import = new ImportSiswa();

            // Proses file Excel
            Excel::import($import, $this->file_import);

            // Simpan data dari file ke properti $newSiswaList
            $this->newSiswaList = $import->getCollection()->toArray();

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
            'ms_kelas_id' => 'required|exists:ms_kelas,ms_kelas_id',
        ];
    }

    protected $messages = [
        'file_import.required' => 'File Excel wajib diunggah untuk melanjutkan.',
        'file_import.mimes' => 'File harus berupa format: xlsx, xls, atau csv.',

        'ms_kelas_id.required' => 'Harap pilih kelas sebelum melanjutkan.',
        'ms_kelas_id.exists' => 'Kelas yang dipilih tidak valid atau tidak ditemukan.',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function createPenempatan()
    {
        // Validasi data input
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $existingNames = Siswa::pluck('nama_siswa')->toArray(); // Ambil nama siswa yang sudah ada
            $importedCount = 0;
            $skippedCount = 0;

            foreach ($this->newSiswaList as $siswaData) {
                // Validasi data nama saja, telepon tidak wajib
                if (empty($siswaData['nama_siswa'])) {
                    $skippedCount++;
                    continue;
                }

                // Cek apakah nama siswa sudah ada
                if (in_array($siswaData['nama_siswa'], $existingNames)) {
                    $skippedCount++;
                    continue;
                }

                // Simpan data siswa
                $siswa = Siswa::create([
                    'nama_siswa' => $siswaData['nama_siswa'],
                    'telepon'    => $siswaData['telepon'] ?? null, // Jika tidak ada, simpan null
                ]);

                // Simpan data penempatan siswa
                PenempatanSiswa::create([
                    'ms_siswa_id'      => $siswa->ms_siswa_id,
                    'ms_kelas_id'      => $this->ms_kelas_id,
                    'ms_tahun_ajar_id' => $this->selectedTahunAjar,
                    'ms_jenjang_id'    => $this->selectedJenjang,
                    'ms_pengguna_id'   => Auth::id(),
                ]);

                // Tambahkan nama siswa baru ke array nama yang sudah ada
                $existingNames[] = $siswaData['nama_siswa'];
                $importedCount++;
            }

            DB::commit();
            // Feedback kepada pengguna
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Import selesai. Berhasil: {$importedCount}, Gagal: {$skippedCount} ",
            ]);

            // Reset data setelah sukses
            $this->newSiswaList = null;
            $this->file_import = null;

            $this->emit('refreshSiswas');
            $this->emit('refreshKelass');
        } catch (\Exception $e) {
            DB::rollBack();

            // Informasikan error kepada pengguna
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = KelasModel::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }
        return view('livewire.siswa.import', [
            'select_kelas' => $select_kelas,
            'newSiswaList' => $this->newSiswaList,
        ]);
    }
}
