<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\KeranjangTagihanSiswa;
use App\Models\TagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Cicilan extends Component
{
    public $tagihan;
    public $jumlah_bayar = 0;

    protected $listeners = [
        'loadCicilan' => 'loadCicilan',
    ];

    public function loadCicilan($ms_tagihan_siswa_id)
    {
        $this->tagihan = TagihanSiswa::withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar')
            ->where('ms_tagihan_siswa_id', $ms_tagihan_siswa_id)
            ->first();

        $this->jumlah_bayar = 0;

        if (!$this->tagihan) {
            throw new \Exception('Tagihan tidak ditemukan!');
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Tagihan dimuat'
        ]);
    }

    public function masukKeranjang($ms_tagihan_siswa_id)
    {
        DB::beginTransaction();

        try {
            $this->jumlah_bayar = $this->normalizeAmount($this->jumlah_bayar);
            // ✅ Validasi basic dulu
            $this->validate([
                'jumlah_bayar' => 'required|numeric|min:1',
            ], [
                'jumlah_bayar.required' => 'Jumlah bayar wajib diisi.',
                'jumlah_bayar.numeric' => 'Jumlah bayar harus berupa angka.',
                'jumlah_bayar.min' => 'Jumlah bayar harus lebih dari 0.',
            ]);

            // 🔒 Ambil ulang + lock
            $tagihan = TagihanSiswa::lockForUpdate()
                ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar')
                ->find($ms_tagihan_siswa_id);

            if (!$tagihan) {
                throw ValidationException::withMessages([
                    'jumlah_bayar' => 'Tagihan tidak ditemukan.'
                ]);
            }

            $jumlah_bayar_sebelumnya = $tagihan->total_bayar ?? 0;
            $sisa = $tagihan->jumlah_tagihan_siswa - $jumlah_bayar_sebelumnya;

            // ✅ Validasi bisnis
            if ($this->jumlah_bayar > $sisa) {
                throw ValidationException::withMessages([
                    'jumlah_bayar' => 'Jumlah bayar melebihi sisa tagihan (Rp' . number_format($sisa, 0, ',', '.') . ').'
                ]);
            }

            // 🔥 Insert keranjang (anti duplicate)
            KeranjangTagihanSiswa::updateOrCreate(
                [
                    'ms_penempatan_siswa_id' => $tagihan->ms_penempatan_siswa_id,
                    'ms_tagihan_siswa_id' => $ms_tagihan_siswa_id,
                ],
                [
                    'ms_pengguna_id' => Auth::id(),
                    'jumlah_bayar' => $this->jumlah_bayar,
                    'tanggal_dibayar' => now(),
                    'status' => 'Masih Dicicil',
                    'deskripsi' => 'Tagihan #' . $ms_tagihan_siswa_id . ' dibayar sebagian.',
                ]
            );

            $sisa_tagihan = $tagihan->jumlah_tagihan_siswa - ($jumlah_bayar_sebelumnya + $this->jumlah_bayar);

            $tagihan->update([
                'status' => $sisa_tagihan > 0 ? 'Masih Dicicil' : 'Masuk Keranjang',
                'deskripsi' => $sisa_tagihan > 0
                    ? 'Sebagian tagihan dibayar, sisa: Rp' . number_format($sisa_tagihan, 0, ',', '.')
                    : 'Tagihan telah Masuk Keranjang.',
            ]);

            DB::commit();

            $this->reset(['tagihan', 'jumlah_bayar']);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil masuk keranjang.'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAksiBayar'
            ]);

            $this->emit('keranjangUpdated');
            $this->emit('reloadTagihanSiswa');
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal validasi, cek input!'
            ]);

            throw $e; // 🔥 WAJIB biar tampil di blade

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.cicilan', [
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
