<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;

class Create extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $siswas;
    public $jenis_tagihans;

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
            'selectAllSiswa',
            'selectAllTagihan',
            'siswaSelected',
            'tagihanSelected',
            'jumlahTagihan'
        ]);

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan dimuat']);
        $this->loadSiswas();
        $this->loadJenisTagihan();
    }

    public function loadSiswas()
    {
        if (!$this->ms_jenjang_id || !$this->ms_tahun_ajar_id) return;

        $query = PenempatanSiswa::query()
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_jenjang_id', $this->ms_jenjang_id)
            ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id);

        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->searchSiswa) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $this->searchSiswa . '%');
        }

        $this->siswas = $query
            ->orderBy('ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')
            ->select('ms_penempatan_siswa.*', 'ms_siswa.nama_siswa')
            ->get();
    }

    public function loadJenisTagihan()
    {
        if (!$this->ms_jenjang_id || !$this->ms_tahun_ajar_id) return;

        $query = JenisTagihanSiswa::query()
            ->where('ms_jenjang_id', $this->ms_jenjang_id)
            ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id);

        if ($this->selectedKategoriTagihan) {
            $query->where('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
        }

        if ($this->searchJenisTagihan) {
            $query->where('nama_jenis_tagihan_siswa', 'like', '%' . $this->searchJenisTagihan . '%');
        }

        $this->jenis_tagihans = $query
            ->orderBy('ms_kategori_tagihan_siswa_id')
            ->orderBy('ms_jenis_tagihan_siswa_id')
            ->get();
    }

    public function updatedSearchSiswa()
    {
        $this->loadSiswas();
    }

    public function updatedSelectedKelas()
    {
        $this->loadSiswas();
    }

    public function updatedSearchJenisTagihan()
    {
        $this->resetPage();
        $this->loadJenisTagihan();
    }

    public function updatedSelectedKategoriTagihan()
    {
        $this->resetPage();
        $this->loadJenisTagihan();
    }

    // checkbox
    public function updatedSelectAllSiswa($value)
    {
        if ($value) {
            $this->siswaSelected = $this->siswas
                ->pluck('ms_penempatan_siswa_id')
                ->toArray();
        } else {
            // Kosongkan siswaSelected
            $this->siswaSelected = [];
        }
    }

    public function updatedselectAllTagihan($value)
    {
        if ($value) {
            $this->tagihanSelected = $this->jenis_tagihans
                ->pluck('ms_jenis_tagihan_siswa_id')
                ->toArray();
        } else {
            $this->tagihanSelected = [];
        }
    }

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
                'jumlahTagihan.*' => 'required|numeric|min:1',
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
            )->get()->keyBy('ms_jenis_tagihan_siswa_id');

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

                    $jumlah = $this->jumlahTagihan[$jenisId] ?? 0;

                    if ($jumlah <= 0) {
                        throw new \Exception("Jumlah tidak valid.");
                    }

                    $jenis = $jenisTagihans[$jenisId] ?? null;

                    if (!$jenis) {
                        throw new \Exception("Jenis tagihan tidak ditemukan.");
                    }

                    $deskripsiJurnal = "Tagihan {$jenis->nama_jenis_tagihan_siswa} siswa {$penempatan->ms_siswa->nama_siswa}";

                    // 🔥 jurnal debit
                    $debitId = AkuntansiJurnalDetail::create([
                        'kode_rekening' => $kode_rekening_debit,
                        'posisi' => 'debit',
                        'nominal' => $jumlah,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => $ms_pengguna_id,
                        'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $this->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsiJurnal,
                    ])->akuntansi_jurnal_detail_id;

                    // 🔥 jurnal kredit
                    $kreditId = AkuntansiJurnalDetail::create([
                        'kode_rekening' => $kode_rekening_kredit,
                        'posisi' => 'kredit',
                        'nominal' => $jumlah,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => $ms_pengguna_id,
                        'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $this->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsiJurnal,
                    ])->akuntansi_jurnal_detail_id;

                    $tagihanData[] = [
                        'ms_penempatan_siswa_id' => $penempatanId,
                        'ms_jenis_tagihan_siswa_id' => $jenisId,
                        'ms_pengguna_id' => $ms_pengguna_id,
                        'jumlah_tagihan_siswa' => $jumlah,
                        'status' => 'Belum Dibayar',
                        'deskripsi' => 'Tagihan',
                        'akuntansi_jurnal_detail_debit_id' => $debitId,
                        'akuntansi_jurnal_detail_kredit_id' => $kreditId,
                        'created_at' => now(),
                        'updated_at' => now(),
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
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }

        $select_kategori = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kategori = KategoriTagihanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }

        return view('livewire.tagihan-siswa.create', [
            'select_kelas' => $select_kelas,
            'siswas' => $this->siswas,
            'select_kategori' => $select_kategori,
            'jenis_tagihans' => $this->jenis_tagihans,
        ]);
    }
}
