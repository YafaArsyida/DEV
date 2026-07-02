<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
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

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data dimuat'
        ]);

        $penempatanSiswa = $tagihan->ms_penempatan_siswa;
        $this->ms_jenjang_id = $penempatanSiswa->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $penempatanSiswa->ms_tahun_ajar_id ?? null;
        $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa ?? null;

        $this->nama_jenis_tagihan_siswa = $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa;
        $this->jumlah_tagihan_siswa = $tagihan->jumlah_tagihan_siswa;

        // Reset jumlah bayar saat tagihan di-load
        $this->jumlah_perubahan_tagihan = $this->tagihan->jumlah_tagihan_siswa;

        $this->dispatchBrowserEvent('tagihan-edit-loaded');
    }

    public function aksiEdit()
    {
        DB::beginTransaction();

        try {
            $this->jumlah_perubahan_tagihan = $this->normalizeDecimalAmount($this->jumlah_perubahan_tagihan);

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
                throw ValidationException::withMessages([
                    'jumlah_perubahan_tagihan' => 'Jumlah tagihan tidak boleh kurang dari yang sudah dibayarkan (' . number_format($jumlahSudahDibayar) . ').'
                ]);
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
            $this->emit('refreshTagihanSiswa');
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAksiEdit']);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tagihan berhasil diperbarui.']);

            // Reset jumlah perubahan tagihan
            $this->jumlah_perubahan_tagihan = 0;
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal validasi, cek input!'
            ]);

            throw $e; // 🔥 penting untuk tampilkan error di blade

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tagihan-siswa.edit', [
            'tagihan' => $this->tagihan,
        ]);
    }

    private function normalizeDecimalAmount($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return 0;
        }

        $clean = preg_replace('/[^\d\.,\-]/', '', $value);

        if (strpos($clean, ',') !== false) {
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        } elseif (substr_count($clean, '.') > 1) {
            $clean = str_replace('.', '', $clean);
        }

        return $clean;
    }
}
