<?php

namespace App\Http\Livewire\KuitansiPembayaran;

use App\Models\Jenjang;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Untuk membantu slugifikasi nama jenjang

use App\Models\KuitansiPembayaran;

class Edit extends Component
{
    use WithFileUploads;

    public $selectedJenjang;

    public $logo_baru;

    public $ms_kuitansi_pembayaran_id;
    public $logo;
    public $nama_institusi;
    public $alamat;
    public $kontak;
    public $judul;
    public $pesan;
    public $tempat;

    protected $listeners = ['loadKuitansiTransaksi'];

    public function loadKuitansiTransaksi($ms_kuitansi_pembayaran_id, $selectedJenjang)
    {
        $this->selectedJenjang = $selectedJenjang;

        $surat = KuitansiPembayaran::findOrFail($ms_kuitansi_pembayaran_id);

        $this->ms_kuitansi_pembayaran_id = $surat->ms_kuitansi_pembayaran_id;
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
            $surat = KuitansiPembayaran::findOrFail($this->ms_kuitansi_pembayaran_id);

            if ($this->logo_baru) {
                // Hapus file lama jika ada
                if ($surat->logo && Storage::disk('public')->exists($surat->logo)) {
                    Storage::disk('public')->delete($surat->logo);
                }

                // Simpan foto baru dengan slug ke folder baru (kuitansi_pembayaran)
                $nama_jenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');

                $filePath = $this->logo_baru->storeAs(
                    'kuitansi_pembayaran/logo',
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
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'editKuitansiTransaksi']);
            $this->emit('refreshKuitansi', $this->selectedJenjang);
        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.kuitansi-pembayaran.edit');
    }
}
