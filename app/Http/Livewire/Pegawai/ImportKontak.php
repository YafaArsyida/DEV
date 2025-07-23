<?php

namespace App\Http\Livewire\Pegawai;

use App\Exports\ExportKontakPegawai;
use App\Imports\ImportKontakPegawai;
use App\Models\Jenjang;
use App\Models\Pegawai;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportKontak extends Component
{
    use WithFileUploads;

    public $selectedJenjang = null;
    public $namaJenjang = '';

    public $file_import = null;

    public $newPegawaiList = []; // Menyimpan siswa baru di-upload

    protected $listeners = ['showImportKontakPegawai'];

    public function showImportKontakPegawai($selectedJenjang)
    {
        $this->selectedJenjang = $selectedJenjang;

        // Cari nama kelas berdasarkan selectedJenjang
        $jenjang = Jenjang::find($selectedJenjang);
        $this->namaJenjang = $jenjang ? $jenjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function exportKontakPegawai()
    {
        $oldPegawaiList = Pegawai::with(['ms_jenjang'])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->orderBy('ms_jabatan_id', 'ASC')
            ->orderBy('nama_pegawai', 'ASC')
            ->get();

        if ($oldPegawaiList->isEmpty()) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Data tidak ditemukan.']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Menyiapkan dokumen ...']);
        return Excel::download(new ExportKontakPegawai($oldPegawaiList), 'pegawai-' . now()->format('Ymd') . '.xlsx');
    }

    public function updatedFileImport()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Inisialisasi kelas import
            $import = new ImportKontakPegawai();

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
        if (!is_array($this->newPegawaiList) || empty($this->newPegawaiList)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tidak ada data untuk diperbarui.']);
            return;
        }

        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($this->newPegawaiList as $pegawai) {
            // Validasi data sebelum update
            if (!isset($pegawai['ms_pegawai_id'], $pegawai['telepon']) || empty($pegawai['telepon'])) {
                $skippedCount++;
                continue;
            }

            try {
                // Cari siswa berdasarkan ms_pegawai_id dan update teleponnya
                $pegawaiModel = Pegawai::find($pegawai['ms_pegawai_id']);
                if ($pegawaiModel) {
                    $pegawaiModel->update(['telepon' => $pegawai['telepon']]);
                    $pegawaiModel->update(['email' => $pegawai['email']]);
                    $updatedCount++;
                } else {
                    $skippedCount++;
                }
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => "Gagal memperbarui kontak pegawai ID: {$pegawai['ms_pegawai_id']}."
                ]);
                $skippedCount++;
                continue;
            }
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data berhasil diperbarui.']);
        // Reset data setelah sukses
        $this->newPegawaiList = [];
        $this->file_import = null;

        $this->emit('refreshPegawais');
    }

    public function render()
    {
        $oldPegawaiList = Pegawai::with(['ms_jenjang'])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->orderBy('ms_jabatan_id', 'ASC')
            ->orderBy('nama_pegawai', 'ASC')
            ->get();

        return view('livewire.pegawai.import-kontak', [
            'oldPegawaiList' => $oldPegawaiList,
            'newPegawaiList' => $this->newPegawaiList,
        ]);
    }
}
