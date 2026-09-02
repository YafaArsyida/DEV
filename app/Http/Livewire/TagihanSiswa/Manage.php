<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use App\Services\AccountingService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Manage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $perPage = 50;

    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $ms_penempatan_siswa_id;
    public $nama_siswa;

    public $selectedKategori;      // Filter kategori

    // properti model
    public $jumlahTagihan;

    public $search = '';           // Pencarian tagihan

    public $TagihanSelected = [];
    public $TagihanSelectAll = false;
    public $tagihanOnPage = [];

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'manageTagihan',

        'refreshTagihanSiswa' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingselectedKategori()
    {
        $this->resetPage();
    }

    public function updatingSelectedJenjang()
    {
        $this->resetPage();
    }

    public function updatingSelectedTahunAjar()
    {
        $this->resetPage();
    }

    public function manageTagihan($id)
    {
        $penempatan = PenempatanSiswa::with('ms_siswa')->find($id);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Tagihan dimuat'
        ]);

        $this->ms_penempatan_siswa_id = $id;
        $this->ms_jenjang_id = $penempatan->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $penempatan->ms_tahun_ajar_id;
        $this->nama_siswa = $penempatan->ms_siswa->nama_siswa ?? 'Tidak Diketahui';

        $this->TagihanSelectAll = false;
        $this->TagihanSelected = [];
    }

    public function updatedTagihanSelectAll($value)
    {
        if ($value) {
            // Tambahkan semua ID tagihan dari halaman aktif
            $this->TagihanSelected = collect($this->tagihanOnPage)->pluck('ms_tagihan_siswa_id')->toArray();
        } else {
            // Kosongkan TagihanSelected
            $this->TagihanSelected = [];
        }
    }

    // HAPUS TAGIHAN
    public function HapusTagihan()
    {
        DB::beginTransaction();

        try {
            $jumlahBerhasil = 0;
            $jumlahGagal = 0;

            foreach ($this->TagihanSelected as $ms_tagihan_siswa_id) {

                // =====================================================
                // 1. LOCK TAGIHAN
                // =====================================================
                $tagihan = TagihanSiswa::with([
                    'ms_jenis_tagihan_siswa',
                ])
                    ->lockForUpdate()
                    ->find($ms_tagihan_siswa_id);

                if (!$tagihan) {
                    $jumlahGagal++;
                    continue;
                }

                // =====================================================
                // 2. VALIDASI STATUS
                // =====================================================
                // if ($tagihan->status_transaksi === 'dibatalkan') {
                //     $jumlahGagal++;
                //     continue;
                // }

                // =====================================================
                // 3. CEK PEMBAYARAN
                // =====================================================
                $isInDetailTransaksi = DetailTransaksiTagihanSiswa::where(
                    'ms_tagihan_siswa_id',
                    $tagihan->ms_tagihan_siswa_id
                )->exists();

                if ($isInDetailTransaksi) {
                    $jumlahGagal++;
                    continue;
                }

                // =====================================================
                // 4. CEK KERANJANG
                // =====================================================
                $isInKeranjang = KeranjangTagihanSiswa::where(
                    'ms_tagihan_siswa_id',
                    $tagihan->ms_tagihan_siswa_id
                )->exists();

                if ($isInKeranjang) {
                    $jumlahGagal++;
                    continue;
                }

                // =====================================================
                // 5. HAPUS JURNAL
                // =====================================================
                if ($tagihan->akuntansi_jurnal_id) {
                    AccountingService::delete(
                        $tagihan->akuntansi_jurnal_id
                    );
                }

                // =====================================================
                // 6. SIMPAN INFORMASI PENGHAPUS
                // =====================================================
                $namaJenisTagihan =
                    $tagihan->ms_jenis_tagihan_siswa
                        ->nama_jenis_tagihan_siswa
                        ?? 'Tagihan';

                $tagihan->update([
                    'ms_pengguna_id' => auth()->user()->ms_pengguna_id,
                    'deskripsi' => sprintf(
                        'Tagihan %s dihapus oleh %s',
                        $namaJenisTagihan,
                        $this->nama_petugas
                    ),
                ]);

                // =====================================================
                // 7. SOFT DELETE TAGIHAN
                // =====================================================
                $tagihan->delete();

                $jumlahBerhasil++;
            }

            // =====================================================
            // 8. COMMIT
            // =====================================================
            DB::commit();

            // =====================================================
            // 9. RESET STATE
            // =====================================================
            $this->TagihanSelectAll = false;
            $this->TagihanSelected = [];

            // =====================================================
            // 10. REFRESH DATA
            // =====================================================
            $this->emitSelf('$refresh');
            $this->emit('refreshTagihanSiswa');

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAksiDeleteMultiple'
            ]);

            // =====================================================
            // 11. NOTIFIKASI
            // =====================================================
            if ($jumlahBerhasil > 0 && $jumlahGagal > 0) {

                $this->dispatchBrowserEvent('alertify-warning', [
                    'message' =>
                        "{$jumlahBerhasil} tagihan berhasil dihapus, "
                        . "{$jumlahGagal} tagihan tidak dapat dihapus."
                ]);

            } elseif ($jumlahBerhasil > 0) {

                $this->dispatchBrowserEvent('alertify-success', [
                    'message' =>
                        "{$jumlahBerhasil} tagihan berhasil dihapus."
                ]);

            } else {

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' =>
                        'Tidak ada tagihan yang dapat dihapus.'
                ]);
            }

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->TagihanSelectAll = false;
            $this->TagihanSelected = [];

            $this->dispatchBrowserEvent('alertify-error', [
                'message' =>
                    'Terjadi kesalahan saat menghapus data: '
                    . ($e->getMessage() ?? 'Terjadi kesalahan sistem')
            ]);
        }
    }
    // EDIT TAGIHAN
    public function editTagihan()
    {
        DB::beginTransaction();

        try {
            // =====================================================
            // 1. VALIDASI DATA YANG DIPILIH
            // =====================================================
            if (empty($this->TagihanSelected)) {
                throw new \Exception(
                    'Tidak ada data tagihan yang dipilih.'
                );
            }

            // =====================================================
            // 2. NORMALISASI & VALIDASI NOMINAL
            // =====================================================
            $this->jumlahTagihan = $this->normalizeAmount($this->jumlahTagihan);
            if ($this->jumlahTagihan === null) {
                throw new \Exception(
                    'Jumlah tagihan harus diisi.'
                );
            }

            $this->validate([
                'jumlahTagihan' => 'integer|min:0',
            ], [
                'jumlahTagihan.integer' => 'Jumlah tagihan harus berupa angka.',

                'jumlahTagihan.min' =>'Jumlah tagihan tidak boleh kurang dari 0.',
            ]);

            // =====================================================
            // 3. VALIDASI SEMUA TAGIHAN TERLEBIH DAHULU
            // =====================================================
            $tagihanList = [];

            foreach ($this->TagihanSelected as $ms_tagihan_siswa_id) {

                $tagihan = TagihanSiswa::with([
                    'ms_penempatan_siswa.ms_siswa',
                    'ms_jenis_tagihan_siswa',
                ])
                    ->lockForUpdate()
                    ->find($ms_tagihan_siswa_id);

                if (!$tagihan) {
                    throw new \Exception(
                        "Tagihan dengan ID {$ms_tagihan_siswa_id} tidak ditemukan."
                    );
                }

                // =================================================
                // 3a. VALIDASI STATUS
                // =================================================
                // if ($tagihan->status_transaksi === 'dibatalkan') {
                //     throw new \Exception(
                //         'Tagihan yang sudah dibatalkan tidak dapat diedit.'
                //     );
                // }

                // =================================================
                // 3b. VALIDASI JURNAL
                // =================================================
                // if (!$tagihan->akuntansi_jurnal_id) {
                //     throw new \Exception(
                //         'Jurnal tagihan tidak ditemukan.'
                //     );
                // }

                // =================================================
                // 3c. DATA SISWA & JENIS TAGIHAN
                // =================================================
                $namaSiswa =
                    $tagihan->ms_penempatan_siswa
                        ?->ms_siswa
                        ?->nama_siswa
                        ?? '-';

                $namaJenis =
                    $tagihan->ms_jenis_tagihan_siswa
                        ?->nama_jenis_tagihan_siswa
                        ?? 'Tagihan';

                // =================================================
                // 3d. JUMLAH YANG SUDAH DIBAYAR
                // =================================================
                $jumlahSudahDibayar =
                    $tagihan->jumlah_sudah_dibayar();

                // =================================================
                // 3e. VALIDASI NOMINAL BARU
                // =================================================
                if (
                    $this->jumlahTagihan < $jumlahSudahDibayar
                ) {
                    throw new \Exception(
                        sprintf(
                            'Tagihan %s tidak dapat diubah menjadi Rp%s '
                            . 'karena sudah dibayar Rp%s.',
                            $namaSiswa,
                            number_format(
                                $this->jumlahTagihan,
                                0,
                                ',',
                                '.'
                            ),
                            number_format(
                                $jumlahSudahDibayar,
                                0,
                                ',',
                                '.'
                            )
                        )
                    );
                }

                // Simpan untuk tahap update
                $tagihanList[] = [
                    'tagihan' => $tagihan,
                    'namaSiswa' => $namaSiswa,
                    'namaJenis' => $namaJenis,
                    'jumlahSudahDibayar' => $jumlahSudahDibayar,
                ];
            }

            // =====================================================
            // 4. UPDATE SEMUA TAGIHAN
            // =====================================================
            foreach ($tagihanList as $item) {

                $tagihan = $item['tagihan'];
                $namaSiswa = $item['namaSiswa'];
                $namaJenis = $item['namaJenis'];
                $jumlahSudahDibayar = $item['jumlahSudahDibayar'];

                // =================================================
                // NOMINAL LAMA
                // =================================================
                $nominalLama =
                    $tagihan->getOriginal(
                        'jumlah_tagihan_siswa'
                    );

                // =================================================
                // TENTUKAN STATUS
                // =================================================
                if ($jumlahSudahDibayar == 0) {

                    $status = 'Belum Dibayar';

                } elseif (
                    $this->jumlahTagihan > $jumlahSudahDibayar
                ) {

                    $status = 'Masih Dicicil';

                } else {

                    $status = 'Lunas';
                }

                // =================================================
                // DESKRIPSI JURNAL
                // =================================================
                $deskripsiJurnal = sprintf(
                    'Tagihan %s siswa %s',
                    $namaJenis,
                    $namaSiswa
                );

                // =================================================
                // UPDATE JURNAL
                // =================================================
                AccountingService::update(
                    $tagihan->akuntansi_jurnal_id,
                    [
                        'tanggal' => $tagihan->created_at,
                        'deskripsi' => $deskripsiJurnal,

                        'detail' => [
                            [
                                'kode_rekening' => 12001,
                                'posisi' => 'debit',
                                'nominal' => $this->jumlahTagihan,
                            ],
                            [
                                'kode_rekening' => 41001,
                                'posisi' => 'kredit',
                                'nominal' => $this->jumlahTagihan,
                            ],
                        ],
                    ]
                );

                // =================================================
                // UPDATE TAGIHAN
                // =================================================
                $tagihan->update([
                    'jumlah_tagihan_siswa' => $this->jumlahTagihan,
                    'status' => $status,
                    'deskripsi' => sprintf(
                        'Nominal tagihan diubah dari Rp%s ' . 'menjadi Rp%s oleh %s',
                        number_format(
                            $nominalLama,
                            0,
                            ',',
                            '.'
                        ),
                        number_format(
                            $this->jumlahTagihan,
                            0,
                            ',',
                            '.'
                        ),
                        $this->nama_petugas
                    ),
                ]);
            }

            // =====================================================
            // 5. COMMIT
            // =====================================================
            DB::commit();

            // =====================================================
            // 6. RESET STATE
            // =====================================================
            $this->jumlahTagihan = null;
            $this->TagihanSelected = [];
            $this->TagihanSelectAll = false;

            // =====================================================
            // 7. REFRESH DATA
            // =====================================================
            $this->emitSelf('$refresh');
            $this->emit('refreshTagihanSiswa');

            // =====================================================
            // 8. NOTIFIKASI
            // =====================================================
            $this->dispatchBrowserEvent(
                'alertify-success',
                [
                    'message' => 'Tagihan berhasil diperbarui.'
                ]
            );

        } catch (ValidationException $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent(
                'alertify-error',
                [
                    'message' => 'Gagal validasi, cek input!'
                ]
            );

            // Agar error validation tetap bisa
            // ditampilkan oleh Blade/Livewire.
            throw $e;

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent(
                'alertify-error',
                [
                    'message' =>
                        'Terjadi kesalahan: '
                        . (
                            $e->getMessage()
                            ?? 'Terjadi kesalahan sistem'
                        )
                ]
            );
        }
    }

    public function render()
    {
        $select_kategori = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kategori = KategoriTagihanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }
        // Query Tagihan
        $query = TagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas'
            ])
            ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar');

        // FILTER KATEGORI
        if ($this->selectedKategori) {
            $query->whereRelation(
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_kategori_tagihan_siswa_id',
                $this->selectedKategori
            );
        }

        // SEARCH
        if ($this->search) {
            $query->whereRelation(
                'ms_jenis_tagihan_siswa',
                'nama_jenis_tagihan_siswa',
                'like',
                '%' . $this->search . '%'
            );
        }

        $tagihans = $query
            ->orderBy('ms_jenis_tagihan_siswa_id')
            ->paginate($this->perPage);

        // Simpan data tagihan dari halaman aktif
        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($t) => $t->total_bayar ?? 0);
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

        $this->tagihanOnPage = $tagihans->items();

        return view('livewire.tagihan-siswa.manage', [
            'select_kategori' => $select_kategori,
            'tagihans' => $tagihans,
            'totalEstimasi' => $totalEstimasi,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
        ]);
    }
    private function normalizeAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) str_replace('.', '', $value);
    }
}
