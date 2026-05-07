<?php

namespace App\Http\Livewire\Siswa;

use App\Exports\ExportTeleponSiswa;
use App\Http\Controllers\HelperController;
use App\Imports\ImportTeleponSiswa;
use App\Models\Kelas;

use App\Models\PenempatanSiswa;
use App\Models\Siswa;

use Illuminate\Support\Facades\DB;
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
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Tidak ada data untuk diperbarui.'
            ]);
            return;
        }

        DB::beginTransaction();

        try {
            $updated = 0;
            $skipped = 0;

            // 🔥 ambil semua ID sekaligus (lebih optimal)
            $ids = collect($this->newSiswaList)
                ->pluck('ms_siswa_id')
                ->filter()
                ->toArray();

            $siswas = Siswa::whereIn('ms_siswa_id', $ids)->get()->keyBy('ms_siswa_id');

            foreach ($this->newSiswaList as $item) {

                // 🚫 validasi basic
                if (
                    empty($item['ms_siswa_id']) ||
                    empty($item['telepon'])
                ) {
                    $skipped++;
                    continue;
                }

                $siswa = $siswas[$item['ms_siswa_id']] ?? null;

                if (!$siswa) {
                    $skipped++;
                    continue;
                }

                // ✅ normalize nomor
                $normalizedPhone = HelperController::normalizePhoneNumber($item['telepon']);

                // 🚫 optional: validasi panjang / format
                if (strlen($normalizedPhone) < 8) {
                    $skipped++;
                    continue;
                }

                // ✅ update
                $siswa->update([
                    'telepon' => $normalizedPhone
                ]);

                $updated++;
            }

            DB::commit();

            // 🔥 FEEDBACK CLEAN (tidak spam)
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Berhasil update {$updated} data" . ($skipped ? ", {$skipped} dilewati" : "")
            ]);

            // 🔥 reset state
            $this->newSiswaList = [];
            $this->file_import = null;

            $this->emit('refreshSiswas');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan saat update massal'
            ]);
        }
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
