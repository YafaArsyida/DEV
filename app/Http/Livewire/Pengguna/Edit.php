<?php

namespace App\Http\Livewire\Pengguna;

use App\Models\Jenjang;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Edit extends Component
{
    public $ms_pengguna_id;
    public $nama, $email, $peran, $password;
    public $ms_jenjang_id = []; // Menyimpan jenjang yang dipilih
    public $selectedJenjang = []; // Menyimpan jenjang yang sudah dipilih oleh pengguna (untuk checkbox yang tercentang)

    protected $listeners = ['editPengguna'];

    public function editPengguna($ms_pengguna_id)
    {
        // Ambil data pengguna berdasarkan ID
        $pengguna = User::findOrFail($ms_pengguna_id);

        // Set properti dengan data pengguna
        $this->ms_pengguna_id = $pengguna->ms_pengguna_id;
        $this->nama = $pengguna->nama;
        $this->email = $pengguna->email;
        $this->peran = $pengguna->peran;

        // Pastikan aksesJenjang ada dan ambil data jenjang yang sudah dipilih
        if ($pengguna->ms_akses_jenjang) {
            $this->selectedJenjang = $pengguna->ms_akses_jenjang->pluck('ms_jenjang_id')->toArray();
        } else {
            $this->selectedJenjang = []; // Jika tidak ada akses jenjang, set ke array kosong
        }

        // Set ms_jenjang_id untuk checkbox
        $this->ms_jenjang_id = $this->selectedJenjang;
    }

    public function updatePengguna()
    {
        $this->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|max:25',
            'peran' => 'required|string|max:50',
            'password' => 'nullable|min:6', // Validasi password baru (opsional)
            'ms_jenjang_id' => 'required|array|min:1', // Validasi jenjang yang dipilih
        ]);

        // Cari pengguna berdasarkan ID
        $pengguna = User::findOrFail($this->ms_pengguna_id);

        // Update data pengguna
        $pengguna->update([
            'nama' => $this->nama,
            'email' => $this->email,
            'peran' => $this->peran,
        ]);

        // Jika password diisi, update password
        if (!empty($this->password)) {
            $pengguna->password = Hash::make($this->password);
            $pengguna->save();
        }

        // Hapus akses jenjang lama
        DB::table('ms_akses_jenjang')
            ->where('ms_pengguna_id', $pengguna->ms_pengguna_id)
            ->delete();

        // Masukkan akses jenjang yang baru
        foreach ($this->ms_jenjang_id as $ms_jenjang_id) {
            DB::table('ms_akses_jenjang')->insert([
                'ms_pengguna_id' => $pengguna->ms_pengguna_id,
                'ms_jenjang_id' => $ms_jenjang_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Emit event untuk memberikan feedback pada pengguna
        $this->emit('refreshPengguna');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data pengguna berhasil diperbarui']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditPengguna']);

        // Reset field input untuk modal edit
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->ms_pengguna_id = null;
        $this->nama = '';
        $this->email = '';
        $this->peran = '';
        $this->password = ''; // Reset password
        $this->ms_jenjang_id = []; // Reset jenjang yang dipilih
    }

    public function render()
    {
        return view('livewire.pengguna.edit', [
            'select_jenjang' => Jenjang::where('status', 'Aktif')->get(),
        ]);
    }
}
