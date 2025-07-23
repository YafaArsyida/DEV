<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
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
        'showTagihan'
    ];

    public function updatingSearch()
    {
        $this->emitSelf('$refresh');
    }

    public function updatingselectedKategori()
    {
        $this->emitSelf('$refresh');
    }

    public function showTagihan($params)
    {
        $this->ms_jenjang_id = $params['jenjang'];
        $this->ms_tahun_ajar_id = $params['tahunAjar'];
        $this->ms_penempatan_siswa_id = $params['ms_penempatan_siswa_id'];

        $penempatan = PenempatanSiswa::with('ms_siswa')->find($this->ms_penempatan_siswa_id);
        $this->nama_siswa = $penempatan->ms_siswa->nama_siswa ?? 'Tidak Diketahui';

        $this->TagihanSelectAll = false;
        $this->TagihanSelected = [];
        $this->emitSelf('$refresh');
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

                    // Dapatkan ID jurnal terkait
                    $jurnalIds = [
                        $tagihan->akuntansi_jurnal_detail_debit_id,
                        $tagihan->akuntansi_jurnal_detail_kredit_id,
                    ];

                    // Validasi dan soft delete jurnal
                    AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', $jurnalIds)->get()->each(function ($jurnal) {
                        $jurnal->delete();
                    });

                    $tagihan->ms_pengguna_id = auth()->user()->ms_pengguna_id; // Set pengguna yang menghapus
                    $tagihan->deskripsi = "Tagihan dihapus oleh petugas {$this->nama_petugas}"; // Set deskripsi
                    $tagihan->save();

                    // Soft delete tagihan
                    $tagihan->delete();
                    $anyTagihanDeleted = true;
                }
            }
            DB::commit();

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
        $this->emit('refreshTagihans');
    }

    // EDIT TAGIHAN
    public function editTagihan()
    {
        DB::beginTransaction();

        try {
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

                if ($tagihan) {
                    $dataToUpdate = [];
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

                        // Update jurnal detail
                        $debitJurnal = AkuntansiJurnalDetail::find($tagihan->akuntansi_jurnal_detail_debit_id);
                        $kreditJurnal = AkuntansiJurnalDetail::find($tagihan->akuntansi_jurnal_detail_kredit_id);

                        if ($debitJurnal && $kreditJurnal) {
                            $debitJurnal->update([
                                'nominal' => $this->jumlahTagihan,
                            ]);

                            $kreditJurnal->update([
                                'nominal' => $this->jumlahTagihan,
                            ]);
                        }
                    }

                    // Perbarui deskripsi
                    $dataToUpdate['deskripsi'] = "Tagihan diubah oleh {$this->nama_petugas} menjadi nominal " . number_format($this->jumlahTagihan);

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
            $this->emit('refreshTagihans');

            // Berikan notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil diperbarui.']);
        } catch (\Exception $e) {
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
        $query = TagihanSiswa::select('ms_tagihan_siswa.*', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->join('ms_jenis_tagihan_siswa', 'ms_jenis_tagihan_siswa.ms_jenis_tagihan_siswa_id', '=', 'ms_tagihan_siswa.ms_jenis_tagihan_siswa_id')
            ->join('ms_kategori_tagihan_siswa', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', '=', 'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->whereHas('ms_penempatan_siswa', function ($q) {
                $q->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id);
            });

        // Filter berdasarkan kategori tagihan jika dipilih
        if ($this->selectedKategori) {
            $query->where('ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', $this->selectedKategori);
        }

        // Filter berdasarkan pencarian nama jenis tagihan
        if ($this->search) {
            $query->where('ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa', 'like', '%' . $this->search . '%');
        }

        // Paginasi dan urutan berdasarkan kategori tagihan
        $tagihans = $query
            ->orderBy('ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id', 'ASC')
            ->orderBy('ms_jenis_tagihan_siswa_id', 'ASC')->get();

        // Simpan data tagihan dari halaman aktif
        $this->tagihanOnPage = $tagihans;
        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(fn($item) => $item->jumlah_sudah_dibayar());
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;

        return view('livewire.tagihan-siswa.manage', [
            'select_kategori' => $select_kategori,
            'tagihans' => $tagihans,
            'totalEstimasi' => $totalEstimasi,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
        ]);
    }
}
