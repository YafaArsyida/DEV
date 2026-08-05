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

class Manage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

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
        $this->emitSelf('$refresh');
    }

    public function updatingselectedKategori()
    {
        $this->emitSelf('$refresh');
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
            $anyTagihanDeleted = false;
            foreach ($this->TagihanSelected as $ms_tagihan_siswa_id) {
                $tagihan = TagihanSiswa::find($ms_tagihan_siswa_id);

                if ($tagihan) {
                    // Cek apakah tagihan sudah pernah dibayar
                    $isInDetailTransaksi = DetailTransaksiTagihanSiswa::where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)->exists();

                    if ($isInDetailTransaksi) {
                        // Jika sudah pernah dibayar, tampilkan pesan error dan lewati proses penghapusan
                        $this->dispatchBrowserEvent('alertify-error', ['message' => "Tagihan tidak dapat dihapus karena memiliki riwayat pembayaran."]);
                        continue;
                    }

                    $isInKeranjang = KeranjangTagihanSiswa::where('ms_tagihan_siswa_id', $tagihan->ms_tagihan_siswa_id)->first();
                    if ($isInKeranjang) {
                        $this->dispatchBrowserEvent('alertify-error', ['message' => "Tagihan tidak dapat dihapus karena masuk keranjang"]);
                        continue;
                    }

                    // Hapus akuntansi
                    AccountingService::delete(
                        $tagihan->akuntansi_jurnal_id
                    );

                    $tagihan->ms_pengguna_id = auth()->user()->ms_pengguna_id; // Set pengguna yang menghapus
                    
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
            }
            DB::commit();
            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAksiDeleteMultiple'
            ]);

            if ($anyTagihanDeleted) {
                $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil dihapus.']);
            }
            // Commit transaksi jika berhasil
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
                $rules['jumlahTagihan'] = 'integer|min:0';
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
                if ($tagihan) {
                    $dataToUpdate = [];

                    $tagihan->load([
                        'ms_penempatan_siswa.ms_siswa',
                        'ms_jenis_tagihan_siswa',
                    ]);

                    $namaSiswa = $tagihan->ms_penempatan_siswa->ms_siswa->nama_siswa;

                    $namaJenis = $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa;

                    $deskripsiJurnal = sprintf('Tagihan %s siswa %s', $namaJenis, $namaSiswa);

                    // Ambil jumlah yang sudah dibayarkan
                    $jumlahSudahDibayar = $tagihan->jumlah_sudah_dibayar();

                    // Cek validasi dan pembaruan jumlah tagihan
                    if ($this->jumlahTagihan !== null) {
                        if ($this->jumlahTagihan < $jumlahSudahDibayar) {
                            throw new \Exception('Jumlah tagihan tidak boleh kurang dari jumlah yang sudah dibayarkan (' . number_format($jumlahSudahDibayar) . ').');
                        }

                        // Update jumlah tagihan
                        $dataToUpdate['jumlah_tagihan_siswa'] = $this->jumlahTagihan;

                        // Tentukan status berdasarkan jumlah tagihan dan jumlah yang sudah dibayarkan
                        if ($jumlahSudahDibayar == 0) {
                            $dataToUpdate['status'] = 'Belum Dibayar';
                        } elseif ($this->jumlahTagihan > $jumlahSudahDibayar) {
                            $dataToUpdate['status'] = 'Masih Dicicil';
                        } else {
                            $dataToUpdate['status'] = 'Lunas';
                        }

                        // Update jurnal 
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
                                ]
                            ]
                        );
                    }

                    $dataToUpdate['deskripsi'] = sprintf(
                        'Nominal tagihan diubah dari Rp%s menjadi Rp%s oleh %s',
                        number_format($tagihan->getOriginal('jumlah_tagihan_siswa'), 0, ',', '.'),
                        number_format($this->jumlahTagihan, 0, ',', '.'),
                        $this->nama_petugas
                    );
                    // Lakukan pembaruan data tagihan
                    if (!empty($dataToUpdate)) {
                        $tagihan->update($dataToUpdate);
                    }
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
            ->get();

        // Simpan data tagihan dari halaman aktif
        $this->tagihanOnPage = $tagihans;
        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($t) => $t->total_bayar ?? 0);
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

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
