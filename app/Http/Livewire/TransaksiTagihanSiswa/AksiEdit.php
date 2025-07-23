<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AksiEdit extends Component
{
    public $tagihan;
    public $ms_tagihan_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $nama_siswa;

    public $nama_jenis_tagihan_siswa;
    public $jumlah_tagihan_siswa;

    public $jumlah_perubahan_tagihan = 0;

    public $nama_petugas;

    public function mount()
    {
        $this->nama_petugas = auth()->user()->nama;
    }

    protected $listeners = [
        'loadTagihanEdit' => 'loadTagihanEdit',
    ];

    public function loadTagihanEdit($ms_tagihan_siswa_id)
    {
        $keranjang = KeranjangTagihanSiswa::where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)->first();

        if ($keranjang) {
            // Jika sudah ada di keranjang, berikan notifikasi dan hentikan proses
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan sudah ada di keranjang.']);
            return;
        }

        // Ambil data tagihan
        $tagihan = TagihanSiswa::findOrFail($ms_tagihan_siswa_id);
        $this->ms_tagihan_siswa_id = $ms_tagihan_siswa_id;
        $this->tagihan = TagihanSiswa::where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)
            ->first();

        if (!$this->tagihan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tagihan tidak ditemukan.']);
            return;
        }

        $penempatanSiswa = $tagihan->ms_penempatan_siswa;
        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id ?? null;
        $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa ?? null;

        $this->nama_jenis_tagihan_siswa = $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa;
        $this->jumlah_tagihan_siswa = $tagihan->jumlah_tagihan_siswa;

        // Reset jumlah bayar saat tagihan di-load
        $this->jumlah_perubahan_tagihan = $this->tagihan->jumlah_tagihan_siswa;
    }

    public function aksiEdit()
    {
        DB::beginTransaction();

        try {
            // Validasi input jumlah tagihan
            $rules = ['jumlah_perubahan_tagihan' => 'numeric|min:0'];
            $messages = [
                'jumlah_perubahan_tagihan.numeric' => 'Jumlah tagihan harus berupa angka.',
                'jumlah_perubahan_tagihan.min' => 'Jumlah tagihan tidak boleh kurang dari 0.',
            ];
            $this->validate($rules, $messages);

            // Ambil data tagihan
            $tagihan = TagihanSiswa::find($this->tagihan->ms_tagihan_siswa_id);
            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan.');
            }

            // Ambil jumlah yang sudah dibayarkan
            $jumlahSudahDibayar = $tagihan->jumlah_sudah_dibayar();

            // Cek validasi jumlah tagihan
            if ($this->jumlah_perubahan_tagihan < $jumlahSudahDibayar) {
                throw new \Exception('Jumlah tagihan tidak boleh kurang dari jumlah yang sudah dibayarkan (' . number_format($jumlahSudahDibayar) . ').');
            }

            // Tentukan status berdasarkan jumlah tagihan dan jumlah yang sudah dibayarkan
            $dataToUpdate['jumlah_tagihan_siswa'] = $this->jumlah_perubahan_tagihan;
            if ($jumlahSudahDibayar == 0) {
                $dataToUpdate['status'] = 'Belum Dibayar';
            } elseif ($this->jumlah_perubahan_tagihan > $jumlahSudahDibayar) {
                $dataToUpdate['status'] = 'Masih Dicicil';
            } else {
                $dataToUpdate['status'] = 'Lunas';
            }

            // Update jurnal detail
            $debitJurnal = AkuntansiJurnalDetail::find($tagihan->akuntansi_jurnal_detail_debit_id);
            $kreditJurnal = AkuntansiJurnalDetail::find($tagihan->akuntansi_jurnal_detail_kredit_id);

            if ($debitJurnal && $kreditJurnal) {
                $debitJurnal->update([
                    'nominal' => $this->jumlah_perubahan_tagihan,
                ]);

                $kreditJurnal->update([
                    'nominal' => $this->jumlah_perubahan_tagihan,
                ]);
            }

            // Perbarui deskripsi
            $dataToUpdate['deskripsi'] = "Tagihan diubah oleh {$this->nama_petugas} menjadi nominal Rp" . number_format($this->jumlah_perubahan_tagihan);

            // Lakukan pembaruan data tagihan
            $tagihan->update($dataToUpdate);

            // Commit transaksi
            DB::commit();

            // Emit event untuk refresh data
            $this->emit('tagihanUpdated');
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAksiEdit']);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil diperbarui.']);

            // Reset jumlah perubahan tagihan
            $this->jumlah_perubahan_tagihan = 0;
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Berikan notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.aksi-edit', [
            'tagihan' => $this->tagihan,
        ]);
    }
}
