<?php

namespace App\Http\Livewire\Keuangan\KuitansiTransaksiEduPay;

use App\Models\Jenjang;
use App\Models\KuitansiTransaksiEduPay;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Untuk membantu slugifikasi nama jenjang


class Edit extends Component
{
    use WithFileUploads;

    public $selectedJenjang;

    public $logo_baru;

    public $ms_kuitansi_transaksi_edupay_id;
    public $logo;
    public $nama_institusi;
    public $alamat;
    public $kontak;
    public $judul;
    public $pesan;
    public $tempat;

    protected $listeners = ['loadKuitansiEduPay'];

    public function loadKuitansiEduPay($ms_kuitansi_transaksi_edupay_id, $selectedJenjang)
    {
        $this->selectedJenjang = $selectedJenjang;

        $surat = KuitansiTransaksiEduPay::findOrFail($ms_kuitansi_transaksi_edupay_id);

        $this->ms_kuitansi_transaksi_edupay_id = $surat->ms_kuitansi_transaksi_edupay_id;
        $this->logo = $surat->logo;
        $this->logo_baru = null;
        $this->nama_institusi = $surat->nama_institusi;
        $this->alamat = $surat->alamat;
        $this->kontak = $surat->kontak;
        $this->judul = $surat->judul;
        $this->pesan = $surat->pesan;
        $this->tempat = $surat->tempat;
    }

    public function rules()
    {
        return [
            'nama_institusi' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updatedLogoBaru()
    {
        // Kirim event ke browser untuk memberi feedback kepada pengguna
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Cek Preview Logo']);
    }

    public function updateKuitansi()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $surat = KuitansiTransaksiEduPay::findOrFail($this->ms_kuitansi_transaksi_edupay_id);

            if ($this->logo_baru) {
                // Hapus file lama jika ada
                if ($surat->logo && Storage::disk('public')->exists($surat->logo)) {
                    Storage::disk('public')->delete($surat->logo);
                }

                // Simpan foto baru dengan slug ke folder baru (kuitansi_pembayaran)
                $nama_jenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');

                $filePath = $this->logo_baru->storeAs(
                    'kuitansi_edupay/logo',
                    Str::slug($nama_jenjang . '-' . now()) . '.' . $this->logo_baru->getClientOriginalExtension(),
                    'public'
                );

                // Perbarui model
                $surat->update(['logo' => $filePath]);
            }

            // Perbarui data surat
            $surat->update([
                'ms_jenjang_id' => $this->selectedJenjang,
                'nama_institusi' => $this->nama_institusi,
                'alamat' => $this->alamat,
                'kontak' => $this->kontak,
                'judul' => $this->judul,
                'pesan' => $this->pesan,
                'tempat' => $this->tempat,
            ]);

            DB::commit();

            // Kirim notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kuitansi berhasil diperbarui.']);
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'editKuitansiEduPay']);
            $this->emit('kuitansiEduPay', $this->selectedJenjang);
        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.keuangan.kuitansi-transaksi-edu-pay.edit');
    }
}
