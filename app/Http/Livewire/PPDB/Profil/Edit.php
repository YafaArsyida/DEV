<?php

namespace App\Http\Livewire\PPDB\Profil;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public $jenjangSlug;

    public $nama = '';
    public $email = '';
    public $telepon = '';

    public $password = '';
    public $password_confirmation = '';

    public function mount($jenjang)
    {
        $this->jenjangSlug = is_object($jenjang)
            ? $jenjang->slug
            : $jenjang;

        $pengguna = auth()->user();

        $this->nama = $pengguna->nama;
        $this->email = $pengguna->email;
        $this->telepon = $pengguna->telepon;
    }

    protected function rules()
    {
        $pengguna = auth()->user();

        return [
            'nama' => 'required|string|max:255',

            'email' => [
                'required',
                'max:255',
                Rule::unique('ms_pengguna', 'email')
                    ->ignore(
                        $pengguna->ms_pengguna_id,
                        'ms_pengguna_id'
                    ),
            ],

            'telepon' => 'nullable|string|max:20',

            'password' => 'nullable|string|min:6|confirmed',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.unique' => 'Email sudah digunakan akun lain.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function simpan()
    {
        $this->validate();

        $pengguna = User::findOrFail(
            auth()->user()->ms_pengguna_id
        );

        $pengguna->nama = $this->nama;
        $pengguna->email = $this->email;
        $pengguna->telepon = $this->telepon;

        if (filled($this->password)) {
            $pengguna->password = Hash::make($this->password);
        }

        $pengguna->save();

        $this->password = '';
        $this->password_confirmation = '';

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Profil akun berhasil diperbarui.',
        ]);

        $this->emit('profilUpdated');
    }

    public function render()
    {
        return view('livewire.p-p-d-b.profil.edit');
    }
}
