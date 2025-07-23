<?php

namespace App\Http\Livewire\Siswa;

use App\Exports\ExportEduCardSiswa;
use App\Imports\ImportEduCardSiswa;

use App\Models\EduCard;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportEduCard extends Component
{
    use WithFileUploads;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;
    public $namaKelas = '';

    public $file_import = null;

    public $newSiswaList = []; // Menyimpan siswa baru di-upload

    protected $listeners = ['showImportEduCard'];

    public function showImportEduCard($selectedKelas, $selectedJenjang, $selectedTahunAjar)
    {
        $this->selectedKelas = $selectedKelas;
        $this->selectedJenjang = $selectedJenjang;
        $this->selectedTahunAjar = $selectedTahunAjar;

        // Cari nama kelas berdasarkan selectedKelas
        $kelas = Kelas::find($selectedKelas);
        $this->namaKelas = $kelas ? $kelas->nama_kelas : 'Tidak Diketahui';
    }

    public function exportEduCardSiswa()
    {
        $oldSiswaList = PenempatanSiswa::with(['ms_siswa.ms_educard', 'ms_kelas'])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_kelas_id', $this->selectedKelas)
            ->get();

        if ($oldSiswaList->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data siswa tidak ditemukan.']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportEduCardSiswa($oldSiswaList), 'siswa-' . now()->format('Ymd') . '.xlsx');
    }

    public function updatedFileImport()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Inisialisasi kelas import
            $import = new ImportEduCardSiswa();

            // Proses file Excel
            Excel::import($import, $this->file_import);

            // Simpan data dari file ke properti $newSiswaList
            $this->newSiswaList = $import->getCollection()->toArray();
            $this->emit('logData', $this->newSiswaList);
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

        $importedCount = 0;
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($this->newSiswaList as $siswa) {
            // Validasi data sebelum proses
            if (!isset($siswa['ms_siswa_id']) || empty($siswa['nama_siswa'])) {
                $skippedCount++;
                continue;
            }

            try {
                // Update atau hapus EduCard berdasarkan data baru
                if (!empty($siswa['educard'])) {
                    EduCard::updateOrCreate(
                        ['ms_siswa_id' => $siswa['ms_siswa_id']], // Kondisi untuk cek apakah data sudah ada
                        [
                            'ms_pengguna_id' => auth()->id(), // Asosiasi dengan pengguna saat ini
                            'kode_kartu' => $siswa['educard'], // Kode kartu dari data impor
                            'jenis_pemilik' => 'siswa', // Tetap 'siswa' untuk tipe pemilik
                            'status_kartu' => 'aktif', // Status default kartu
                            'deskripsi' => 'EduCard ' . $siswa['nama_siswa'], // Deskripsi kartu
                        ]
                    );
                    $importedCount++;
                } else {
                    // Hapus data jika educard dikosongkan
                    EduCard::where('ms_siswa_id', $siswa['ms_siswa_id'])->delete();
                    $deletedCount++;
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Tangani error duplicate entry
                if ($e->getCode() == 23000) {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => "Duplikasi kode kartu: {$siswa['educard']} pada siswa {$siswa['nama_siswa']}."
                    ]);
                } else {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => "Terjadi kesalahan pada siswa {$siswa['nama_siswa']}."
                    ]);
                }
                $skippedCount++;
                continue;
            }
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data EduCard berhasil diperbarui.']);
        // Reset data setelah sukses
        $this->newSiswaList = [];
        $this->file_import = null;

        $this->emit('refreshSiswas');
    }

    public function render()
    {
        $oldSiswaList = PenempatanSiswa::with(['ms_siswa.ms_educard', 'ms_kelas'])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_kelas_id', $this->selectedKelas)
            ->get();

        return view('livewire.siswa.import-edu-card', [
            'oldSiswaList' => $oldSiswaList,
            'newSiswaList' => $this->newSiswaList,
        ]);
    }
}
