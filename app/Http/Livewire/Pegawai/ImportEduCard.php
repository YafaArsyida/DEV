<?php

namespace App\Http\Livewire\Pegawai;

use App\Exports\ExportEduCardPegawai;
use App\Imports\ImportEduCardPegawai;
use App\Models\EduCard;
use App\Models\Jenjang;
use App\Models\Pegawai;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportEduCard extends Component
{
    use WithFileUploads;

    public $selectedJenjang = null;
    public $namaJenjang = '';

    public $file_import = null;

    public $newPegawaiList = []; // Menyimpan pegawai baru di-upload

    protected $listeners = ['showImportEduCardPegawai'];

    public function showImportEduCardPegawai($selectedJenjang)
    {
        $this->selectedJenjang = $selectedJenjang;

        // Cari nama kelas berdasarkan selectedKelas
        $janjang = Jenjang::find($selectedJenjang);
        $this->namaJenjang = $janjang ? $janjang->nama_jenjang : 'Tidak Diketahui';
    }

    public function exportEduCard()
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
        return Excel::download(new ExportEduCardPegawai($oldPegawaiList), 'pegawai-' . now()->format('Ymd') . '.xlsx');
    }

    public function updatedFileImport()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Inisialisasi kelas import
            $import = new ImportEduCardPegawai();

            // Proses file Excel
            Excel::import($import, $this->file_import);

            // Simpan data dari file ke properti $newPegawaiList
            $this->newPegawaiList = $import->getCollection()->toArray();
            $this->emit('logData', $this->newPegawaiList);
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

        $importedCount = 0;
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($this->newPegawaiList as $pegawai) {
            // Validasi data sebelum proses
            if (!isset($pegawai['ms_pegawai_id']) || empty($pegawai['nama_pegawai'])) {
                $skippedCount++;
                continue;
            }

            try {
                // Update atau hapus EduCard berdasarkan data baru
                if (!empty($pegawai['educard'])) {
                    EduCard::updateOrCreate(
                        ['ms_pegawai_id' => $pegawai['ms_pegawai_id']], // Kondisi untuk cek apakah data sudah ada
                        [
                            'ms_pengguna_id' => auth()->id(), // Asosiasi dengan pengguna saat ini
                            'kode_kartu' => $pegawai['educard'], // Kode kartu dari data impor
                            'jenis_pemilik' => 'pegawai', // Tetap 'pegawai' untuk tipe pemilik
                            'status_kartu' => 'aktif', // Status default kartu
                            'deskripsi' => 'EduCard ' . $pegawai['nama_pegawai'], // Deskripsi kartu
                        ]
                    );
                    $importedCount++;
                } else {
                    // Hapus data jika educard dikosongkan
                    EduCard::where('ms_pegawai_id', $pegawai['ms_pegawai_id'])->delete();
                    $deletedCount++;
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Tangani error duplicate entry
                if ($e->getCode() == 23000) {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => "Duplikasi kode kartu: {$pegawai['educard']} pada pegawai {$pegawai['nama_pegawai']}."
                    ]);
                } else {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => "Terjadi kesalahan pada pegawai {$pegawai['nama_pegawai']}."
                    ]);
                }
                $skippedCount++;
                continue;
            }
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data EduCard berhasil diperbarui.']);
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

        return view('livewire.pegawai.import-edu-card', [
            'oldPegawaiList' => $oldPegawaiList,
            'newPegawaiList' => $this->newPegawaiList,
        ]);
    }
}
