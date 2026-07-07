<?php

namespace App\Http\Livewire\Siswa;

use App\Exports\ExportEduCardSiswa;
use App\Imports\ImportEduCardSiswa;

use App\Models\EduCard;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
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

    public $previewSiswaList = [];

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

            $this->generatePreview();

            // $this->emit('logData', $this->newSiswaList);
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

    private function generatePreview()
    {
        $this->previewSiswaList = [];

        if (empty($this->newSiswaList)) {
            return;
        }

        $ids = collect($this->newSiswaList)
            ->pluck('ms_siswa_id')
            ->filter()
            ->toArray();

        $penempatan = PenempatanSiswa::with([
                'ms_siswa.ms_educard',
                'ms_kelas'
            ])
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_kelas_id', $this->selectedKelas)
            ->whereIn('ms_siswa_id', $ids)
            ->get()
            ->keyBy('ms_siswa_id');

        foreach ($this->newSiswaList as $item) {

            $old = $penempatan[$item['ms_siswa_id']] ?? null;

            $kodeLama = $old?->ms_siswa?->ms_educard?->kode_kartu;
            $kodeBaru = trim($item['educard'] ?? '');

            if (blank($kodeLama) && filled($kodeBaru)) {
                $status = 'tambah';
            } elseif (filled($kodeLama) && blank($kodeBaru)) {
                $status = 'hapus';
            } elseif ($kodeLama != $kodeBaru) {
                $status = 'update';
            } else {
                $status = 'sama';
            }

            $this->previewSiswaList[] = [
                'ms_siswa_id' => $item['ms_siswa_id'],
                'nama_siswa' => $old?->ms_siswa?->nama_siswa ?? '-',
                'kelas' => $old?->ms_kelas?->nama_kelas ?? '-',
                
                'educard_lama' => $kodeLama,
                'educard_baru' => $kodeBaru,
                
                'status' => $status,
            ];
        }
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
            $imported = 0;
            $deleted = 0;
            $skipped = 0;

            // 🔥 ambil semua siswa sekaligus (hindari N+1)
            $ids = collect($this->newSiswaList)
                ->pluck('ms_siswa_id')
                ->filter()
                ->toArray();

            $siswas = Siswa::whereIn('ms_siswa_id', $ids)
                ->get()
                ->keyBy('ms_siswa_id');

            foreach ($this->newSiswaList as $item) {

                // 🚫 validasi basic
                if (
                    empty($item['ms_siswa_id']) ||
                    empty($item['nama_siswa'])
                ) {
                    $skipped++;
                    continue;
                }

                $siswa = $siswas[$item['ms_siswa_id']] ?? null;

                if (!$siswa) {
                    $skipped++;
                    continue;
                }

                try {
                    if (!empty($item['educard'])) {

                        // 🔥 optional: trimming
                        $kodeKartu = trim($item['educard']);

                        EduCard::updateOrCreate(
                            ['ms_siswa_id' => $item['ms_siswa_id']],
                            [
                                'ms_pengguna_id' => auth()->id(),
                                'kode_kartu' => $kodeKartu,
                                'jenis_pemilik' => 'siswa',
                                'status_kartu' => 'aktif',
                                'deskripsi' => 'EduCard ' . $item['nama_siswa'],
                            ]
                        );

                        $imported++;
                    } else {
                        // ✅ hapus jika kosong
                        EduCard::where('ms_siswa_id', $item['ms_siswa_id'])->delete();
                        $deleted++;
                    }
                } catch (\Illuminate\Database\QueryException $e) {

                    // 🔥 HANDLE DUPLIKASI TANPA SPAM
                    if ($e->getCode() == 23000) {
                        // duplicate key → skip saja
                        $skipped++;
                        continue;
                    }

                    throw $e; // selain itu lempar ke global catch
                }
            }

            DB::commit();

            // 🔥 summary clean (UX bagus)
            $message = "Import: {$imported}, Hapus: {$deleted}";
            if ($skipped) {
                $message .= ", Skip: {$skipped}";
            }

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => $message
            ]);

            // 🔥 reset state
            $this->newSiswaList = [];
            $this->previewSiswaList = [];
            $this->file_import = null;

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalImportEduCard'
            ]);
            $this->emit('refreshSiswas');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan saat update EduCard'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.siswa.import-edu-card', [
            'previewSiswaList' => $this->previewSiswaList,
        ]);
    }
}
