<?php

namespace App\Http\Livewire\JenisTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public $ms_tahun_ajar_id, $ms_jenjang_id, $ms_jenis_tagihan_siswa_id, $ms_kategori_tagihan_siswa_id, $nama_jenis_tagihan_siswa, $tanggal_jatuh_tempo, $deskripsi;

    protected $listeners = [
        'loadDataJenisTagihan',
    ];

    public function loadDataJenisTagihan($ms_jenis_tagihan_siswa_id)
    {
        $jenis = JenisTagihanSiswa::findOrFail($ms_jenis_tagihan_siswa_id);

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
        $validatedData = $this->validate();

        try {
            // Ambil data jenis tagihan berdasarkan ID dan update
            $jenisTagihan = JenisTagihanSiswa::findOrFail($this->ms_jenis_tagihan_siswa_id);
            $jenisTagihan->update([
                'ms_kategori_tagihan_siswa_id' => $this->ms_kategori_tagihan_siswa_id,
                'nama_jenis_tagihan_siswa' => $this->nama_jenis_tagihan_siswa,
                'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo,
                'deskripsi' => $this->deskripsi
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah jenis tagihan!']);
            $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditJenisTagihan']);
            $this->emit('refreshJenisTagihans');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
        return view('livewire.jenis-tagihan-siswa.edit', [
            'select_kategori' => $select_kategori
        ]);
    }
}
