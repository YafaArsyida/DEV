<?php

namespace App\Http\Livewire\Keuangan\JenisTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Edit extends Component
{
    // Relasi
    public $ms_tahun_ajar_id;
    public $ms_jenjang_id;
    public $ms_kategori_tagihan_siswa_id;
    public $ms_jenis_tagihan_siswa_id;

    // Form input
    public $nama_jenis_tagihan_siswa;
    public $tanggal_jatuh_tempo;
    public $deskripsi;

    protected $listeners = [
        'loadDataJenisTagihan',
    ];

    public function loadDataJenisTagihan($ms_jenis_tagihan_siswa_id)
    {
        $this->resetValidation();

        $jenis = JenisTagihanSiswa::findOrFail($ms_jenis_tagihan_siswa_id);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data dimuat'
        ]);

        $this->ms_tahun_ajar_id = $jenis->ms_tahun_ajar_id; // Update ms_tahun_ajar_id
        $this->ms_jenjang_id = $jenis->ms_jenjang_id; // Update ms_jenjang_id

        $this->ms_jenis_tagihan_siswa_id = $jenis->ms_jenis_tagihan_siswa_id;
        $this->ms_kategori_tagihan_siswa_id = $jenis->ms_kategori_tagihan_siswa_id;
        $this->nama_jenis_tagihan_siswa = $jenis->nama_jenis_tagihan_siswa;
        $this->tanggal_jatuh_tempo = $jenis->tanggal_jatuh_tempo;
        $this->deskripsi = $jenis->deskripsi;
    }

    protected function rules()
    {
        return [
            'nama_jenis_tagihan_siswa' => 'required|string|max:255',
            'tanggal_jatuh_tempo' => 'required',
            'ms_kategori_tagihan_siswa_id' => 'required|exists:ms_kategori_tagihan_siswa,ms_kategori_tagihan_siswa_id',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_jenis_tagihan_siswa.required' => 'Nama tagihan tidak boleh kosong',
        'ms_kategori_tagihan_siswa_id.required' => 'Pilih kategori tagihan',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateJenis()
    {
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();

            $jenisTagihan = JenisTagihanSiswa::findOrFail($this->ms_jenis_tagihan_siswa_id);

            $jenisTagihan->update([
                'ms_kategori_tagihan_siswa_id' => $validatedData['ms_kategori_tagihan_siswa_id'],
                'nama_jenis_tagihan_siswa' => $validatedData['nama_jenis_tagihan_siswa'],
                'tanggal_jatuh_tempo' => $validatedData['tanggal_jatuh_tempo'],
                'deskripsi' => $validatedData['deskripsi']
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil mengubah jenis tagihan!'
            ]);

            $this->dispatchBrowserEvent('hide-edit-modal', [
                'modalId' => 'ModalEditJenisTagihan'
            ]);

            $this->emit('refreshJenisTagihans');
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal validasi, cek input!'
            ]);

            throw $e; // 🔥 WAJIB agar @error tampil

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kategori = [];
        if ($this->ms_jenjang_id && $this->ms_tahun_ajar_id) {
            $select_kategori = KategoriTagihanSiswa::where('ms_jenjang_id', $this->ms_jenjang_id)
                ->where('ms_tahun_ajar_id', $this->ms_tahun_ajar_id)
                ->get();
        }
        return view('livewire.keuangan.jenis-tagihan-siswa.edit', [
            'select_kategori' => $select_kategori
        ]);
    }
}
