<?php

namespace App\Http\Livewire\TagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use App\Services\AccountingService;
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
        // $this->jumlah_perubahan_tagihan = $this->tagihan->jumlah_tagihan_siswa;

        $this->dispatchBrowserEvent('tagihan-edit-loaded');
    }

    public function aksiEdit()
    {
        DB::beginTransaction();

        try {
            // =====================================================
            // 1. NORMALISASI & VALIDASI INPUT
            // =====================================================
            $this->jumlah_perubahan_tagihan =
                $this->normalizeAmount($this->jumlah_perubahan_tagihan);

            $rules = [
                'jumlah_perubahan_tagihan' => 'numeric|min:0',
            ];

            $messages = [
                'jumlah_perubahan_tagihan.numeric' =>
                    'Jumlah tagihan harus berupa angka.',

                'jumlah_perubahan_tagihan.min' =>
                    'Jumlah tagihan tidak boleh kurang dari 0.',
            ];

            $this->validate($rules, $messages);

            // =====================================================
            // 2. LOCK TAGIHAN
            // =====================================================
            $tagihan = TagihanSiswa::with([
                'ms_penempatan_siswa.ms_siswa',
                'ms_jenis_tagihan_siswa',
            ])
                ->lockForUpdate()
                ->find($this->tagihan->ms_tagihan_siswa_id);

            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan.');
            }

            // =====================================================
            // 3. VALIDASI STATUS TAGIHAN
            // =====================================================
            // if ($tagihan->status_transaksi === 'dibatalkan') {
            //     throw new \Exception(
            //         'Tagihan yang sudah dibatalkan tidak dapat diedit.'
            //     );
            // }

            // =====================================================
            // 4. VALIDASI JURNAL
            // =====================================================
            if (!$tagihan->akuntansi_jurnal_id) {
                throw new \Exception(
                    'Jurnal tagihan tidak ditemukan.'
                );
            }

            // =====================================================
            // 5. DATA TAGIHAN
            // =====================================================
            $namaSiswa =
                $tagihan->ms_penempatan_siswa
                    ?->ms_siswa
                    ?->nama_siswa
                    ?? '-';

            $namaJenis =
                $tagihan->ms_jenis_tagihan_siswa
                    ?->nama_jenis_tagihan_siswa
                    ?? 'Tagihan';

            $deskripsiJurnal = sprintf(
                'Tagihan %s - %s',
                $namaJenis,
                $namaSiswa
            );

            // =====================================================
            // 6. JUMLAH YANG SUDAH DIBAYAR
            // =====================================================
            $jumlahSudahDibayar = $tagihan->jumlah_sudah_dibayar();

            // =====================================================
            // 7. VALIDASI NOMINAL
            // =====================================================
            if ($this->jumlah_perubahan_tagihan < $jumlahSudahDibayar) {
                throw ValidationException::withMessages([
                    'jumlah_perubahan_tagihan' =>
                        'Jumlah tagihan tidak boleh kurang dari '
                        . 'yang sudah dibayarkan (Rp'
                        . number_format(
                            $jumlahSudahDibayar,
                            0,
                            ',',
                            '.'
                        )
                        . ').'
                ]);
            }

            // =====================================================
            // 8. TENTUKAN STATUS TAGIHAN
            // =====================================================
            $dataToUpdate = [
                'jumlah_tagihan_siswa' =>
                    $this->jumlah_perubahan_tagihan,
            ];

            if ($jumlahSudahDibayar == 0) {

                $dataToUpdate['status'] = 'Belum Dibayar';

            } elseif (
                $this->jumlah_perubahan_tagihan > $jumlahSudahDibayar
            ) {

                $dataToUpdate['status'] = 'Masih Dicicil';

            } else {

                $dataToUpdate['status'] = 'Lunas';
            }

            // =====================================================
            // 9. UPDATE JURNAL TAGIHAN
            // =====================================================
            AccountingService::update(
                $tagihan->akuntansi_jurnal_id,
                [
                    'tanggal' => now(),

                    'deskripsi' => $deskripsiJurnal,

                    'detail' => [
                        [
                            'kode_rekening' => 12001,
                            'posisi' => 'debit',
                            'nominal' => $this->jumlah_perubahan_tagihan,
                        ],
                        [
                            'kode_rekening' => 41001,
                            'posisi' => 'kredit',
                            'nominal' => $this->jumlah_perubahan_tagihan,
                        ],
                    ],
                ]
            );

            // =====================================================
            // 10. DESKRIPSI PERUBAHAN
            // =====================================================
            $nominalLama =
                $tagihan->getOriginal('jumlah_tagihan_siswa');

            $dataToUpdate['deskripsi'] = sprintf(
                'Nominal tagihan diubah dari Rp%s menjadi Rp%s oleh %s',
                number_format($nominalLama, 0, ',', '.'),
                number_format(
                    $this->jumlah_perubahan_tagihan,
                    0,
                    ',',
                    '.'
                ),
                $this->nama_petugas
            );

            // =====================================================
            // 11. UPDATE TAGIHAN
            // =====================================================
            $tagihan->update($dataToUpdate);

            // =====================================================
            // 12. COMMIT
            // =====================================================
            DB::commit();

            // =====================================================
            // 13. REFRESH DATA
            // =====================================================
            $this->emit('refreshTagihanSiswa');
            $this->emit('reloadTagihanSiswa');

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAksiEdit'
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Tagihan berhasil diperbarui.'
            ]);

            // =====================================================
            // 14. RESET
            // =====================================================
            $this->jumlah_perubahan_tagihan = 0;

        } catch (ValidationException $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal validasi, cek input!'
            ]);

            // Penting agar error validasi tetap tampil di Blade
            throw $e;

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' =>
                    $e->getMessage()
                    ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tagihan-siswa.edit', [
            'tagihan' => $this->tagihan,
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
