<?php

namespace App\Http\Livewire\PPDB\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $nama = '';
    public $email = '';
    public $telepon = '';
    public $password = '';
    public $password_confirmation = '';
    public $agreement = false;

    protected function rules()
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                // 'email',
                'max:255',
                'unique:ms_pengguna,email',
            ],

            'telepon' => [
                'required',
                'string',
                'max:20',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'agreement' => [
                'accepted',
            ],
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama orang tua/wali wajib diisi.',
        'nama.max' => 'Nama maksimal 255 karakter.',

        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email ini sudah terdaftar. Silakan login menggunakan akun yang sudah ada.',
        'email.max' => 'Email maksimal 255 karakter.',

        'telepon.required' => 'Nomor WhatsApp wajib diisi.',
        'telepon.max' => 'Nomor WhatsApp maksimal 20 karakter.',

        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',

        'agreement.accepted' => 'Kamu harus menyetujui syarat dan ketentuan PPDB.',
    ];

    public function register()
    {
        // Validasi gagal akan ditangani Livewire.
        // Proses berhenti tanpa redirect dan tanpa insert data.
        $validated = $this->validate();

        // Simpan akun dalam transaksi database.
        $user = DB::transaction(function () use ($validated) {
            return User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'password' => Hash::make($validated['password']),
                'peran' => 'ORANG_TUA',
            ]);
        });

        // Login otomatis menggunakan akun yang baru dibuat.
        Auth::login($user);

        // Regenerasi session untuk keamanan.
        request()->session()->regenerate();

        // Bersihkan password dari state komponen.
        $this->reset([
            'password',
            'password_confirmation',
        ]);

        // Beri tahu browser bahwa registrasi berhasil.
        $this->dispatchBrowserEvent('registration-success');
    }

    public function render()
    {
        return view('livewire.p-p-d-b.auth.register');
    }
}
