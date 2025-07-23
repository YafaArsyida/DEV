<?php

namespace App\Http\Livewire\Pengguna;

use App\Models\Jenjang;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $nama, $email, $password, $peran;
    public $ms_jenjang_id = [];

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|unique:ms_pengguna,email',
            'password' => 'required|string|min:6',
            'peran' => 'required|string|max:50',
            'ms_jenjang_id' => 'required|array|min:1', // Validasi bahwa ms_jenjang_id dipilih
            'ms_jenjang_id.*' => 'exists:ms_jenjang,ms_jenjang_id',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama petugas tidak boleh kosong',
        'email.required' => 'Email tidak boleh kosong',
        // 'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email ini sudah digunakan',
        'password.required' => 'Password tidak boleh kosong',
        'password.min' => 'Password harus minimal 6 karakter',
        'peran.required' => 'Peran tidak boleh kosong',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        $validatedData = $this->validate();
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Menyimpan pengguna baru
        $user = User::create($validatedData);

        // Loop untuk setiap jenjang yang dipilih dan masukkan ke tabel ms_akses_jenjang
        foreach ($this->ms_jenjang_id as $ms_jenjang_id) {
            DB::table('ms_akses_jenjang')->insert([
                'ms_pengguna_id' => $user->ms_pengguna_id, // ID pengguna yang baru dibuat
                'ms_jenjang_id' => $ms_jenjang_id, // ID jenjang yang dipilih
                'created_at' => now(), // Tanggal dibuat
                'updated_at' => now(), // Tanggal diupdate
            ]);
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah petugas!']);
        $this->resetInput();
        $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAddPengguna']);
        $this->emit('refreshPengguna');
    }

    public function resetInput()
    {
        $this->nama = '';
        $this->email = '';
        $this->password = '';
        $this->peran = '';
        $this->ms_jenjang_id = [];
    }
    public function render()
    {
        return view('livewire.pengguna.create', [
            'select_jenjang' => Jenjang::where('status', 'Aktif')->get(),
        ]);
    }
}
