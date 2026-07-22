<?php

namespace App\Http\Livewire\JenisTagihanSiswa;

use App\Models\JenisTagihanSiswa;
use App\Models\KategoriTagihanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use Illuminate\Validation\ValidationException;

class Create extends Component
{
    public $ms_tahun_ajar_id;
    public $ms_jenjang_id;
    public $ms_kategori_tagihan_siswa_id;

    public $nama_jenis_tagihan_siswa;
    public $tanggal_jatuh_tempo;
    public $deskripsi;

    protected $listeners = [
        'showCreateJenis'
    ];

    public function showCreateJenis($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function rules()
    {
        return [
            'nama_jenis_tagihan_siswa' => 'required|string|max:255',
            'ms_jenjang_id' => 'required|exists:ms_jenjang,ms_jenjang_id',
            'ms_tahun_ajar_id' => 'required|exists:ms_tahun_ajar,ms_tahun_ajar_id',
            'ms_kategori_tagihan_siswa_id' => 'required|exists:ms_kategori_tagihan_siswa,ms_kategori_tagihan_siswa_id',
            'tanggal_jatuh_tempo' => 'required|date',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_jenis_tagihan_siswa.required' => 'Nama tagihan tidak boleh kosong',

        'ms_jenjang_id.required' => 'Pilih jenjang',
        'ms_jenjang_id.exists' => 'Jenjang tidak valid',

        'ms_tahun_ajar_id.required' => 'Pilih tahun ajar',
        'ms_tahun_ajar_id.exists' => 'Tahun ajar tidak valid',

        'ms_kategori_tagihan_siswa_id.required' => 'Pilih kategori tagihan',
        'ms_kategori_tagihan_siswa_id.exists' => 'Kategori tidak valid',

        'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi',
        'tanggal_jatuh_tempo.date' => 'Format tanggal tidak valid',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();

            JenisTagihanSiswa::create([
                'nama_jenis_tagihan_siswa' => $validatedData['nama_jenis_tagihan_siswa'],
                'ms_jenjang_id'            => $validatedData['ms_jenjang_id'],
                'ms_tahun_ajar_id'         => $validatedData['ms_tahun_ajar_id'],
                'ms_kategori_tagihan_siswa_id' => $validatedData['ms_kategori_tagihan_siswa_id'],
                'tanggal_jatuh_tempo'      => $validatedData['tanggal_jatuh_tempo'],
                'deskripsi'                => $validatedData['deskripsi'],
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil menambah jenis tagihan!'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAddJenisTagihan'
            ]);

            $this->resetInput();

            $this->emit('refreshJenisTagihans');
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek input!'
            ]);

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function resetInput()
    {
        $this->nama_jenis_tagihan_siswa = '';
        $this->tanggal_jatuh_tempo = '';
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
