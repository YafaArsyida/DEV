<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AksiTambah extends Component
{
    // Properties
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $ms_penempatan_siswa_id;
    public $namaSiswa;

    public $select_kategori = [];
    public $tagihans = [];

    // fitur pencarian
    public $searchJenisTagihan = ''; // Pencarian tagihan

    // fitur filter
    public $selectedKategoriTagihan;

    // properti model
    public $jumlahTagihan = [];

    // fitur tagihanSelectedbox 
    public $tagihanSelected = []; // ID jenis tagihan yang dipilih

    public $selectAllTagihan = false;

    // Listeners
    protected $listeners = ['tambahTagihanSiswa' => 'canvasTagihan'];

    public function updatedSearchJenisTagihan()
    {
        $this->resetSelectionState();
        $this->loadData();
    }

    public function updatedSelectedKategoriTagihan()
    {
        $this->resetSelectionState();
        $this->loadData();
    }

    protected function resetSelectionState()
    {
        $this->selectAllTagihan = false;
        $this->tagihanSelected = [];
        $this->jumlahTagihan = [];
    }

    public function loadData()
    {
        if (!$this->ms_penempatan_siswa_id) {
            $this->select_kategori = [];
            $this->tagihans = [];
            return;
        }

        // Ambil informasi penempatan sekali saja
        $penempatan = PenempatanSiswa::find($this->ms_penempatan_siswa_id);

        if (!$penempatan) {
            $this->select_kategori = [];
            $this->tagihans = [];
            return;
        }

        $this->ms_jenjang_id    = $penempatan->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $penempatan->ms_tahun_ajar_id;

        // kategori
        $this->select_kategori = KategoriTagihanSiswa::select(
                'ms_kategori_tagihan_siswa_id',
                'nama_kategori_tagihan_siswa'
            )
            ->where('ms_jenjang_id', $this->ms_jenjang_id)
            ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
            ->get();

       $query = JenisTagihanSiswa::with([
            'ms_kategori_tagihan_siswa',
            'ms_tagihan_siswa' => function ($q) {
                $q->where(
                    'ms_penempatan_siswa_id',
                    $this->ms_penempatan_siswa_id
                );
            }
        ]);

        $query->where('ms_jenjang_id', $this->ms_jenjang_id)
        ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id);
        
        if ($this->selectedKategoriTagihan) {
            $query->where(
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id',
                $this->selectedKategoriTagihan
            );
        }

        if ($this->searchJenisTagihan) {
            $query->where(
                'ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa',
                'like',
                "%{$this->searchJenisTagihan}%"
            );
        }

        $this->tagihans = $query
            ->orderBy('ms_kategori_tagihan_siswa_id')
            ->orderBy('ms_jenis_tagihan_siswa_id')
            ->get()
            ->map(function ($item) {
                $tagihan = $item->ms_tagihan_siswa->first();

                return [
                    // Master Jenis Tagihan
                    'ms_jenis_tagihan_siswa_id' => $item->ms_jenis_tagihan_siswa_id,
                    'nama_jenis_tagihan_siswa' => $item->nama_jenis_tagihan_siswa,
                    'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo,
                    'cicilan_status' => $item->cicilan_status,

                    // Kategori
                    'nama_kategori_tagihan_siswa' =>
                        $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-',

                    // Existing Tagihan
                    'sudah_ditetapkan' => $tagihan !== null,
                    'ms_tagihan_siswa_id' => $tagihan->ms_tagihan_siswa_id ?? null,
                    'jumlah_tagihan_siswa' => $tagihan->jumlah_tagihan_siswa ?? null,
                    'status_tagihan' => $tagihan->status ?? null,
                    'deskripsi_tagihan' => $tagihan->deskripsi ?? null,
                ];
            })
            ->values()
            ->all();
    }

    public function canvasTagihan($penempatanSiswa, $jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
        $this->ms_penempatan_siswa_id = $penempatanSiswa;

        $this->resetSelectionState();
        $this->selectedKategoriTagihan = null;
        $this->searchJenisTagihan = '';

        // Ambil nama siswa dari relasi
        $penempatan = PenempatanSiswa::with('ms_siswa')->find($penempatanSiswa);
        $this->namaSiswa = $penempatan->ms_siswa->nama_siswa ?? 'Tidak Diketahui';

        // 🔥 load sekali
        $this->loadData();
    }

    public function updatedSelectAllTagihan($value)
    {
        if ($value) {
            $this->tagihanSelected = collect($this->tagihans)
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
            if (empty($this->tagihanSelected)) {
                throw new \Exception('Pilih jenis tagihan.');
            }

            $ms_pengguna_id = auth()->id();

            foreach ($this->tagihanSelected as $id) {
                $jumlah = $this->normalizeAmount($this->jumlahTagihan[$id] ?? null);

                if ($jumlah <= 0) {
                    throw new \Exception('Jumlah tagihan untuk item yang dipilih harus lebih dari 0.');
                }
            }

            // 🔒 Lock penempatan (biar aman)
            $penempatan = PenempatanSiswa::lockForUpdate()
                ->with('ms_siswa')
                ->find($this->ms_penempatan_siswa_id);

            if (!$penempatan) {
                throw new \Exception('Penempatan siswa tidak ditemukan.');
            }

            // 🔥 Ambil semua jenis tagihan SEKALI
            $jenisTagihans = JenisTagihanSiswa::whereIn(
                'ms_jenis_tagihan_siswa_id',
                $this->tagihanSelected
            )
                ->get()
                ->keyBy('ms_jenis_tagihan_siswa_id');

            // 🔥 Ambil tagihan yang sudah ada (anti duplicate)
            $existing = TagihanSiswa::lockForUpdate()
                ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                ->whereIn('ms_jenis_tagihan_siswa_id', $this->tagihanSelected)
                ->pluck('ms_jenis_tagihan_siswa_id')
                ->toArray();

            $existingIds = [];
            $insertData = [];

            $kode_rekening_piutang = 12001;
            $kode_rekening_pendapatan = 41001;

            foreach ($this->tagihanSelected as $id) {

                if (in_array($id, $existing)) {
                    $existingIds[] = $id;
                    continue;
                }

                $jumlah = $this->normalizeAmount($this->jumlahTagihan[$id] ?? null);

                if ($jumlah <= 0) {
                    throw new \Exception("Jumlah tagihan tidak valid.");
                }

                $jenis = $jenisTagihans[$id] ?? null;

                if (!$jenis) {
                    throw new \Exception("Jenis tagihan tidak ditemukan.");
                }

                $deskripsiJurnal = "Tagihan {$jenis->nama_jenis_tagihan_siswa} siswa {$penempatan->ms_siswa->nama_siswa}";

                // 🔥 jurnal debit
                $debitId = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $kode_rekening_piutang,
                    'posisi' => 'debit',
                    'nominal' => $jumlah,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'ms_departemen_id' => 'SEKOLAH',
                    'is_canceled' => 'active',
                    'deskripsi' => $deskripsiJurnal,
                ])->akuntansi_jurnal_detail_id;

                // 🔥 jurnal kredit
                $kreditId = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $kode_rekening_pendapatan,
                    'posisi' => 'kredit',
                    'nominal' => $jumlah,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'ms_departemen_id' => 'SEKOLAH',
                    'is_canceled' => 'active',
                    'deskripsi' => $deskripsiJurnal,
                ])->akuntansi_jurnal_detail_id;

                $insertData[] = [
                    'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                    'ms_jenis_tagihan_siswa_id' => $id,
                    'ms_pengguna_id' => $ms_pengguna_id,
                    'jumlah_tagihan_siswa' => $jumlah,
                    'status' => 'Belum Dibayar',
                    'deskripsi' => 'Tagihan baru',
                    'akuntansi_jurnal_detail_debit_id' => $debitId,
                    'akuntansi_jurnal_detail_kredit_id' => $kreditId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 🔥 Bulk insert (1x query)
            if (!empty($insertData)) {
                TagihanSiswa::insert($insertData);
            }

            DB::commit();

            // 🔥 reset state
            $this->resetSelectionState();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => !empty($existingIds)
                    ? 'Sebagian berhasil. Sudah ada: ' . implode(', ', $existingIds)
                    : 'Tagihan berhasil dibuat.'
            ]);

            $this->emit('refreshTagihanSiswa');
            $this->emit('reloadTagihanSiswa');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.aksi-tambah', [
            'select_kategori' => $this->select_kategori,
        ]);
    }

    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_string($value)) {
            $value = str_replace(['.', ','], '', $value);
        }

        return (int) $value;
    }
}
