<?php

namespace App\Http\Livewire\SmartCanteen\ProdukKantin;

use App\Models\KategoriProdukKantin;
use App\Models\KategoriProdukSmartCanteen;
use App\Models\ProdukKantin;
use App\Models\ProdukSmartCanteen;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $ms_jenjang_id;
    public $ms_kategori_produk_kantin_id;
    public $nama_produk_kantin;
    public $harga;
    public $stok = 1;
    public $satuan = 'pcs';
    public $status = 1;
    public $deskripsi;
    public $icon = 'mdi mdi-cube-outline';
    public $icon_color = 'text-primary';

    protected $listeners = ['showCreateProduk'];

    public function showCreateProduk($jenjangId = null)
    {
        $this->resetInput();
        $this->ms_jenjang_id = $jenjangId;
    }

    public function resetInput()
    {
        $this->ms_kategori_produk_kantin_id = null;
        $this->nama_produk_kantin = '';
        $this->harga = '';
        $this->stok = 1;
        $this->satuan = 'pcs';
        $this->status = 1;
        $this->deskripsi = '';
        $this->icon = 'mdi mdi-cube-outline';
        $this->icon_color = 'text-primary';
    }
    protected function rules()
    {
        return [
            'ms_jenjang_id' => 'required|exists:ms_jenjang,ms_jenjang_id',
            'ms_kategori_produk_kantin_id' => 'required|exists:ms_kategori_produk_kantin,ms_kategori_produk_kantin_id',
            'nama_produk_kantin' => 'required|string|max:255',
            'harga' => 'required|numeric|min:100',
            'stok' => 'required|integer|min:1',
            'satuan' => 'required|string|max:20',
            'status' => 'required|boolean',
            'deskripsi' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'icon_color' => 'nullable|string|max:50',
        ];
    }

    protected $messages = [
        'ms_jenjang_id.required' => 'Jenjang wajib dipilih.',
        'ms_jenjang_id.exists' => 'Jenjang tidak valid.',

        'ms_kategori_produk_kantin_id.required' => 'Kategori wajib dipilih.',
        'ms_kategori_produk_kantin_id.exists' => 'Kategori tidak valid.',

        'nama_produk_kantin.required' => 'Nama produk tidak boleh kosong.',
        'nama_produk_kantin.max' => 'Nama produk maksimal 255 karakter.',

        'harga.required' => 'Harga wajib diisi.',
        'harga.numeric' => 'Harga harus berupa angka.',
        'harga.min' => 'Harga minimal Rp 100.',

        'stok.required' => 'Stok wajib diisi.',
        'stok.integer' => 'Stok harus berupa angka bulat.',
        'stok.min' => 'Minimal stok adalah 1.',

        'satuan.required' => 'Satuan wajib diisi.',
        'satuan.max' => 'Satuan maksimal 20 karakter.',

        'status.required' => 'Status wajib diisi.',
        'status.boolean' => 'Status hanya boleh aktif atau non-aktif.',

        'deskripsi.string' => 'Deskripsi harus berupa teks.',
        'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',

        'icon.string' => 'Icon harus berupa teks.',
        'icon.max' => 'Icon maksimal 100 karakter.',

        'icon_color.string' => 'Warna icon harus berupa teks.',
        'icon_color.max' => 'Warna icon maksimal 50 karakter.',
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
            ProdukSmartCanteen::create([
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'ms_kategori_produk_kantin_id' => $this->ms_kategori_produk_kantin_id,
                'nama_produk_kantin' => $this->nama_produk_kantin,
                'harga' => $this->harga,
                'stok' => $this->stok,
                'satuan' => $this->satuan,
                'status' => $this->status,
                'deskripsi' => $this->deskripsi,
                'icon' => $this->icon,
                'icon_color' => $this->icon_color,
                'ms_pengguna_id' => Auth::id()// ID pengguna yang login
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah produk!']);
            $this->resetInput();
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalTambahProduk']);
            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function render()
    {
        return view('livewire.smart-canteen.produk-kantin.create', [
            'kategoriList' => KategoriProdukSmartCanteen::all(),
        ]);
    }
}
