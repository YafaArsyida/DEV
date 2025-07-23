<?php

namespace App\Http\Livewire\SuratTagihanSiswa;

use App\Models\Jenjang;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Str; // Untuk membantu slugifikasi nama jenjang

use App\Models\SuratTagihan;

class Create extends Component
{
    use WithFileUploads;

    public $selectedJenjang;

    public $foto_kop;
    public $tanda_tangan;

    public $tempat_tanggal;
    public $nomor_surat;
    public $lampiran;
    public $hal;
    public $salam_pembuka;
    public $pembuka;
    public $isi;
    public $rincian;
    public $panduan;
    public $instruksi_1;
    public $instruksi_2;
    public $instruksi_3;
    public $instruksi_4;
    public $instruksi_5;
    public $penutup;
    public $salam_penutup;
    public $jabatan;
    public $nama_petugas;
    public $nomor_petugas;
    public $catatan_1;
    public $catatan_2;
    public $catatan_3;

    protected $listeners = ['createSuratTagihan'];

    public function createSuratTagihan($selectedJenjang)
    {
        $this->resetInputFields(); // Reset input field setiap kali modal dibuka
        $this->selectedJenjang = $selectedJenjang;
    }

    public function resetInputFields()
    {
        $this->foto_kop = null;
        $this->tanda_tangan = null;

        $this->tempat_tanggal = '';
        $this->nomor_surat = '';
        $this->lampiran = '';
        $this->hal = '';
        $this->salam_pembuka = '';
        $this->pembuka = '';
        $this->isi = '';
        $this->rincian = '';
        $this->panduan = '';
        $this->instruksi_1 = '';
        $this->instruksi_2 = '';
        $this->instruksi_3 = '';
        $this->instruksi_4 = '';
        $this->instruksi_5 = '';
        $this->penutup = '';
        $this->salam_penutup = '';
        $this->jabatan = '';
        $this->nama_petugas = '';
        $this->nomor_petugas = '';
        $this->catatan_1 = '';
        $this->catatan_2 = '';
        $this->catatan_3 = '';
    }

    public function rules()
    {
        return [
            'tempat_tanggal' => 'required|string|max:255',
            'nomor_surat' => 'required|string|max:255',
            'lampiran' => 'required|string|max:500',
            'hal' => 'required|string|max:500',
            'salam_pembuka' => 'required|string|max:255',
            'pembuka' => 'required|string|max:255',
            // 'isi' => 'required|string|max:255',
            // 'rincian' => 'required|string|max:255',
            'panduan' => 'required|string|max:255',
            // 'instruksi_1' => 'required|string|max:255',
            // 'instruksi_2' => 'required|string|max:255',
            // 'instruksi_3' => 'required|string|max:255',
            // 'instruksi_4' => 'required|string|max:255',
            // 'instruksi_5' => 'required|string|max:255',
            'penutup' => 'required|string|max:255',
            'salam_penutup' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nama_petugas' => 'required|string|max:255',
            'nomor_petugas' => 'required|string|max:255',
            // 'catatan_1' => 'required|string|max:255',
            // 'catatan_2' => 'required|string|max:255',
            // 'catatan_3' => 'required|string|max:255',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updatedFotoKop()
    {
        // Kirim event ke browser untuk memberi feedback kepada pengguna
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Cek Preview Foto']);
    }
    public function updatedTandaTangan()
    {
        // Kirim event ke browser untuk memberi feedback kepada pengguna
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Cek Preview Tanda Tangan']);
    }

    public function createSurat()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Cek apakah surat sudah ada untuk jenjang yang dipilih
            $existingSurat = SuratTagihan::where('ms_jenjang_id', $this->selectedJenjang)->first();

            if ($existingSurat) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Surat sudah ada']);
            }

            $fotoKopPath = null;
            $tandaTanganPath = null;

            // Simpan file foto kop jika diunggah
            if ($this->foto_kop) {
                $namaJenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');
                $fotoKopPath = $this->foto_kop->storeAs(
                    'surat_tagihan/foto_kop',
                    Str::slug($namaJenjang . '-' . now()) . '.' . $this->foto_kop->getClientOriginalExtension(),
                    'public'
                );
            }

            // Simpan file tanda tangan jika diunggah
            if ($this->tanda_tangan) {
                $namaJenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');
                $tandaTanganPath = $this->tanda_tangan->storeAs(
                    'surat_tagihan/tanda_tangan',
                    Str::slug($namaJenjang . '-tanda-tangan-' . now()) . '.' . $this->tanda_tangan->getClientOriginalExtension(),
                    'public'
                );
            }

            // Buat surat baru
            SuratTagihan::create([
                'ms_jenjang_id' => $this->selectedJenjang,
                'tempat_tanggal' => $this->tempat_tanggal,
                'nomor_surat' => $this->nomor_surat,
                'lampiran' => $this->lampiran,
                'hal' => $this->hal,
                'salam_pembuka' => $this->salam_pembuka,
                'pembuka' => $this->pembuka,
                'isi' => $this->isi,
                'rincian' => $this->rincian,
                'panduan' => $this->panduan,
                'instruksi_1' => $this->instruksi_1,
                'instruksi_2' => $this->instruksi_2,
                'instruksi_3' => $this->instruksi_3,
                'instruksi_4' => $this->instruksi_4,
                'instruksi_5' => $this->instruksi_5,
                'penutup' => $this->penutup,
                'salam_penutup' => $this->salam_penutup,
                'jabatan' => $this->jabatan,
                'nama_petugas' => $this->nama_petugas,
                'nomor_petugas' => $this->nomor_petugas,
                'catatan_1' => $this->catatan_1,
                'catatan_2' => $this->catatan_2,
                'catatan_3' => $this->catatan_3,
                'foto_kop' => $fotoKopPath,
                'tanda_tangan' => $tandaTanganPath,
            ]);

            DB::commit();

            // Kirim notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Surat berhasil dibuat']);
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'createSuratTagihan']);
            $this->emit('refreshSurat', $this->selectedJenjang);
        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.surat-tagihan-siswa.create');
    }
}
