<?php

namespace App\Http\Livewire\Pegawai;

use App\Http\Controllers\HelperController;
use App\Models\EduCard;
use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\Pegawai;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Create extends Component
{
    public $nama_pegawai, $nip, $telepon, $email, $alamat, $deskripsi, $educard;
    public $ms_jabatan_id;

    public $nama_jenjang; // Tambahkan variabel untuk menyimpan jenjang
    public $selectedJenjang; // Tambahkan variabel untuk menyimpan jenjang

    protected $listeners = [
        'PegawaiCreate',
    ];

    public function PegawaiCreate($selectedJenjang)
    {
        $this->selectedJenjang = $selectedJenjang;
        $this->nama_jenjang = Jenjang::where('ms_jenjang_id', $selectedJenjang)->value('nama_jenjang');
    }

    protected function rules()
    {
        return [
            'nama_pegawai' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|string|max:20',
            'ms_jabatan_id' => 'required|exists:ms_jabatan,ms_jabatan_id',
            'nip' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_pegawai.required' => 'Nama pegawai tidak boleh kosong',
        'nama_pegawai.string' => 'Nama pegawai harus berupa teks',
        'nama_pegawai.max' => 'Nama pegawai maksimal 255 karakter',

        'telepon.required' => 'Telepon tidak boleh kosong',
        'telepon.string' => 'Telepon harus berupa teks',
        'telepon.max' => 'Telepon maksimal 20 karakter',

        'ms_jabatan_id.required' => 'Kelas tidak boleh kosong',
        'ms_jabatan_id.exists' => 'Kelas tidak valid',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // 1️⃣ Generate email jika kosong (2 kata nama)
            $email = $this->email ?: HelperController::generateEmailFromNama($this->nama_pegawai);
            $email = HelperController::makeUniqueEmail($email);

            // 2️⃣ Buat akun user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'nama'     => $this->nama_pegawai,
                    'telepon'  => $this->telepon ?: null,
                    'password' => Hash::make('123456'),
                    'peran'    => HelperController::mapRoleDariJabatan($this->ms_jabatan_id),
                    'current_session' => null,
                ]
            );

            // 3️⃣ Insert data pegawai + simpan user_id
            $pegawai = Pegawai::create([
                'nama_pegawai' => $this->nama_pegawai,
                'nip'          => $this->nip,
                'user_id'      => $user->ms_pengguna_id,   // FK ke user
                'ms_jabatan_id' => $this->ms_jabatan_id,
                'ms_jenjang_id' => $this->selectedJenjang,
                'telepon'      => $this->telepon ?: null,
                'email'        => $email,
                'alamat'       => $this->alamat,
                'deskripsi'    => $this->deskripsi,
                'ms_pengguna_id' => auth()->id(), // petugas yang input
            ]);

            // 4️⃣ EduCard (kalau ada)
            if (!empty($this->educard)) {
                EduCard::create([
                    'ms_pegawai_id'  => $pegawai->ms_pegawai_id,
                    'ms_pengguna_id' => auth()->id(), // petugas yang input
                    'kode_kartu'     => $this->educard,
                    'jenis_pemilik'  => 'pegawai',
                    'status_kartu'   => 'aktif',
                    'deskripsi'      => 'EduCard ' . $this->nama_pegawai,
                ]);
            }

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah pegawai + akun login!']);
            $this->resetInput();
            $this->dispatchBrowserEvent('hide-modal', ['modalId' => 'ModalPegawaiCreate']);
            $this->emit('JabatanIndex');
            $this->emit('PegawaiIndex');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Gagal menambah pegawai: ' . $e->getMessage()
            ]);
        }
    }

    public function resetInput()
    {
        $this->nama_pegawai = '';
        $this->telepon = '';
        $this->nip = '';
        $this->email = '';
        $this->alamat = '';
        $this->telepon = '';
        $this->deskripsi = '';
        $this->educard = '';
        // jika modal hide nonaktif ini ahrus nonaktif
        // $this->ms_jabatan_id = '';
    }

    public function render()
    {
        $select_jabatan = [];
        $select_jabatan = Jabatan::get();
        return view('livewire.pegawai.create', [
            'select_jabatan' => $select_jabatan,
        ]);
    }
}
