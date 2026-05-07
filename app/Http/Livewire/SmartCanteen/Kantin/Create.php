<?php

namespace App\Http\Livewire\SmartCanteen\Kantin;

use App\Models\SmartCanteen\Kantin;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use Livewire\Component;

class Create extends Component
{
    public $nama_kantin;
    public $deskripsi;

    protected $listeners = [
        'showCreateKantin',
    ];

    public function showCreateKantin()
    {
        $this->resetValidation();
        $this->resetInput();
    }

    protected function rules()
    {
        return [
            'nama_kantin' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'nama_kantin.required' => 'Nama kantin tidak boleh kosong',
        'nama_kantin.max' => 'Nama kantin maksimal 255 karakter',
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

            Kantin::create([
                'nama_kantin' => $this->nama_kantin,
                'deskripsi' => $this->deskripsi,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Kantin berhasil ditambahkan'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalAddKantin'
            ]);

            $this->resetInput();

            // refresh index
            $this->emit('refreshKantin');
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek input!'
            ]);

            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function resetInput()
    {
        $this->nama_kantin = '';
        $this->deskripsi = '';
    }

    public function render()
    {
        return view('livewire.smart-canteen.kantin.create');
    }
}
