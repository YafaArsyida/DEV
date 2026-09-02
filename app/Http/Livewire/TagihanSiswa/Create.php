<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;

class Create extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 50;

    // Properties
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;

    // fitur pencarian
    public $searchSiswa = ''; // Pencarian siswa
    public $searchJenisTagihan = ''; // Pencarian tagihan

    // fitur filter
    public $selectedKelas;
    public $selectedKategoriTagihan;

    // properti model
    public $jumlahTagihan = [];

    // fitur checkbox 
    public $siswasOnPage = [];
    public $siswaSelected = []; // ID siswa yang dipilih
    public $tagihanSelected = []; // ID jenis tagihan yang dipilih

    public $selectAllSiswa = false;
    public $selectAllTagihan = false;

    protected $listeners = [
        'showCreateTagihan',
    ];

    public function showCreateTagihan($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;

        $this->reset([
            'searchSiswa',
            'searchJenisTagihan',

            'selectedKelas',
            'selectedKategoriTagihan',

            'selectAllSiswa',
            'selectAllTagihan',

            'siswaSelected',
            'tagihanSelected',

            'jumlahTagihan',
        ]);

        $this->resetPage();

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Tagihan dimuat'
        ]);
    }

    protected function resetSelectAllSiswa()
    {
        $this->selectAllSiswa = false;
    }

    public function updatingPage()
    {
        $this->selectAllSiswa = false;
    }

    public function updatedSearchSiswa()
    {
        $this->resetPage();
        $this->resetSelectAllSiswa();
    }

    public function updatedSelectedKelas()
    {
        $this->resetPage();
        $this->resetSelectAllSiswa();
    }

    public function updatedSearchJenisTagihan()
    {
        $this->resetPage();
    }

    public function updatedSelectedKategoriTagihan()
    {
        $this->resetPage();
    }

    // checkbox
    public function updatedSelectAllSiswa($value)
    {
        $pageIds = collect($this->siswasOnPage)
            ->pluck('ms_penempatan_siswa_id')
            ->toArray();

        if ($value) {

            $this->siswaSelected = array_values(array_unique(
                array_merge($this->siswaSelected, $pageIds)
            ));

        } else {

            $this->siswaSelected = array_values(array_diff(
                $this->siswaSelected,
                $pageIds
            ));

        }
    }
    
    public function updatedSiswaSelected()
    {
        $pageIds = collect($this->siswasOnPage)
            ->pluck('ms_penempatan_siswa_id')
            ->toArray();

        $selectedOnPage = array_intersect(
            $pageIds,
            $this->siswaSelected
        );

        $this->selectAllSiswa =
            count($pageIds) > 0 &&
            count($selectedOnPage) === count($pageIds);
    }

    // public function updatedselectAllTagihan($value)
    // {
    //     if ($value) {
    //         $this->tagihanSelected = $this->jenis_tagihans
    //             ->pluck('ms_jenis_tagihan_siswa_id')
    //             ->toArray();
    //     } else {
    //         $this->tagihanSelected = [];
    //     }
    // }

    public function createTagihan()
    {
        DB::beginTransaction();

        try {
            if (empty($this->siswaSelected)) {
                throw new \Exception('Pilih siswa.');
            }

            if (empty($this->tagihanSelected)) {
                throw new \Exception('Pilih jenis tagihan.');
            }

            $this->validate([
                'siswaSelected' => 'required|array|min:1',
                'tagihanSelected' => 'required|array|min:1',
                'jumlahTagihan.*' => 'required',
            ]);

            $ms_pengguna_id = auth()->id();

            $kode_rekening_debit = 12001;
            $kode_rekening_kredit = 41001;

            // 🔒 Lock semua penempatan
            $penempatans = PenempatanSiswa::lockForUpdate()
                ->with('ms_siswa')
                ->whereIn('ms_penempatan_siswa_id', $this->siswaSelected)
                ->get()
                ->keyBy('ms_penempatan_siswa_id');

            if ($penempatans->isEmpty()) {
                throw new \Exception('Data siswa tidak ditemukan.');
            }

            // 🔥 Ambil jenis tagihan sekali
            $jenisTagihans = JenisTagihanSiswa::whereIn(
                'ms_jenis_tagihan_siswa_id',
                $this->tagihanSelected
            )
                ->get()
                ->keyBy('ms_jenis_tagihan_siswa_id');

            // 🔥 Ambil existing (anti duplicate)
            $existing = TagihanSiswa::lockForUpdate()
                ->whereIn('ms_penempatan_siswa_id', $this->siswaSelected)
                ->whereIn('ms_jenis_tagihan_siswa_id', $this->tagihanSelected)
                ->get()
                ->groupBy('ms_penempatan_siswa_id');

            $tagihanData = [];
            $existingLog = [];

            foreach ($penempatans as $penempatanId => $penempatan) {

                $existingJenisIds = collect($existing[$penempatanId] ?? [])
                    ->pluck('ms_jenis_tagihan_siswa_id')
                    ->toArray();

                foreach ($this->tagihanSelected as $jenisId) {

                    if (in_array($jenisId, $existingJenisIds)) {
                        $existingLog[] = "{$penempatan->ms_siswa->nama_siswa} - {$jenisId}";
                        continue;
                    }

                    $jumlah = $this->normalizeAmount(
                        $this->jumlahTagihan[$jenisId] ?? 0
                    );

                    if ($jumlah <= 0) {
                        throw new \Exception("Jumlah tidak valid.");
                    }

                    $jenis = $jenisTagihans[$jenisId] ?? null;

                    if (!$jenis) {
                        throw new \Exception("Jenis tagihan tidak ditemukan.");
                    }

                    $deskripsiJurnal = sprintf(
                        'Tagihan %s siswa %s',
                        $jenis->nama_jenis_tagihan_siswa,
                        $penempatan->ms_siswa->nama_siswa
                    );

                    // ==========================
                    // Membuat jurnal
                    // ==========================
                    $jurnal = AccountingService::create([
                        'tanggal' => now(),
                        'deskripsi' => $deskripsiJurnal,
                        'ms_pengguna_id' => $ms_pengguna_id,
                        'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $this->ms_jenjang_id,
                        'ms_departemen_id' => 'SEKOLAH',

                        'detail' => [

                            [
                                'kode_rekening' => $kode_rekening_debit,
                                'posisi' => 'debit',
                                'nominal' => $jumlah,
                            ],

                            [
                                'kode_rekening' => $kode_rekening_kredit,
                                'posisi' => 'kredit',
                                'nominal' => $jumlah,
                            ],
                        ]
                    ]);

                    // ==========================
                    // Data tagihan
                    // ==========================
                    $tagihanData[] = [
                        'ms_penempatan_siswa_id'    => $penempatanId,
                        'ms_jenis_tagihan_siswa_id' => $jenisId,
                        'ms_pengguna_id'            => $ms_pengguna_id,
                        'jumlah_tagihan_siswa'      => $jumlah,
                        'status'                    => 'Belum Dibayar',
                        'deskripsi'                 => 'Tagihan Baru',
                        'akuntansi_jurnal_id'       => $jurnal->akuntansi_jurnal_id,
                        'created_at'                => now(),
                        'updated_at'                => now(),
                    ];
                }
            }

            // 🔥 Bulk insert
            if (!empty($tagihanData)) {
                TagihanSiswa::insert($tagihanData);
            }

            DB::commit();

            // reset
            $this->reset([
                'selectAllSiswa',
                'selectAllTagihan',
                'siswaSelected',
                'tagihanSelected',
                'jumlahTagihan'
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => !empty($existingLog)
                    ? 'Sebagian berhasil, ada duplikat.'
                    : 'Tagihan berhasil dibuat.'
            ]);

            $this->emit('refreshTagihanSiswa');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        // Dropdown Kelas
        $select_kelas = collect();

        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kelas = Kelas::query()
                ->where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }

        // Dropdown Kategori
        $select_kategori = collect();

        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kategori = KategoriTagihanSiswa::query()
                ->where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }

        // Data siswa
        $siswas = collect();

        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {

            $siswas = PenempatanSiswa::query()
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->select('ms_penempatan_siswa.*')
                ->with([
                    'ms_siswa.ms_educard',
                    'ms_kelas'
                ])
                ->where('ms_penempatan_siswa.ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->ms_tahun_ajar_id)

                ->when(
                    $this->selectedKelas,
                    fn($q) => $q->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas)
                )

                ->when($this->searchSiswa, function ($q) {
                    $q->whereHas('ms_siswa', function ($q2) {
                        $q2->where(
                            'nama_siswa',
                            'like',
                            '%' . $this->searchSiswa . '%'
                        );
                    });
                })

                ->orderBy('ms_penempatan_siswa.ms_kelas_id')
                ->orderBy('ms_siswa.nama_siswa')
                ->paginate($this->perPage);

            $this->siswasOnPage = $siswas->items();
        }

        // Data Jenis Tagihan
        $jenis_tagihans = collect();

        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {

            $jenis_tagihans = JenisTagihanSiswa::query()
                ->where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)

                ->when(
                    $this->selectedKategoriTagihan,
                    fn($q) => $q->where(
                        'ms_kategori_tagihan_siswa_id',
                        $this->selectedKategoriTagihan
                    )
                )

                ->when(
                    $this->searchJenisTagihan,
                    fn($q) => $q->where(
                        'nama_jenis_tagihan_siswa',
                        'like',
                        '%' . $this->searchJenisTagihan . '%'
                    )
                )

                ->orderBy('ms_kategori_tagihan_siswa_id')
                ->orderBy('ms_jenis_tagihan_siswa_id')
                ->get();
        }

        return view('livewire.tagihan-siswa.create', compact(
            'select_kelas',
            'select_kategori',
            'siswas',
            'jenis_tagihans'
        ));
    }
    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
