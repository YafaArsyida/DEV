<?php

namespace App\Http\Livewire\SmartCanteen\KategoriProdukKantin;

use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $ms_kantin_id, $nama_kategori_produk_kantin, $icon, $deskripsi;

    protected $listeners = [
        'showCreateKategori',
    ];

    public function showCreateKategori($kantin)
    {
        $this->ms_kantin_id = $kantin;
        $this->emitSelf('render');
    }

    protected function rules()
    {
        return [
            'nama_kategori_produk_kantin' => 'required|string|max:50',
            'ms_kantin_id' => 'required',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_kategori_produk_kantin.required' => 'Nama kategori tidak boleh kosong',
        'ms_kantin_id.required' => 'Pilih Kantin',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        DB::beginTransaction();

        try {

            $this->validate();

            KategoriProdukSmartCanteen::create([
                'ms_kantin_id' => $this->ms_kantin_id,
                'nama_kategori_produk_kantin' => $this->nama_kategori_produk_kantin,
                'deskripsi'  => $this->deskripsi,
                'icon'       => $this->icon,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah kategori!']);
            
            $this->resetInput();

            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalTambahKategori']);
           
            $this->emit('refreshKategori');
            $this->emit('refreshProduk');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
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
