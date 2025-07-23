<?php

namespace App\Http\Livewire\Pengguna;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ResetPassword extends Component
{
    public $ms_pengguna_id; // ID pengguna yang akan di-reset
    public $newPassword; // Password baru yang akan diberikan

    protected $listeners = ['resetPassword' => 'resetPassword'];

    // Menampilkan modal reset password
    public function resetPassword($ms_pengguna_id)
    {
        $this->ms_pengguna_id = $ms_pengguna_id;
        $this->newPassword = null; // Reset password baru ke default
    }

    // Proses reset password
    public function resetPass()
    {
        $user = User::findOrFail($this->ms_pengguna_id);

        // Buat password default baru
        $defaultPassword = '123456'; // Anda bisa menyesuaikan default ini
        $user->password = Hash::make($defaultPassword);
        $user->save();

        // Emit event untuk notifikasi di index
        // $this->emit('refreshPengguna');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Password berhasil di-reset!']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalKonfirmasiReset']);

        // Reset properti
        $this->ms_pengguna_id = null;
        $this->newPassword = null;
    }

    public function render()
    {
        return view('livewire.pengguna.reset-password');
    }
}
