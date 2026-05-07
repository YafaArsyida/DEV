<?php

namespace App\Http\Livewire\SmartCanteen\AksesKantin;

use App\Models\Jenjang;
use App\Models\SmartCanteen\Kantin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    public $ms_pengguna_id;

    public $nama, $email, $password;
    public $ms_kantin_id;
    public $ms_jenjang_id = [];

    protected $listeners = ['editPengguna'];

    public function editPengguna($id)
    {
        $this->resetValidation();
        $this->resetInput();

        $user = User::with(['ms_kantin', 'ms_jenjang'])->findOrFail($id);

        $this->ms_pengguna_id = $user->ms_pengguna_id;
        $this->nama = $user->nama;
        $this->email = $user->email;

        // 🔥 ambil kantin (single)
        $this->ms_kantin_id = optional($user->ms_kantin->first())->ms_kantin_id;

        // 🔥 ambil jenjang (multi)
        $this->ms_jenjang_id = $user->ms_jenjang
            ->pluck('ms_jenjang_id')
            ->toArray();
    }

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|unique:ms_pengguna,email,' . $this->ms_pengguna_id . ',ms_pengguna_id',
            'password' => 'nullable|min:6',

            'ms_kantin_id' => 'required|exists:ms_kantin,ms_kantin_id',

            'ms_jenjang_id' => 'required|array|min:1',
            'ms_jenjang_id.*' => 'exists:ms_jenjang,ms_jenjang_id',
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function update()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            $user = User::findOrFail($this->ms_pengguna_id);

            // 🔥 update basic
            $user->update([
                'nama' => $this->nama,
                'email' => $this->email,
            ]);

            // 🔥 update password jika diisi
            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            // =========================
            // 🔥 SYNC JENJANG
            // =========================
            DB::table('ms_akses_jenjang')
                ->where('ms_pengguna_id', $user->ms_pengguna_id)
                ->delete();

            foreach ($this->ms_jenjang_id as $jenjangId) {
                DB::table('ms_akses_jenjang')->insert([
                    'ms_pengguna_id' => $user->ms_pengguna_id,
                    'ms_jenjang_id' => $jenjangId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // =========================
            // 🔥 REPLACE KANTIN (single)
            // =========================
            DB::table('ms_akses_kantin')
                ->where('ms_pengguna_id', $user->ms_pengguna_id)
                ->delete();

            DB::table('ms_akses_kantin')->insert([
                'ms_pengguna_id' => $user->ms_pengguna_id,
                'ms_kantin_id' => $this->ms_kantin_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Petugas berhasil diperbarui'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'editPengguna'
            ]);

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
        $this->ms_kantin_id = null;
        $this->ms_jenjang_id = [];
    }

    public function render()
    {
        return view('livewire.smart-canteen.akses-kantin.edit',[
            'kantinList' => Kantin::get(),
            'jenjangList' => Jenjang::where('status', 'Aktif')->get(),
        ]);
    }
}
