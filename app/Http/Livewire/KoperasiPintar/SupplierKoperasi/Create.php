<?php

namespace App\Http\Livewire\KoperasiPintar\SupplierKoperasi;

use App\Models\SupplierKoperasi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    // LISTENER UNTUK MEMBUKA MODAL
    protected $listeners = ['showCreateSupplierKoperasi'];

    // PROPERTI FORM SESUAI FILLABLE MODEL
    public $nama_supplier_koperasi;
    public $alamat;
    public $telepon;
    public $email;
    public $npwp;
    public $deskripsi;

    // SAAT MODAL DIBUKA
    public function showCreateSupplierKoperasi()
    {
        $this->resetInput();
        $this->emitSelf('render');
    }

    // RESET FORM
    public function resetInput()
    {
        $this->nama_supplier_koperasi = '';
        $this->alamat = '';
        $this->telepon = '';
        $this->email = '';
        $this->npwp = '';
        $this->deskripsi = '';
    }

    // VALIDASI
    protected function rules()
    {
        return [
            'nama_supplier_koperasi' => 'required|string|max:150',
            'alamat'                 => 'nullable|string|max:255',
            'telepon'                => 'nullable|string|max:30',
            'email'                  => 'nullable|email|max:150',
            'npwp'                   => 'nullable|string|max:100',
            'deskripsi'              => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'nama_supplier_koperasi.required' => 'Nama supplier wajib diisi.',
        'email.email'                     => 'Format email tidak valid.',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    // SIMPAN DATA
    public function save()
    {
        $validated = $this->validate();

        DB::beginTransaction();
        try {

            SupplierKoperasi::create([
                'nama_supplier_koperasi' => $this->nama_supplier_koperasi,
                'alamat'                 => $this->alamat,
                'telepon'                => $this->telepon,
                'email'                  => $this->email,
                'npwp'                   => $this->npwp,
                'deskripsi'              => $this->deskripsi,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah supplier koperasi!']);
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalTambahSupplierKoperasi']);
            $this->emit('refreshSupplierOnly');
            $this->resetInput();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.koperasi-pintar.supplier-koperasi.create');
    }
}
