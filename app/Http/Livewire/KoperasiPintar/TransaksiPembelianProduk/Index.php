<?php

namespace App\Http\Livewire\KoperasiPintar\TransaksiPembelianProduk;

use App\Models\Jenjang;
use App\Models\KoperasiPintar\ProdukKoperasi;
use App\Models\KoperasiPintar\SupplierKoperasi;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedJenjang = null;
    public $namaJenjang = '';

    public $keranjang = [];
    public $select_supplier;

    public $barcodeInput;
    public $tanggal_pembelian;
    public $ms_supplier_koperasi_id;
    public $metode_pembayaran = 'tunai';
    public $deskripsi;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
        'refreshPembelian' => '$refresh',
        'refreshSupplierOnly' => 'loadSupplierList',
    ];

    public function mount()
    {
        $this->tanggal_pembelian = now()->format('Y-m-d');
        $this->loadSupplierList(); // agar rapi
        $this->ms_supplier_koperasi_id = '';
    }

    public function loadSupplierList()
    {
        $this->select_supplier = SupplierKoperasi::orderBy('nama_supplier_koperasi')->get();
    }

    public function getSupplierNamaProperty()
    {
        if (!$this->ms_supplier_koperasi_id) {
            return null;
        }

        return $this->select_supplier
            ->firstWhere('ms_supplier_koperasi_id', $this->ms_supplier_koperasi_id)
            ->nama_supplier_koperasi ?? null;
    }

    public function updateParameters($jenjang)
    {
        $this->selectedJenjang = $jenjang;

        $j = Jenjang::find($jenjang);
        $this->namaJenjang = $j ? $j->nama_jenjang : 'Tidak Diketahui';
    }

    public function tambahDariBarcode()
    {
        // ✅ 1. Cek supplier wajib dipilih
        if (!$this->ms_supplier_koperasi_id) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Silakan pilih supplier terlebih dahulu!'
            ]);

            $this->barcodeInput = '';
            // $this->emit('focusBarcode');
            return;
        }

        // ✅ 2. Ambil produk dari kode barcode
        $produk = ProdukKoperasi::where('kode_produk_koperasi', $this->barcodeInput)->first();

        if (!$produk) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Produk belum terdaftar!'
            ]);

            $this->barcodeInput = '';
            // $this->emit('focusBarcode');
            return;
        }

        // ✅ 3. Jika produk sudah ada → tambah qty
        foreach ($this->keranjang as $key => $item) {
            if ($item['produk_id'] == $produk->ms_produk_koperasi_id) {

                $this->keranjang[$key]['jumlah'] += 1;
                $this->keranjang[$key]['subtotal'] =
                    $this->keranjang[$key]['jumlah'] * $this->keranjang[$key]['harga_beli'];

                $this->dispatchBrowserEvent('alertify-success', [
                    'message' => 'Jumlah produk ditambah!'
                ]);

                $this->barcodeInput = '';
                // $this->emit('focusBarcode');
                return;
            }
        }

        // ✅ 4. Jika produk baru → tambahkan ke keranjang
        $this->keranjang[] = [
            'produk_id' => $produk->ms_produk_koperasi_id,
            'nama'      => $produk->nama_produk_koperasi,
            'jumlah'    => 1,
            'satuan'     => $produk->satuan ?? '-',
            'harga_beli' => $produk->harga_beli,
            'subtotal'  => $produk->harga_beli,
        ];

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Produk berhasil ditambahkan ke keranjang!'
        ]);

        $this->barcodeInput = '';
        // $this->emit('focusBarcode');
    }

    public function updateJumlah($index)
    {
        $qty = (int) $this->keranjang[$index]['jumlah'];
        $harga = (int) $this->keranjang[$index]['harga_beli'];

        if ($qty < 1) $qty = 1;

        $this->keranjang[$index]['jumlah'] = $qty;
        $this->keranjang[$index]['subtotal'] = $qty * $harga;
    }

    public function updateHarga($index)
    {
        $qty = (int) $this->keranjang[$index]['jumlah'];
        $harga = (int) $this->keranjang[$index]['harga_beli'];

        $this->keranjang[$index]['subtotal'] = $qty * $harga;
    }

    public function hapusItem($index)
    {
        // Validasi index agar tidak error
        if (!isset($this->keranjang[$index])) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Item tidak ditemukan!'
            ]);
            return;
        }

        // Ambil nama produk untuk notifikasi
        $namaProduk = $this->keranjang[$index]['nama'];

        // Hapus item
        unset($this->keranjang[$index]);
        $this->keranjang = array_values($this->keranjang); // reset index array

        // Alertify sukses
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => "Produk <strong>{$namaProduk}</strong> berhasil dihapus!"
        ]);
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.transaksi-pembelian-produk.index');
    }
}
