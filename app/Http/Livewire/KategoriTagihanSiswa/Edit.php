<?php

namespace App\Http\Livewire\KategoriTagihanSiswa;

use App\Models\KategoriTagihanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

class Edit extends Component
{
    // Relasi
    public $ms_kategori_tagihan_siswa_id;

    // Form input
    public $nama_kategori_tagihan_siswa;
    public $urutan;
    public $deskripsi;    

    protected $listeners = [
        'loadDataKategoriTagihan',
    ];

    public function loadDataKategoriTagihan($ms_kategori_tagihan_siswa_id)
    {
        $this->resetValidation();

        $kategori = KategoriTagihanSiswa::findOrFail($ms_kategori_tagihan_siswa_id);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Data dimuat'
        ]);
        
        $this->ms_kategori_tagihan_siswa_id = $kategori->ms_kategori_tagihan_siswa_id;
        $this->nama_kategori_tagihan_siswa = $kategori->nama_kategori_tagihan_siswa;
        $this->urutan = $kategori->urutan;
        $this->deskripsi = $kategori->deskripsi;
    }

    protected function rules()
    {
        return [
            'nama_kategori_tagihan_siswa' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_tagihan_siswa.required' => 'Nama kategori tidak boleh kosong',
        'urutan.required' => 'Urutan tidak boleh kosong',
        'urutan.integer' => 'Urutan harus berupa angka',
        'urutan.min' => 'Urutan minimal 1',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updateKategori()
    {
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();

            $kategori = KategoriTagihanSiswa::findOrFail($this->ms_kategori_tagihan_siswa_id);

            $kategori->update([
                'nama_kategori_tagihan_siswa' => $validatedData['nama_kategori_tagihan_siswa'],
                'urutan' => $validatedData['urutan'],
                'deskripsi' => $validatedData['deskripsi'],
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil mengubah kategori!'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalEditKategoriTagihan'
            ]);

            $this->emit('refreshKategoriTagihans');
            $this->emit('refreshJenisTagihans');
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal validasi, cek input!'
            ]);

            throw $e; // 🔥 penting untuk tampilkan error di blade

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.kategori-tagihan-siswa.edit');
    }
}
