<?php

namespace App\Http\Livewire\KoperasiPintar\KategoriProdukKoperasi;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\KategoriProdukKoperasi;

class Create extends Component
{
    public $ms_jenjang_id, $nama_kategori_produk_koperasi, $deskripsi;
    protected $listeners = [
        'showCreateKategoriKoperasi',
    ];

    public function showCreateKategoriKoperasi($jenjang)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_kategori_produk_koperasi' => 'required|string|max:50',
            'ms_jenjang_id' => 'required',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_produk_koperasi.required' => 'Nama kategori tidak boleh kosong',
        'ms_jenjang_id.required' => 'Pilih jenjang',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $validatedData = $this->validate();
        DB::beginTransaction();

        try {

            KategoriProdukKoperasi::create([
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'nama_kategori_produk_koperasi' => $this->nama_kategori_produk_koperasi,
                'status'  => 'aktif',
                'deskripsi'  => $this->deskripsi,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah kategori!']);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        $this->resetInput();
        $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalTambahKategoriKoperasi']);
        $this->emit('refreshKategori');
        $this->emit('refreshProduk');
    }

    public function resetInput()
    {
        $this->nama_kategori_produk_koperasi = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.kategori-produk-koperasi.create');
    }
}
