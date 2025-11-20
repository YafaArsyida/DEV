<?php

namespace App\Http\Livewire\KoperasiPintar\ProdukKoperasi;

use App\Models\KategoriProdukKoperasi;
use App\Models\ProdukKoperasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $ms_produk_koperasi_id;

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

    protected $listeners = [
        'showEditProdukKoperasi'
    ];

    public function showEditProdukKoperasi($id)
    {
        $this->resetErrorBag();

        $produk = ProdukKoperasi::findOrFail($id);

        $this->ms_produk_koperasi_id = $id;

        $this->ms_jenjang_id = $produk->ms_jenjang_id;
        $this->ms_kategori_produk_koperasi_id = $produk->ms_kategori_produk_koperasi_id;
        $this->kode_produk_koperasi = $produk->kode_produk_koperasi;
        $this->nama_produk_koperasi = $produk->nama_produk_koperasi;
        $this->satuan = $produk->satuan;
        $this->stok = $produk->stok;
        $this->harga_beli = $produk->harga_beli;
        $this->harga_jual = $produk->harga_jual;
        $this->status_produk_koperasi = $produk->status_produk_koperasi;
        $this->deskripsi = $produk->deskripsi;
    }

    protected function rules()
    {
        return [
            'ms_kategori_produk_koperasi_id' => 'required|exists:ms_kategori_produk_koperasi,ms_kategori_produk_koperasi_id',
            'kode_produk_koperasi' => 'required|string|max:100',
            'nama_produk_koperasi' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status_produk_koperasi' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string|max:1000',
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        DB::beginTransaction();

        try {
            ProdukKoperasi::where('ms_produk_koperasi_id', $this->ms_produk_koperasi_id)
                ->update([
                    'ms_kategori_produk_koperasi_id' => $this->ms_kategori_produk_koperasi_id,
                    'kode_produk_koperasi' => $this->kode_produk_koperasi,
                    'nama_produk_koperasi' => $this->nama_produk_koperasi,
                    'satuan' => $this->satuan,
                    'stok' => $this->stok,
                    'harga_beli' => $this->harga_beli,
                    'harga_jual' => $this->harga_jual,
                    'status_produk_koperasi' => $this->status_produk_koperasi,
                    'deskripsi' => $this->deskripsi,
                    'ms_pengguna_id' => Auth::id(),
                ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Produk berhasil diperbarui!']);

            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalEditProdukKoperasi']);

            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.produk-koperasi.edit', [
            'kategoriList' => KategoriProdukKoperasi::all(),
        ]);
    }
}
