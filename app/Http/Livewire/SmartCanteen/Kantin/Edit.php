<?php

namespace App\Http\Livewire\SmartCanteen\Kantin;

use App\Models\SmartCanteen\Kantin;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    public $ms_kantin_id;
    public $nama_kantin;
    public $deskripsi;

    protected $listeners = ['editKantin'];

    public function editKantin($id)
    {
        $this->resetValidation();

        $kantin = Kantin::findOrFail($id);

        $this->ms_kantin_id = $kantin->ms_kantin_id;
        $this->nama_kantin = $kantin->nama_kantin;
        $this->deskripsi = $kantin->deskripsi;
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
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function update()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            $kantin = Kantin::findOrFail($this->ms_kantin_id);

            $kantin->update([
                'nama_kantin' => $this->nama_kantin,
                'deskripsi' => $this->deskripsi,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Kantin berhasil diperbarui'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalEditKantin'
            ]);

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

    public function render()
    {
        return view('livewire.smart-canteen.kantin.edit');
    }
}
