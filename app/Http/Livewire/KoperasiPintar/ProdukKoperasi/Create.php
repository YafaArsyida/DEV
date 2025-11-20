<?php

namespace App\Http\Livewire\KoperasiPintar\ProdukKoperasi;

use App\Models\KategoriProdukKoperasi;
use App\Models\ProdukKoperasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $ms_jenjang_id;
    public $ms_kategori_produk_koperasi_id;
    public $kode_produk_koperasi;
    public $nama_produk_koperasi;
    public $satuan = 'pcs';
    public $stok = 1;
    public $harga_beli;
    public $harga_jual;
    public $status_produk_koperasi = 'aktif';
    public $deskripsi;

    protected $listeners = ['showCreateProdukKoperasi'];

    public function showCreateProdukKoperasi($jenjang)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->emitSelf('render');
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->ms_kategori_produk_koperasi_id = null;
        $this->kode_produk_koperasi = '';
        $this->nama_produk_koperasi = '';
        $this->satuan = 'pcs';
        $this->stok = 1;
        $this->harga_beli = '';
        $this->harga_jual = '';
        $this->status_produk_koperasi = 'aktif';
        $this->deskripsi = '';
    }

    protected function rules()
    {
        return [
            'ms_kategori_produk_koperasi_id' => 'required|exists:ms_kategori_produk_koperasi,ms_kategori_produk_koperasi_id',
            'kode_produk_koperasi' => 'required|string|max:50|unique:ms_produk_koperasi,kode_produk_koperasi',
            'nama_produk_koperasi' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status_produk_koperasi' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string|max:1000'
        ];
    }

    protected $messages = [
        'ms_jenjang_id' => 'required',
        'ms_jenjang_id.required' => 'Pilih jenjang',
        'ms_kategori_produk_koperasi_id.required' => 'Kategori wajib dipilih.',
        'kode_produk_koperasi.required' => 'Kode produk wajib diisi.',
        'kode_produk_koperasi.unique' => 'Kode produk sudah digunakan.',
        'nama_produk_koperasi.required' => 'Nama produk wajib diisi.',
        'harga_jual.required' => 'Harga jual wajib diisi.',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        $validated = $this->validate();

        DB::beginTransaction();

        try {

            ProdukKoperasi::create([
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'ms_kategori_produk_koperasi_id' => $this->ms_kategori_produk_koperasi_id,
                'kode_produk_koperasi' => $this->kode_produk_koperasi,
                'nama_produk_koperasi' => $this->nama_produk_koperasi,
                'satuan' => $this->satuan,
                'stok' => $this->stok,
                'harga_beli' => $this->harga_beli,
                'harga_jual' => $this->harga_jual,
                'status_produk_koperasi' => $this->status_produk_koperasi,
                'deskripsi' => $this->deskripsi,
                'ms_pengguna_id' => Auth::id()
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah produk koperasi!']);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalTambahProdukKoperasi']);
            $this->emit('refreshProduk');
            $this->resetInput();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.produk-koperasi.create', [
            'kategoriList' => KategoriProdukKoperasi::all(),
        ]);
    }
}
