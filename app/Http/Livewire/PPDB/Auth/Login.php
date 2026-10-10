<?php

namespace App\Http\Livewire\PPDB\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    
    protected function rules()
    {
        return [
            'email' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
            ],
            'remember' => [
                'boolean',
            ],
        ];
    }

    protected $messages = [
        'email.required' => 'Email atau username wajib diisi.',
        'email.max' => 'Email atau username maksimal 255 karakter.',

        'password.required' => 'Password wajib diisi.',

        'remember.boolean' => 'Pilihan ingat saya tidak valid.',
    ];


    public function login()
    {
        // Validasi form dan tampilkan alert jika gagal.
        try {
            $validated = $this->validate();
        } catch (ValidationException $e) {
            $this->dispatchBrowserEvent('login-error', [
                'message' => $e->validator->errors()->first(),
            ]);

            return;
        }

        // Autentikasi menggunakan email dan password.
        $credentials = [
            'email' => trim($validated['email']),
            'password' => $validated['password'],
        ];

        if (!Auth::attempt($credentials, (bool) $this->remember)) {
            $this->addError(
                'email',
                'Email atau password tidak sesuai.'
            );

            $this->reset('password');

            $this->dispatchBrowserEvent('login-error', [
                'message' => 'Email atau password tidak sesuai. Periksa kembali data Anda.',
            ]);

            return;
        }

        // Pastikan akun memiliki peran orang tua.
        $user = Auth::user();

        if ($user->peran !== 'ORANG_TUA') {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            $this->addError(
                'email',
                'Akun ini tidak memiliki akses ke portal orang tua PPDB.'
            );

            $this->reset('password');

            $this->dispatchBrowserEvent('login-error', [
                'message' => 'Akun ini tidak memiliki akses ke portal orang tua PPDB.',
            ]);

            return;
        }

        // Regenerasi session untuk keamanan.
        request()->session()->regenerate();

        // Bersihkan password dari state Livewire.
        $this->reset('password');

        // Tampilkan notifikasi berhasil sebelum redirect.
        $this->dispatchBrowserEvent('login-success');
    }



    public function render()
    {
        return view('livewire.p-p-d-b.auth.login');
    }
}
