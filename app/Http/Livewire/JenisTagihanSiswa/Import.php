<?php

namespace App\Http\Livewire\JenisTagihanSiswa;

use App\Imports\ImportJenisTagihan;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $selectedKategoriTagihan = null;
    public $namaKategori = null;

    public $file_import = null;

    public $newJenisTagihan = []; // Menyimpan siswa baru di-upload

    protected $listeners = [
        'showImportTagihan'
    ];

    public function showImportTagihan($jenjang, $tahunAjar)
    {
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
        $this->newJenisTagihan = [];

        $this->selectedKategoriTagihan = null;
        $this->namaKategori = null;
    }

    public function updatedSelectedKategoriTagihan()
    {
        if ($this->selectedKategoriTagihan) {
            $kategori = KategoriTagihanSiswa::find($this->selectedKategoriTagihan);
            $this->namaKategori = $kategori ? $kategori->nama_kategori_tagihan_siswa : 'Kategori tidak ditemukan';
        } else {
            $this->namaKategori = 'Kategori tidak dipilih';
        }
    }

    public function updatedFileImport()
    {
        // Validasi file yang diunggah
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Inisialisasi kelas import
            $import = new ImportJenisTagihan();

            // Proses file Excel
            Excel::import($import, $this->file_import);

            // Simpan data dari file ke properti $newJenisTagihan
            $this->newJenisTagihan = $import->getCollection()->toArray();

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
            'selectedKategoriTagihan' => 'required|exists:ms_kategori_tagihan_siswa,ms_kategori_tagihan_siswa_id',
        ];
    }

    protected $messages = [
        'file_import.required' => 'File Excel wajib diunggah untuk melanjutkan.',
        'file_import.mimes' => 'File harus berupa format: xlsx, xls, atau csv.',

        'selectedKategoriTagihan.required' => 'Harap pilih Kategori sebelum melanjutkan.',
        'selectedKategoriTagihan.exists' => 'Kategori yang dipilih tidak valid atau tidak ditemukan.',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function createJenisTagihan()
    {
        // Validasi data input
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $importedCount = 0;
            $skippedCount = 0;

            foreach ($this->newJenisTagihan as $dataExcel) {
                // Validasi nama jenis tagihan
                if (empty($dataExcel['nama_jenis_tagihan_siswa'])) {
                    $skippedCount++;
                    continue;
                }

                // Simpan data jenis tagihan
                JenisTagihanSiswa::create([
                    'ms_tahun_ajar_id'       => $this->selectedTahunAjar,
                    'ms_jenjang_id'          => $this->selectedJenjang,
                    'ms_kategori_tagihan_siswa_id' => $this->selectedKategoriTagihan,
                    'nama_jenis_tagihan_siswa'     => $dataExcel['nama_jenis_tagihan_siswa'],
                    'deskripsi'              => null,
                    'cicilan_status'         => 'Tidak Aktif',
                ]);

                $importedCount++;
            }

            DB::commit();

            // Feedback kepada pengguna
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => "Proses selesai. Berhasil: {$importedCount}, Gagal: {$skippedCount}",
            ]);

            // Reset data setelah sukses
            $this->newJenisTagihan = [];
            $this->selectedKategoriTagihan = null;
            $this->emit('refreshJenisTagihans');
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
        $select_kategori = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kategori = KategoriTagihanSiswa::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }
        return view('livewire.jenis-tagihan-siswa.import', [
            'select_kategori' => $select_kategori,
            'newJenisTagihan' => $this->newJenisTagihan
        ]);
    }
}
