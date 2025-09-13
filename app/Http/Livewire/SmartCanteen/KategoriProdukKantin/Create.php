<?php

namespace App\Http\Livewire\SmartCanteen\KategoriProdukKantin;

use App\Models\KategoriProdukKantin;
use App\Models\KategoriProdukSmartCanteen;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $ms_jenjang_id, $nama_kategori_produk_kantin, $icon, $deskripsi;
    protected $listeners = [
        'showCreateKategori',
    ];

    public function showCreateKategori($jenjang)
    {
        $this->ms_jenjang_id = $jenjang;
        $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_kategori_produk_kantin' => 'required|string|max:50',
            'ms_jenjang_id' => 'required',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_produk_kantin.required' => 'Nama kategori tidak boleh kosong',
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

            KategoriProdukSmartCanteen::create([
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'nama_kategori_produk_kantin' => $this->nama_kategori_produk_kantin,
                'deskripsi'  => $this->deskripsi,
                'icon'       => $this->icon,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah kategori!']);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        $this->resetInput();
        $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalTambahKategori']);
        $this->emit('refreshKategori');
        $this->emit('refreshProduk');
    }

    public function resetInput()
    {
        $this->nama_kategori_produk_kantin = '';
        $this->icon = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.smart-canteen.kategori-produk-kantin.create');
    }
}
