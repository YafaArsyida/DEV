<?php

namespace App\Http\Livewire\KuitansiPembayaran;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\Jenjang;
use App\Models\KuitansiPembayaran;
use Livewire\WithFileUploads;
use Illuminate\Support\Str; // Untuk membantu slugifikasi nama jenjang

class Create extends Component
{
    use WithFileUploads;

    public $selectedJenjang;

    public $logo;
    public $nama_institusi;
    public $alamat;
    public $kontak;
    public $judul;
    public $pesan;
    public $tempat;

    protected $listeners = ['createKuitansiTransaksi'];

    public function createKuitansiTransaksi($selectedJenjang)
    {
        $this->resetInputFields(); // Reset input field setiap kali modal dibuka
        $this->selectedJenjang = $selectedJenjang;
    }

    public function resetInputFields()
    {
        $this->logo = null;

        $this->nama_institusi = '';
        $this->alamat = '';
        $this->kontak = '';
        $this->judul = '';
        $this->pesan = '';
        $this->tempat = '';
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

    public function updatedLogo()
    {
        // Kirim event ke browser untuk memberi feedback kepada pengguna
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Cek Preview Logo']);
    }

    public function createKuitansi()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Cek apakah surat sudah ada untuk jenjang yang dipilih
            $exist = KuitansiPembayaran::where('ms_jenjang_id', $this->selectedJenjang)->first();

            if ($exist) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Kuitansi sudah ada']);
            }

            $logoPath = null;

            // Simpan file foto kop jika diunggah
            if ($this->logo) {
                $namaJenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');
                $logoPath = $this->logo->storeAs(
                    'kuitansi_pembayaran/logo',
                    Str::slug($namaJenjang . '-' . now()) . '.' . $this->logo->getClientOriginalExtension(),
                    'public'
                );
            }

            // Buat surat baru
            KuitansiPembayaran::create([
                'ms_jenjang_id' => $this->selectedJenjang,
                'logo' => $logoPath,
                'nama_institusi' => $this->nama_institusi,
                'alamat' => $this->alamat,
                'kontak' => $this->kontak,
                'judul' => $this->judul,
                'pesan' => $this->pesan,
                'tempat' => $this->tempat,
            ]);

            DB::commit();

            // Kirim notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kuitansi berhasil dibuat']);
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'createKuitansiTransaksi']);
            $this->emit('refreshKuitansi', $this->selectedJenjang);
        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.kuitansi-pembayaran.create');
    }
}
