<?php

namespace App\Http\Livewire\Siswa;

use App\Exports\ExportTeleponSiswa;
use App\Imports\ImportTeleponSiswa;
use App\Models\AktifitasPengguna;
use App\Models\Kelas;

use App\Models\PenempatanSiswa;
use App\Models\Siswa;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportTelepon extends Component
{
    use WithFileUploads;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;
    public $namaKelas = '';

    public $file_import = null;

    public $newSiswaList = []; // Menyimpan siswa baru di-upload

    protected $listeners = ['showImportTelepon'];

    public function showImportTelepon($selectedKelas, $selectedJenjang, $selectedTahunAjar)
    {
        $this->selectedKelas = $selectedKelas;
        $this->selectedJenjang = $selectedJenjang;
        $this->selectedTahunAjar = $selectedTahunAjar;

        // Cari nama kelas berdasarkan selectedKelas
        $kelas = Kelas::find($selectedKelas);
        $this->namaKelas = $kelas ? $kelas->nama_kelas : 'Tidak Diketahui';
    }

    public function exportTeleponSiswa()
    {
        $oldSiswaList = PenempatanSiswa::with(['ms_siswa', 'ms_kelas'])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_kelas_id', $this->selectedKelas)
            ->get();

        if ($oldSiswaList->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportTeleponSiswa($oldSiswaList), 'siswa-' . now()->format('Ymd') . '.xlsx');
    }

    public function updatedFileImport()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Inisialisasi kelas import
            $import = new ImportTeleponSiswa();

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
        ];
    }

    protected $messages = [
        'file_import.required' => 'File Excel wajib diunggah untuk melanjutkan.',
        'file_import.mimes' => 'File harus berupa format: xlsx, xls, atau csv.',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function saveChanges()
    {
        if (!is_array($this->newSiswaList) || empty($this->newSiswaList)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak ada data untuk diperbarui.']);
            return;
        }

        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($this->newSiswaList as $siswa) {
            // Validasi data sebelum update
            if (!isset($siswa['ms_siswa_id'], $siswa['telepon']) || empty($siswa['telepon'])) {
                $skippedCount++;
                continue;
            }

            try {
                // Cari siswa berdasarkan ms_siswa_id dan update teleponnya
                $siswaModel = Siswa::find($siswa['ms_siswa_id']);
                if ($siswaModel) {
                    $siswaModel->update(['telepon' => $siswa['telepon']]);
                    $updatedCount++;
                } else {
                    $skippedCount++;
                }
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => "Gagal memperbarui telepon untuk siswa ID: {$siswa['ms_siswa_id']}."
                ]);
                $skippedCount++;
                continue;
            }
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data telepon siswa berhasil diperbarui.']);
        // Reset data setelah sukses
        $this->newSiswaList = [];
        $this->file_import = null;

        $this->emit('refreshSiswas');
    }

    public function render()
    {
        $oldSiswaList = PenempatanSiswa::with(['ms_siswa', 'ms_kelas'])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_kelas_id', $this->selectedKelas)
            ->get();

        return view('livewire.siswa.import-telepon', [
            'oldSiswaList' => $oldSiswaList,
            'newSiswaList' => $this->newSiswaList,
        ]);
    }
}
