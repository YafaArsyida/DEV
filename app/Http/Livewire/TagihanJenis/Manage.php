<?php

namespace App\Http\Livewire\TagihanJenis;

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
            $anyTagihanDeleted = false;

            foreach ($this->TagihanSelected as $ms_tagihan_siswa_id) {

                $tagihan = TagihanSiswa::find($ms_tagihan_siswa_id);

                if (!$tagihan) {
                    continue;
                }

                // Cek apakah tagihan sudah pernah dibayar
                $isInDetailTransaksi = DetailTransaksiTagihanSiswa::where(
                    'ms_tagihan_siswa_id',
                    $ms_tagihan_siswa_id
                )->exists();

                if ($isInDetailTransaksi) {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => 'Tagihan tidak dapat dihapus karena memiliki riwayat pembayaran.'
                    ]);
                    continue;
                }

                // Cek apakah masih berada di keranjang
                $isInKeranjang = KeranjangTagihanSiswa::where(
                    'ms_tagihan_siswa_id',
                    $tagihan->ms_tagihan_siswa_id
                )->exists();

                if ($isInKeranjang) {
                    $this->dispatchBrowserEvent('alertify-error', [
                        'message' => 'Tagihan tidak dapat dihapus karena masuk keranjang.'
                    ]);
                    continue;
                }

                // Hapus jurnal melalui AccountingService
                if ($tagihan->akuntansi_jurnal_id) {
                    AccountingService::delete(
                        $tagihan->akuntansi_jurnal_id
                    );
                }

                // Simpan informasi penghapusan
                $tagihan->ms_pengguna_id = auth()->user()->ms_pengguna_id;

                $tagihan->deskripsi = sprintf(
                    'Tagihan %s dihapus oleh %s',
                    $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa,
                    $this->nama_petugas
                );

                $tagihan->save();

                // Soft delete tagihan
                $tagihan->delete();

                $anyTagihanDeleted = true;
            }

            // Commit transaksi jika berhasil
            DB::commit();
            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAksiDeleteMultiple'
            ]);

            if ($anyTagihanDeleted) {
                $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil dihapus.']);
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }

        $this->TagihanSelectAll = false;
        $this->TagihanSelected = [];

        $this->emitSelf('$refresh');
        $this->emit('refreshTagihanSiswa');
    }

    // EDIT TAGIHAN
    public function editTagihan()
    {
        DB::beginTransaction();

        try {
            $this->jumlahTagihan = $this->normalizeAmount($this->jumlahTagihan);
            if (empty($this->TagihanSelected)) {
                throw new \Exception('Tidak ada data yang dipilih');
            }

            // Validasi fleksibel berdasarkan input yang diberikan
            $rules = [];
            $messages = [];

            if ($this->jumlahTagihan !== null) {
                $rules['jumlahTagihan'] = 'numeric|min:0';
                $messages['jumlahTagihan.numeric'] = 'Jumlah tagihan harus berupa angka.';
                $messages['jumlahTagihan.min'] = 'Jumlah tagihan tidak boleh kurang dari 0.';
            }

            // Validasi hanya jika ada input
            if (!empty($rules)) {
                $this->validate($rules, $messages);
            }

            // Loop untuk memperbarui tagihan yang dipilih
            foreach ($this->TagihanSelected as $ms_tagihan_siswa_id) {

                $tagihan = TagihanSiswa::find($ms_tagihan_siswa_id);

                if (!$tagihan) {
                    continue;
                }

                $dataToUpdate = [];

                $tagihan->load([
                    'ms_penempatan_siswa.ms_siswa',
                    'ms_jenis_tagihan_siswa',
                ]);

                $namaSiswa = $tagihan->ms_penempatan_siswa->ms_siswa->nama_siswa;
                $namaJenis = $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa;

                $deskripsiJurnal = sprintf('Tagihan %s siswa %s', $namaJenis, $namaSiswa);

                // Jumlah yang sudah dibayar
                $jumlahSudahDibayar = $tagihan->jumlah_sudah_dibayar();

                if ($this->jumlahTagihan !== null) {

                    if ($this->jumlahTagihan < $jumlahSudahDibayar) {
                        throw new \Exception(
                            'Jumlah tagihan tidak boleh kurang dari jumlah yang sudah dibayarkan (' . number_format($jumlahSudahDibayar) . ').'
                        );
                    }

                    // Update nominal tagihan
                    $dataToUpdate['jumlah_tagihan_siswa'] = $this->jumlahTagihan;

                    // Tentukan status
                    if ($jumlahSudahDibayar == 0) {
                        $dataToUpdate['status'] = 'Belum Dibayar';
                    } elseif ($this->jumlahTagihan > $jumlahSudahDibayar) {
                        $dataToUpdate['status'] = 'Masih Dicicil';
                    } else {
                        $dataToUpdate['status'] = 'Lunas';
                    }

                    // Update jurnal melalui AccountingService
                    AccountingService::update(
                        $tagihan->akuntansi_jurnal_id,
                        [
                            'tanggal'   => $tagihan->created_at,
                            'deskripsi' => $deskripsiJurnal,
                            'detail'    => [
                                [
                                    'kode_rekening' => 12001,
                                    'posisi'        => 'debit',
                                    'nominal'       => $this->jumlahTagihan,
                                ],
                                [
                                    'kode_rekening' => 41001,
                                    'posisi'        => 'kredit',
                                    'nominal'       => $this->jumlahTagihan,
                                ],
                            ],
                        ]
                    );
                }

                $dataToUpdate['deskripsi'] = sprintf(
                    'Nominal tagihan diubah dari Rp%s menjadi Rp%s oleh %s',
                    number_format($tagihan->getOriginal('jumlah_tagihan_siswa'), 0, ',', '.'),
                    number_format($this->jumlahTagihan, 0, ',', '.'),
                    $this->nama_petugas
                );

                if (!empty($dataToUpdate)) {
                    $tagihan->update($dataToUpdate);
                }
            }

            // Commit transaksi
            DB::commit();

            // Reset input
            $this->jumlahTagihan = null;
            $this->TagihanSelected = [];
            $this->TagihanSelectAll = false;

            // Emit event untuk refresh data
            $this->emitSelf('$refresh');
            $this->emit('refreshTagihanSiswa');

            // Berikan notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil diperbarui.']);
        } catch (\Throwable $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Berikan notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar');

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

        return view('livewire.tagihan-jenis.manage', compact(
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
