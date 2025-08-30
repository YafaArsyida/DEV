<?php

namespace App\Http\Livewire\SmartCanteen\KategoriProdukKantin;

use App\Models\KategoriProdukKantin;
use Livewire\Component;

class Edit extends Component
{
    public $ms_kategori_produk_kantin_id, $ms_jenjang_id, $nama_kategori_produk_kantin, $icon, $deskripsi;

    protected $listeners = [
        'loadDataKategori',
    ];

    public function loadDataKategori($id)
    {
        $kategori = KategoriProdukKantin::findOrFail($id);
        $this->ms_kategori_produk_kantin_id = $kategori->ms_kategori_produk_kantin_id;
        $this->ms_jenjang_id = $kategori->ms_jenjang_id;
        $this->nama_kategori_produk_kantin = $kategori->nama_kategori_produk_kantin;
        $this->icon = $kategori->icon;
        $this->deskripsi = $kategori->deskripsi;
    }

    protected function rules()
    {
        return [
            'nama_kategori_produk_kantin' => 'required|string|max:50',
            'ms_jenjang_id' => 'required',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_produk_kantin.required' => 'Nama kategori tidak boleh kosong',
        'ms_jenjang_id.required' => 'Pilih jenjang',
    ];

    public function update()
    {
        try {
            $validated = $this->validate();

            $kategori = KategoriProdukKantin::findOrFail($this->ms_kategori_produk_kantin_id);
            $kategori->update([
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'nama_kategori_produk_kantin' => $this->nama_kategori_produk_kantin,
                'deskripsi' => $this->deskripsi,
                'icon' => $this->icon,
            ]);

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil mengubah kategori!']);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalEditKategori']);
            $this->emit('refreshKategori');
            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.smart-canteen.kategori-produk-kantin.edit');
    }
}
