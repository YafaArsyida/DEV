<?php

namespace App\Http\Livewire\SuratTagihanSiswa;

use App\Models\Jenjang;
use App\Models\SuratTagihan;
use App\Models\SuratTagihanSiswa;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str; // Untuk membantu slugifikasi nama jenjang

class Edit extends Component
{
    use WithFileUploads;

    public $selectedJenjang;

    public $foto_kop_baru;
    public $tanda_tangan_baru;

    public $ms_surat_tagihan_siswa_id;
    public $foto_kop;
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
    public $tanda_tangan;
    public $nama_petugas;
    public $nomor_petugas;
    public $catatan_1;
    public $catatan_2;
    public $catatan_3;

    protected $listeners = ['loadSuratTagihan'];

    public function loadSuratTagihan($ms_surat_tagihan_siswa_id, $selectedJenjang)
    {
        $this->selectedJenjang = $selectedJenjang;

        $surat = SuratTagihanSiswa::findOrFail($ms_surat_tagihan_siswa_id);

        $this->ms_surat_tagihan_siswa_id = $surat->ms_surat_tagihan_siswa_id;
        $this->foto_kop = $surat->foto_kop;
        $this->foto_kop_baru = null;
        $this->tempat_tanggal = $surat->tempat_tanggal;
        $this->nomor_surat = $surat->nomor_surat;
        $this->lampiran = $surat->lampiran;
        $this->hal = $surat->hal;
        $this->salam_pembuka = $surat->salam_pembuka;
        $this->pembuka = $surat->pembuka;
        $this->isi = $surat->isi;
        $this->rincian = $surat->rincian;
        $this->panduan = $surat->panduan;
        $this->instruksi_1 = $surat->instruksi_1;
        $this->instruksi_2 = $surat->instruksi_2;
        $this->instruksi_3 = $surat->instruksi_3;
        $this->instruksi_4 = $surat->instruksi_4;
        $this->instruksi_5 = $surat->instruksi_5;
        $this->penutup = $surat->penutup;
        $this->salam_penutup = $surat->salam_penutup;
        $this->jabatan = $surat->jabatan;
        $this->nama_petugas = $surat->nama_petugas;
        $this->nomor_petugas = $surat->nomor_petugas;
        $this->tanda_tangan = $surat->tanda_tangan;
        $this->tanda_tangan_baru = null;
        $this->catatan_1 = $surat->catatan_1;
        $this->catatan_2 = $surat->catatan_2;
        $this->catatan_3 = $surat->catatan_3;
    }

    public function rules()
    {
        return [
            // 'foto_kop' => 'nullable|image|max:2048',
            // 'tanda_tangan' => 'nullable|image|max:2048',

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

    public function updatedFotoKopBaru()
    {
        // Kirim event ke browser untuk memberi feedback kepada pengguna
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Cek Preview Foto']);
    }
    public function updatedTandaTanganBaru()
    {
        // Kirim event ke browser untuk memberi feedback kepada pengguna
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Cek Preview Tanda Tangan']);
    }

    public function updateSurat()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $surat = SuratTagihanSiswa::findOrFail($this->ms_surat_tagihan_siswa_id);

            if ($this->foto_kop_baru) {
                // Hapus file lama jika ada
                if ($surat->foto_kop && Storage::disk('public')->exists($surat->foto_kop)) {
                    Storage::disk('public')->delete($surat->foto_kop);
                }

                // Simpan foto baru dengan slug ke folder baru (surat_tagihan)
                $nama_jenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');

                $filePath = $this->foto_kop_baru->storeAs(
                    'surat_tagihan/foto_kop',
                    Str::slug($nama_jenjang . '-' . now()) . '.' . $this->foto_kop_baru->getClientOriginalExtension(),
                    'public'
                );

                // Perbarui model
                $surat->update(['foto_kop' => $filePath]);
            }
            // if ($this->foto_kop_baru) {
            //     // Hapus file lama jika ada
            //     if ($surat->foto_kop && file_exists(public_path($surat->foto_kop))) {
            //         unlink(public_path($surat->foto_kop));
            //     }

            //     // Simpan foto baru dengan slug ke folder baru (surat_tagihan)
            //     $nama_jenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');
            //     $fileName = Str::slug($nama_jenjang . '-' . now()) . '.' . $this->foto_kop_baru->getClientOriginalExtension();
            //     $filePath = 'surat_tagihan/foto_kop/' . $fileName;

            //     // Simpan file ke public
            //     $this->foto_kop_baru->move(public_path('surat_tagihan/foto_kop'), $fileName);

            //     // Perbarui model
            //     $surat->update(['foto_kop' => $filePath]);
            // }

            if ($this->tanda_tangan_baru) {
                // Hapus file lama jika ada
                if ($this->tanda_tangan && Storage::disk('public')->exists($this->tanda_tangan)) {
                    Storage::disk('public')->delete($surat->tanda_tangan);
                }

                $nama_jenjang = Jenjang::where('ms_jenjang_id', $this->selectedJenjang)->value('nama_jenjang');
                // Simpan file baru
                $filePath = $this->tanda_tangan_baru->storeAs(
                    'surat_tagihan/tanda_tangan',
                    Str::slug($nama_jenjang . '-tanda-tangan-' . now()) . '.' . $this->tanda_tangan_baru->getClientOriginalExtension(),
                    'public'
                );

                $surat->update(['tanda_tangan' => $filePath]);
            }

            // Perbarui data surat
            $surat->update([
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
            ]);

            DB::commit();

            // Kirim notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Surat berhasil diperbarui.']);
            $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'editSuratTagihan']);
            $this->emit('refreshSurat', $this->selectedJenjang);
        } catch (\Exception $e) {
            DB::rollBack();

            // Kirim notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.surat-tagihan-siswa.edit');
    }
}
