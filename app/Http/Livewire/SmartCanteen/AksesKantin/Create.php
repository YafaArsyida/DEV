<?php

namespace App\Http\Livewire\SmartCanteen\AksesKantin;

use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{
    public $nama, $email, $password;

    public $ms_kantin_id;        // 🔥 single select
    public $ms_jenjang_id = [];  // 🔥 multi jenjang

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

            // 🔥 jenjang = multi
            'ms_jenjang_id' => 'required|array|min:1',
            'ms_jenjang_id.*' => 'exists:ms_jenjang,ms_jenjang_id',
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
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'peran' => 'KANTIN',
            ]);

            // 2. akses jenjang (multi)
            foreach ($this->ms_jenjang_id as $jenjangId) {
                DB::table('ms_akses_jenjang')->insert([
                    'ms_pengguna_id' => $user->ms_pengguna_id,
                    'ms_jenjang_id' => $jenjangId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

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
        $this->email = '';
        $this->password = '';
        $this->ms_kantin_id = [];
    }

    public function render()
    {
        return view('livewire.smart-canteen.akses-kantin.create', [
            'jenjangList' => Jenjang::where('status', 'Aktif')->get(),
            'kantinList' => Kantin::get()
        ]);
    }
}
