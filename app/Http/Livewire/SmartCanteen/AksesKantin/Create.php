<?php

namespace App\Http\Livewire\SmartCanteen\AksesKantin;

use App\Models\SmartCanteen\Kantin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
    public $nama, $telepon, $email, $password;

    public $ms_kantin_id;        // 🔥 single select

    protected $listeners = ['createPetugasKantin'];

    public function createPetugasKantin()
    {
        $this->resetValidation();
        $this->resetInput();
    }

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|unique:ms_pengguna,email',
            'password' => 'required|string|min:6',

            // 🔥 kantin = single
            'ms_kantin_id' => 'required|exists:ms_kantin,ms_kantin_id',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama tidak boleh kosong',
        'email.required' => 'Email tidak boleh kosong',
        'email.unique' => 'Email sudah digunakan',
        'password.required' => 'Password wajib diisi',
        'ms_kantin_id.required' => 'Pilih minimal 1 kantin',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            // 1. create user
            $user = User::create([
                'nama' => $this->nama,
                'telepon' => $this->telepon,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'peran' => 'KANTIN',
            ]);

            // 3. akses kantin (single)
            DB::table('ms_akses_kantin')->insert([
                'ms_pengguna_id' => $user->ms_pengguna_id,
                'ms_kantin_id' => $this->ms_kantin_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Petugas kantin berhasil ditambahkan'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'createPetugasKantin'
            ]);

            $this->resetInput();

            $this->emit('refreshKantin');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage()
            ]);
        }
    }

    public function resetInput()
    {
        $this->nama = '';
        $this->telepon = '';
        $this->email = '';
        $this->password = '';
        $this->ms_kantin_id = [];
    }

    public function render()
    {
        return view('livewire.smart-canteen.akses-kantin.create', [
            'kantinList' => Kantin::get()
        ]);
    }
}
