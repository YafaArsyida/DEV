<?php

namespace App\Http\Livewire\Pegawai;

use App\Models\EduCard;
use App\Models\Jabatan;
use App\Models\Jenjang;
use App\Models\Pegawai;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $nama_pegawai, $nip, $telepon, $email, $alamat, $deskripsi, $educard;
    public $ms_jabatan_id;

    public $nama_jenjang; // Tambahkan variabel untuk menyimpan jenjang
    public $selectedJenjang; // Tambahkan variabel untuk menyimpan jenjang

    protected $listeners = [
        'showAddPegawai',
    ];

    public function showAddPegawai($selectedJenjang)
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
        // Validasi data input
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            // Insert data pegawai
            $ms_pengguna_id = Auth::id();

            $pegawai = Pegawai::create([
                'nama_pegawai' => $this->nama_pegawai,
                'nip' => $this->nip,
                'ms_pengguna_id' => $ms_pengguna_id, // ID pengguna yang login
                'ms_jabatan_id' => $this->ms_jabatan_id, // ID pengguna yang login
                'ms_jenjang_id' => $this->selectedJenjang,
                'telepon' => $this->telepon ?: null,
                'email' => $this->email,
                'alamat' => $this->alamat,
                'deskripsi' => $this->deskripsi,
            ]);

            // Logika untuk menangani kolom educard
            if (!empty($this->educard)) {
                // Insert data di tabel ms_educard
                EduCard::Create(
                    [
                        'ms_pegawai_id' => $pegawai->ms_pegawai_id, // Kondisi untuk cek apakah data sudah ada
                        'ms_pengguna_id' => Auth::id(),
                        'kode_kartu' => $this->educard, // Input dari form
                        'jenis_pemilik' => 'pegawai', // Disesuaikan dengan jenis pemilik
                        'status_kartu' => 'aktif', // Status default
                        'deskripsi' => 'EduCard ' . $this->nama_pegawai, // Bisa diubah sesuai kebutuhan
                    ]
                );
            }

            // Commit transaksi
            DB::commit();

            // Notifikasi sukses
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah pegawai!']);

            // Reset form input
            $this->resetInput();

            // Tutup modal dan refresh data siswa
            // $this->dispatchBrowserEvent('hide-create-modal', ['modalId' => 'ModalAddPegawai']);

            $this->emit('refreshJabatans');
            $this->emit('refreshPegawais');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal menambah pegawai: ' . $e->getMessage()]);
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
