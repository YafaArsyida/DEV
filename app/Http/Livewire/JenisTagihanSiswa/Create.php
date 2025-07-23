<?php

namespace App\Http\Livewire\JenisTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Livewire\Component;

class Create extends Component
{
    public $ms_tahun_ajar_id, $ms_jenjang_id, $ms_kategori_tagihan_siswa_id, $nama_jenis_tagihan_siswa, $tanggal_jatuh_tempo, $deskripsi;

    protected $listeners = [
        'showCreateJenis'
    ];

    public function showCreateJenis($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;
        $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_jenis_tagihan_siswa' => 'required|string|max:255',
            'ms_jenjang_id' => 'required',
            'tanggal_jatuh_tempo' => 'required',
            'ms_tahun_ajar_id' => 'required',
            'ms_kategori_tagihan_siswa_id' => 'required|exists:ms_kategori_tagihan_siswa,ms_kategori_tagihan_siswa_id',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_jenis_tagihan_siswa.required' => 'Nama tagihan tidak boleh kosong',
        'ms_jenjang_id.required' => 'Pilih jenjang',
        'ms_tahun_ajar_id.required' => 'Pilih tahun ajar',
        'ms_kategori_tagihan_siswa_id.required' => 'Pilih kategori tagihan',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $validatedData = $this->validate();
        try {
            JenisTagihanSiswa::create($validatedData);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah jenis tagihan!']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        $this->resetInput();
        $this->emit('refreshJenisTagihans');
    }

    public function resetInput()
    {
        $this->nama_jenis_tagihan_siswa = '';
        $this->deskripsi = '';
        $this->ms_kategori_tagihan_siswa_id = '';
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
        return view('livewire.jenis-tagihan-siswa.create', [
            'select_kategori' => $select_kategori
        ]);
    }
}
