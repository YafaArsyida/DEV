<?php

namespace App\Http\Livewire\TagihanJenis;

use App\Models\AktifitasPengguna;
use App\Models\AkuntansiJurnalDetail;
use App\Models\DetailTransaksi;
use App\Models\DetailTransaksiTagihanSiswa;
use App\Models\JenisTagihan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use App\Models\Kelas;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\Tagihan;
use App\Models\TagihanSiswa;

class Manage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

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
        'showTagihan'
    ];

    public function showTagihan($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->ms_jenis_tagihan_siswa_id = $params['ms_jenis_tagihan_siswa_id'];
        $this->resetPage();
        $this->TagihanSelectAll = false;
        $this->TagihanSelected = [];
    }

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    public function updatingSearch()
    {
        $this->emitSelf('$refresh');
    }

    public function updatingSelectedKelas()
    {
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
                        $this->dispatchBrowserEvent('alertify-error', ['message' => "Tidak dapat dihapus, terdapat pembayaran"]);
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

                    // Jika belum pernah dibayar, lanjutkan proses penghapusan
                    $tagihan->ms_pengguna_id = auth()->user()->ms_pengguna_id; // Set pengguna yang menghapus
                    $tagihan->deskripsi = "tagihan dihapus petugas {$this->nama_petugas}"; // Set deskripsi
                    $tagihan->save();

                    // Soft delete tagihan
                    $tagihan->delete();
                    $anyTagihanDeleted = true;
                }
            }

            // Commit transaksi jika berhasil
            DB::commit();
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

                    // Cek validasi jumlah tagihan
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

                    $dataToUpdate['deskripsi'] = "Tagihan diubah oleh petugas {$this->nama_petugas} menjadi RP " . number_format($this->jumlahTagihan);

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
        // Query untuk memilih kelas berdasarkan jenjang dan tahun ajar
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }


        // Query untuk mendapatkan tagihan berdasarkan jenis tagihan dengan JOIN
        $query = TagihanSiswa::select('ms_tagihan_siswa.*', 'ms_siswa.nama_siswa', 'ms_kelas.nama_kelas', 'ms_jenis_tagihan_siswa.nama_jenis_tagihan_siswa', 'ms_kategori_tagihan_siswa.nama_kategori_tagihan_siswa')
            ->join('ms_penempatan_siswa', 'ms_tagihan_siswa.ms_penempatan_siswa_id', '=', 'ms_penempatan_siswa.ms_penempatan_siswa_id')
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->join('ms_kelas', 'ms_penempatan_siswa.ms_kelas_id', '=', 'ms_kelas.ms_kelas_id')
            ->join('ms_jenis_tagihan_siswa', 'ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', '=', 'ms_jenis_tagihan_siswa.ms_jenis_tagihan_siswa_id')
            ->join('ms_kategori_tagihan_siswa', 'ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa_id', '=', 'ms_kategori_tagihan_siswa.ms_kategori_tagihan_siswa_id')
            ->where('ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', $this->ms_jenis_tagihan_siswa_id);

        // Filter kelas jika dipilih
        if ($this->selectedKelas) {
            $query->where('ms_kelas.ms_kelas_id', $this->selectedKelas);
        }

        // Filter pencarian siswa
        if ($this->search) {
            $query->where('ms_siswa.nama_siswa', 'like', '%' . $this->search . '%');
        }

        // Mengambil tagihan yang sudah difilter
        $tagihans = $query->orderBy('ms_kelas.ms_kelas_id', 'ASC')
            ->orderBy('ms_siswa.nama_siswa', 'ASC')
            ->get();

        // Simpan data tagihan dari halaman aktif
        $this->tagihanOnPage = $tagihans;
        // Hitung total estimasi, dibayarkan, dan kekurangan
        $totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');
        $totalDibayarkan = $tagihans->sum(function ($item) {
            return $item->jumlah_sudah_dibayar();
        });
        $totalKekurangan = $totalEstimasi - $totalDibayarkan;


        return view('livewire.tagihan-jenis.manage', [
            'select_kelas' => $select_kelas,
            'tagihans' => $tagihans,
            'totalEstimasi' => $totalEstimasi,
            'totalDibayarkan' => $totalDibayarkan,
            'totalKekurangan' => $totalKekurangan,
        ]);
    }
}
