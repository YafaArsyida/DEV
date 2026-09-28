<?php

namespace App\Http\Livewire\Keuangan\KategoriTagihanSiswa;

use App\Models\KategoriTagihanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use Illuminate\Validation\ValidationException;

class Create extends Component
{
    public $ms_tahun_ajar_id;
    public $ms_jenjang_id;

    public $nama_kategori_tagihan_siswa;
    public $urutan;
    public $deskripsi;

    protected $listeners = [
        'showCreateKategori',
    ];

    public function showCreateKategori($jenjang, $tahunAjar)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->ms_tahun_ajar_id = $tahunAjar;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function rules()
    {
        return [
            'nama_kategori_tagihan_siswa' => 'required|string|max:255',
            'ms_jenjang_id' => 'required|exists:ms_jenjang,ms_jenjang_id',
            'ms_tahun_ajar_id' => 'required|exists:ms_tahun_ajar,ms_tahun_ajar_id',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_tagihan_siswa.required' => 'Nama kategori tidak boleh kosong',
        'ms_jenjang_id.required' => 'Pilih jenjang',
        'ms_jenjang_id.exists' => 'Jenjang tidak valid',
        'ms_tahun_ajar_id.required' => 'Pilih tahun ajar',
        'ms_tahun_ajar_id.exists' => 'Tahun ajar tidak valid',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan minimal 1',
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

            KategoriTagihanSiswa::create([
                'nama_kategori_tagihan_siswa' => $validatedData['nama_kategori_tagihan_siswa'],
                'ms_jenjang_id' => $validatedData['ms_jenjang_id'],
                'ms_tahun_ajar_id' => $validatedData['ms_tahun_ajar_id'],
                'urutan' => $validatedData['urutan'],
                'deskripsi' => $validatedData['deskripsi'],
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil menambah kategori!'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAddKategoriTagihan'
            ]);

            $this->resetInput();

            $this->emit('refreshKategoriTagihans');
            $this->emit('refreshJenisTagihans');
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek input!'
            ]);

            throw $e; // 🔥 supaya error muncul di blade

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function resetInput()
    {
        $this->nama_kategori_tagihan_siswa = '';
        $this->urutan = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.keuangan.kategori-tagihan-siswa.create');
    }
}
