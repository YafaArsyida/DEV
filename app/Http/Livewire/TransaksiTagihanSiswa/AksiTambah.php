<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class AksiTambah extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $tagihansOnPage = [];

    // Properties
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $ms_penempatan_siswa_id;
    public $namaSiswa;

    // fitur pencarian
    public $searchJenisTagihan = ''; // Pencarian tagihan

    // fitur filter
    public $selectedKategoriTagihan;

    // properti model
    public $jumlahTagihan = [];

    // fitur checkbox 
    public $tagihanSelected = []; // ID jenis tagihan yang dipilih

    public $selectAllTagihan = false;

    // Listeners
    protected $listeners = ['showModalTambah' => 'showModalTambah'];

    public function updatingsearchJenisTagihan()
    {
        $this->resetPage(); // Reset pagination saat pencarian berubah
    }

    public function showModalTambah($penempatanSiswa, $jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
        $this->ms_penempatan_siswa_id = $penempatanSiswa;

        $this->selectAllTagihan = false;
        $this->tagihanSelected = [];
        $this->jumlahTagihan = [];

        // Ambil nama siswa dari relasi
        $penempatan = PenempatanSiswa::with('ms_siswa')->find($penempatanSiswa);
        $this->namaSiswa = $penempatan->ms_siswa->nama_siswa ?? 'Tidak Diketahui';

        $this->resetPage(); // Reset pagination saat parameter berubah
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
        if (empty($this->tagihanSelected)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih jenis tagihan yang ingin ditambahkan.']);
            return;
        }

        $this->validate([
            'tagihanSelected' => 'required|array|min:1',
            'jumlahTagihan.*' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $existingTagihanIds = [];
            $kode_rekening_piutang = 12001;
            $kode_rekening_pendapatan = 41001;

            $penempatanSiswa = PenempatanSiswa::with('ms_siswa')->find($this->ms_penempatan_siswa_id);
            $tagihanData = [];

            foreach ($this->tagihanSelected as $ms_jenis_tagihan_siswa_id) {
                if (empty($this->jumlahTagihan[$ms_jenis_tagihan_siswa_id])) {
                    $this->dispatchBrowserEvent('alertify-error', ['message' => 'Mohon isi jumlah tagihan dan tanggal jatuh tempo untuk setiap tagihan yang dipilih.']);
                    return;
                }

                $jumlahTagihan = $this->jumlahTagihan[$ms_jenis_tagihan_siswa_id];

                $existingTagihan = TagihanSiswa::where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                    ->where('ms_jenis_tagihan_siswa_id', $ms_jenis_tagihan_siswa_id)
                    ->exists();

                if ($existingTagihan) {
                    $existingTagihanIds[] = $ms_jenis_tagihan_siswa_id;
                    continue;
                }

                $jenisTagihan = JenisTagihanSiswa::find($ms_jenis_tagihan_siswa_id);
                $deskripsiJurnal = "Tagihan {$jenisTagihan->nama_jenis_tagihan_siswa} siswa {$penempatanSiswa->ms_siswa->nama_siswa}";

                $jurnalDebitId = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $kode_rekening_piutang,
                    'posisi' => 'debit',
                    'nominal' => $jumlahTagihan,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => auth()->id(),
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'is_canceled' => 'active',
                    'deskripsi' => $deskripsiJurnal,
                ])->akuntansi_jurnal_detail_id;

                $jurnalKreditId = AkuntansiJurnalDetail::create([
                    'kode_rekening' => $kode_rekening_pendapatan,
                    'posisi' => 'kredit',
                    'nominal' => $jumlahTagihan,
                    'tanggal_transaksi' => now(),
                    'ms_pengguna_id' => auth()->id(),
                    'ms_tahun_ajaran_id' => $this->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $this->ms_jenjang_id,
                    'is_canceled' => 'active',
                    'deskripsi' => $deskripsiJurnal,
                ])->akuntansi_jurnal_detail_id;

                $tagihanData[] = [
                    'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
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

            if (!empty($tagihanData)) {
                TagihanSiswa::insert($tagihanData);
            }

            DB::commit();

            if (!empty($existingTagihanIds)) {
                $this->dispatchBrowserEvent('alertify-success', [
                    'message' => 'Tagihan berhasil dibuat! Namun, beberapa tagihan sudah ada: ' . implode(', ', $existingTagihanIds),
                ]);
            } else {
                $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil dibuat!']);
            }

            $this->reset(['selectAllTagihan', 'tagihanSelected', 'jumlahTagihan']);
            $this->emitSelf('$refresh');
            $this->emit('tagihanUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
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

        return view('livewire.transaksi-tagihan-siswa.aksi-tambah', [
            'select_kategori' => $select_kategori,
            'jenis_tagihans' => $jenis_tagihans,
        ]);
    }
}
