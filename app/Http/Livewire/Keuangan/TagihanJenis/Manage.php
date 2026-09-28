<?php

namespace App\Http\Livewire\Keuangan\TagihanJenis;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\JenisTagihanSiswa;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use App\Models\Kelas;
use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use App\Services\AccountingService;
use Illuminate\Validation\ValidationException;

class Manage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 50;

    public $ms_jenis_tagihan_siswa_id; // Parameter jenis

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null; // Filter kelas

    // properti model
    public $jumlahTagihan;

    public $search = '';           // Pencarian tagihan

    public $TagihanSelected = [];
    public $TagihanSelectAll = false;
    public $tagihanOnPage = [];

    public $nama_petugas;

    protected $listeners = [
        'manageTagihan',
        'refreshTagihanSiswa' => '$refresh',
    ];

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedKelas()
    {
        $this->resetPage();
    }

     public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function manageTagihan($id)
    {
        $jenis = JenisTagihanSiswa::with('ms_kategori_tagihan_siswa')->find($id);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Tagihan dimuat'
        ]);

        $this->ms_jenis_tagihan_siswa_id = $id;

        $this->selectedJenjang = $jenis->ms_jenjang_id;
        $this->selectedTahunAjar = $jenis->ms_tahun_ajar_id;
        
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

            $this->jumlahTagihan = $this->normalizeAmount(
                $this->jumlahTagihan
            );

            if ($this->jumlahTagihan === null) {
                throw new \Exception(
                    'Jumlah tagihan harus diisi.'
                );
            }

            $this->validate([
                'jumlahTagihan' => 'integer|min:0',
            ], [
                'jumlahTagihan.integer' =>
                    'Jumlah tagihan harus berupa angka.',
                'jumlahTagihan.min' =>
                    'Jumlah tagihan tidak boleh kurang dari 0.',
            ]);

            $nominalBaru = $this->jumlahTagihan;

            // =====================================================
            // 3. LOAD & VALIDASI SEMUA TAGIHAN
            // =====================================================

            $tagihanList = [];

            foreach ($this->TagihanSelected as $ms_tagihan_siswa_id) {

                $tagihan = TagihanSiswa::with([
                    'ms_penempatan_siswa.ms_siswa',
                    'ms_jenis_tagihan_siswa',
                    'akuntansi_jurnal',
                ])
                    ->lockForUpdate()
                    ->find($ms_tagihan_siswa_id);

                if (!$tagihan) {
                    throw new \Exception(
                        "Tagihan dengan ID {$ms_tagihan_siswa_id} tidak ditemukan."
                    );
                }

                // =================================================
                // 3a. VALIDASI JURNAL
                // =================================================

                // if (!$tagihan->akuntansi_jurnal) {
                //     throw new \Exception(
                //         "Jurnal tagihan untuk ID {$ms_tagihan_siswa_id} tidak ditemukan."
                //     );
                // }

                // =================================================
                // 3b. DATA SISWA & JENIS TAGIHAN
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
                // 3c. JUMLAH SUDAH DIBAYAR
                // =================================================

                $jumlahSudahDibayar =
                    $tagihan->jumlah_sudah_dibayar();

                // =================================================
                // 3d. VALIDASI NOMINAL BARU
                // =================================================

                if ($nominalBaru < $jumlahSudahDibayar) {
                    throw new \Exception(
                        sprintf(
                            'Tagihan %s tidak dapat diubah menjadi Rp%s karena sudah dibayar Rp%s.',
                            $namaSiswa,
                            number_format(
                                $nominalBaru,
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

                // =================================================
                // SIMPAN DATA UNTUK TAHAP UPDATE
                // =================================================

                $tagihanList[] = [
                    'tagihan' => $tagihan,
                    'namaSiswa' => $namaSiswa,
                    'namaJenis' => $namaJenis,
                    'jumlahSudahDibayar' => $jumlahSudahDibayar,
                ];
            }

            // =====================================================
            // 4. UPDATE SEMUA TAGIHAN & JURNAL
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

                } elseif ($nominalBaru > $jumlahSudahDibayar) {

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
                                'nominal' => $nominalBaru,
                            ],
                            [
                                'kode_rekening' => 41001,
                                'posisi' => 'kredit',
                                'nominal' => $nominalBaru,
                            ],
                        ],
                    ]
                );

                // =================================================
                // UPDATE TAGIHAN
                // =================================================

                $tagihan->update([
                    'jumlah_tagihan_siswa' => $nominalBaru,
                    'status' => $status,
                    'deskripsi' => sprintf(
                        'Nominal tagihan diubah dari Rp%s menjadi Rp%s oleh %s',
                        number_format(
                            $nominalLama,
                            0,
                            ',',
                            '.'
                        ),
                        number_format(
                            $nominalBaru,
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

            throw $e;

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent(
                'alertify-error',
                [
                    'message' =>
                        'Terjadi kesalahan: ' . ($e->getMessage() ?? 'Terjadi kesalahan sistem')
                ]
            );
        }
    }

    public function render()
    {
        // Query untuk memilih kelas berdasarkan jenjang dan tahun ajar
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        // Query untuk mendapatkan tagihan berdasarkan jenis tagihan dengan JOIN
        $query = TagihanSiswa::query()
            ->with([
                'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa',
                'ms_penempatan_siswa.ms_siswa',
                'ms_penempatan_siswa.ms_kelas'
            ])
            ->select('ms_tagihan_siswa.*') // 🔥 penting biar tidak bentrok
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tagihan_siswa.ms_penempatan_siswa_id')
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_penempatan_siswa.ms_siswa_id')
            ->join('ms_kelas', 'ms_kelas.ms_kelas_id', '=', 'ms_penempatan_siswa.ms_kelas_id')
            ->where('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $this->ms_jenis_tagihan_siswa_id)
            ->withSum([
                'dt_transaksi_tagihan_siswa as total_bayar' => function ($query) {
                    $query->where('status_transaksi', '!=', 'dibatalkan');
                }
            ], 'jumlah_bayar');
        // FILTER tetap pakai relation (clean)
        if ($this->selectedKelas) {
            $query->where('ms_kelas.ms_kelas_id', $this->selectedKelas);
        }

        if ($this->search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $this->search . '%');
        }

        // ORDER BY jadi simple & cepat
        $tagihans = $query
            ->orderBy('ms_kelas.nama_kelas')
            ->orderBy('ms_siswa.nama_siswa')
            ->paginate($this->perPage);

        // TOTAL
        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($t) => $t->total_bayar ?? 0);
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;
        
        $this->tagihanOnPage = $tagihans->items();

        return view('livewire.keuangan.tagihan-jenis.manage', compact(
            'select_kelas',
            'tagihans',
            'totalEstimasi',
            'totalDibayarkan',
            'totalKekurangan'
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
