<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use Livewire\WithPagination;
use Livewire\Component;

class Create extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $siswasOnPage = [];
    public $tagihansOnPage = [];

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
        $this->emitSelf('render');
    }

    public function updatingSearchSiswa()
    {
        $this->resetPage(); // Reset pagination saat pencarian berubah
    }

    public function updatingsearchJenisTagihan()
    {
        $this->resetPage(); // Reset pagination saat pencarian berubah
    }

    // checkbox
    public function updatedSelectAllSiswa($value)
    {
        if ($value) {
            // Tambahkan semua ID siswa dari halaman aktif
            $this->siswaSelected = collect($this->siswasOnPage)->pluck('ms_penempatan_siswa_id')->toArray();
        } else {
            // Kosongkan siswaSelected
            $this->siswaSelected = [];
        }
    }

    public function updatedselectAllTagihan($value)
    {
        if ($value) {
            $this->tagihanSelected = collect($this->tagihansOnPage)->pluck('ms_jenis_tagihan_siswa_id')->toArray();
        } else {
            $this->tagihanSelected = [];
        }
    }

    public function createTagihan()
    {
        if (empty($this->siswaSelected)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih siswa yang ingin ditambahkan.']);
            return;
        }

        if (empty($this->tagihanSelected)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih jenis tagihan yang ingin ditambahkan.']);
            return;
        }

        $this->validate([
            'siswaSelected' => 'required|array|min:1',
            'tagihanSelected' => 'required|array|min:1',
            'jumlahTagihan.*' => 'required|numeric|min:1',
        ]);

        $kode_rekening_debit = 12001;
        $kode_rekening_kredit = 41001;

        $tagihanData = [];

        try {
            foreach ($this->siswaSelected as $ms_penempatan_siswa_id) {
                $penempatanSiswa = PenempatanSiswa::with('ms_siswa')->find($ms_penempatan_siswa_id);
                foreach ($this->tagihanSelected as $ms_jenis_tagihan_siswa_id) {
                    if (empty($this->jumlahTagihan[$ms_jenis_tagihan_siswa_id])) {
                        $this->dispatchBrowserEvent('alertify-error', ['message' => 'Mohon isi jumlah tagihan untuk setiap tagihan yang dipilih.']);
                        return;
                    }

                    $jumlahTagihan = $this->jumlahTagihan[$ms_jenis_tagihan_siswa_id];

                    $existingTagihan = TagihanSiswa::where('ms_penempatan_siswa_id', $ms_penempatan_siswa_id)
                        ->where('ms_jenis_tagihan_siswa_id', $ms_jenis_tagihan_siswa_id)
                        ->exists();

                    if ($existingTagihan) {
                        continue;
                    }

                    $jenisTagihan = JenisTagihanSiswa::find($ms_jenis_tagihan_siswa_id);
                    $deskripsiJurnal = "Tagihan {$jenisTagihan->nama_jenis_tagihan_siswa} siswa {$penempatanSiswa->ms_siswa->nama_siswa}";

                    // Data untuk jurnal debit
                    $jurnalDebit = [
                        'kode_rekening' => $kode_rekening_debit,
                        'posisi' => 'debit',
                        'nominal' => $jumlahTagihan,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $this->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsiJurnal,
                    ];
                    $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

                    // Data untuk jurnal kredit
                    $jurnalKredit = [
                        'kode_rekening' => $kode_rekening_kredit,
                        'posisi' => 'kredit',
                        'nominal' => $jumlahTagihan,
                        'tanggal_transaksi' => now(),
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                        'ms_jenjang_id' => $this->ms_jenjang_id,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsiJurnal,
                    ];
                    $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

                    // Data untuk tagihan
                    $tagihanData[] = [
                        'ms_penempatan_siswa_id' => $ms_penempatan_siswa_id,
                        'ms_jenis_tagihan_siswa_id' => $ms_jenis_tagihan_siswa_id,
                        'ms_pengguna_id' => auth()->id(),
                        'jumlah_tagihan_siswa' => $jumlahTagihan,
                        'status' => 'Belum Dibayar',
                        'deskripsi' => 'Tagihan baru',
                        'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                        'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Bulk insert ke database
            if (!empty($tagihanData)) {
                TagihanSiswa::insert($tagihanData);
            }

            // Reset data dan beri notifikasi
            $this->selectAllSiswa = false;
            $this->selectAllTagihan = false;
            $this->siswaSelected = [];
            $this->tagihanSelected = [];
            $this->jumlahTagihan = [];
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil dibuat untuk siswa yang dipilih!']);
            $this->emitSelf('$refresh');
            $this->emit('refreshTagihans');
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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

        // Data siswa (hanya jika Jenjang dan Tahun Ajar dipilih)
        $siswas = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $query = PenempatanSiswa::with(['ms_siswa', 'ms_kelas', 'ms_tahun_ajar', 'ms_jenjang'])
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->where('ms_penempatan_siswa.ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->ms_tahun_ajar_id);

            // Filter berdasarkan kelas (jika dipilih)
            if ($this->selectedKelas) {
                $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
            }

            // Filter berdasarkan pencarian nama siswa
            if ($this->searchSiswa) {
                $query->where('ms_siswa.nama_siswa', 'like', '%' . $this->searchSiswa . '%');
            }

            // Urutkan berdasarkan ID kelas terlebih dahulu, lalu nama siswa (abjad)
            $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
                ->orderBy('ms_siswa.nama_siswa')
                ->select('ms_penempatan_siswa.*') // Pastikan hanya kolom dari PenempatanSiswa yang diambil
                ->paginate(1000);

            // Simpan data siswa di halaman aktif ke properti $siswasOnPage
            $this->siswasOnPage = $siswas->items();
        }

        $select_kategori = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kategori = KategoriTagihanSiswa::with(['ms_jenjang', 'ms_tahun_ajar'])
                ->where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }

        $jenis_tagihans = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $query = JenisTagihanSiswa::with(['ms_kategori_tagihan_siswa', 'ms_tahun_ajar', 'ms_jenjang'])
                ->where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id);

            if ($this->selectedKategoriTagihan) {
                $query->where('ms_kategori_tagihan_siswa_id', $this->selectedKategoriTagihan);
            }

            // Filter berdasarkan pencarian nama
            if ($this->searchJenisTagihan) {
                $query->where('nama_jenis_tagihan_siswa', 'like', '%' . $this->searchJenisTagihan . '%');
            }

            $jenis_tagihans = $query
                ->orderBy('ms_kategori_tagihan_siswa_id', 'ASC')
                ->orderBy('ms_jenis_tagihan_siswa_id', 'ASC')->paginate(1000);

            $this->tagihansOnPage = $jenis_tagihans->items();
        }

        return view('livewire.tagihan-siswa.create', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,

            'select_kategori' => $select_kategori,
            'jenis_tagihans' => $jenis_tagihans,
        ]);
    }
}
